<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Primary class.
 *
 * @package   local_course_merge
 * @copyright 2017 Lafayette College ITS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_course_merge;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/enrol/meta/locallib.php');

class merge_course {
    /**
     * Creates the merged course and fires off ancillary tasks.
     *
     * @param stdClass $data The course to be created.
     * @param array $coursestolink The source courses.
     *
     * @return stdClass The new course.
     */
    public static function create_course($data, $coursestolink) {

        // New course object.
        $tocreate = new \stdClass();
        $tocreate->category  = $data->category;
        $tocreate->startdate = $data->startdate;
        $tocreate->enddate   = 0;
        $tocreate->fullname  = $data->fullname;
        $tocreate->shortname = $data->shortname;
        $tocreate->idnumber  = $data->idnumber;
        $tocreate->visible   = 0;

        // Some courses have end dates.
        if (isset($data->enddate)) {
            $tocreate->enddate = $data->enddate;
        }

        // We don't check for errors; create_course() will throw an exception.
        $newcourse = create_course($tocreate);

        // Create the metalinks.
        $instances = self::link_courses($newcourse, $coursestolink);

        // Create the groups.
        self::create_groups($instances, $newcourse);

        return $newcourse;
    }

    /**
     * Create the groups in the new course. We do this separately because the teachers don't
     * have an enrolment yet.
     *
     * @param array $instances the enrol_meta instances.
     * @param stdClass $newcourse the new course.
     */
    private static function create_groups($instances, $newcourse) {
        global $DB;

        $enrol = enrol_get_plugin('meta');

        foreach ($instances as $targetid => $eid) {
            $update = new \stdClass();
            $update->customint1 = $targetid;
            $update->customint2 = ENROL_META_CREATE_GROUP;
            $instance = $DB->get_record('enrol', array('courseid' => $newcourse->id,
                'enrol' => 'meta', 'id' => $eid), '*', MUST_EXIST);
            $enrol->update_instance($instance, $update);
        }
    }

    /**
     * Hide the source courses.
     *
     * @param array $coursestolink the source courses.
     */
    public static function hide_courses($coursestolink) {
        foreach ($coursestolink as $oldcourseid) {
            $oldcourse = course_get_format($oldcourseid)->get_course();
            $oldcourse->visible = 0;
            update_course($oldcourse);
        }
    }

    /**
     * Create the metalinks between the new course and the source courses.
     *
     * @param int $newcourse the id of the new course.
     * @param array $coursestolink the ids of the source courses.
     *
     * @return array the enrol_meta instances created.
     */
    private static function link_courses($newcourse, $coursestolink) {
        $enrol = enrol_get_plugin('meta');
        $instances = array();
        foreach ($coursestolink as $target) {
            $instances[$target] = $enrol->add_instance($newcourse, array('customint1' => $target));
            enrol_meta_sync($newcourse->id);
        }
        return $instances;
    }

    /**
     * Move the source courses to the designated category.
     *
     * @param array $coursestolink the ids of the source courses.
     * @param int the id of the category.
     */
    public static function move_courses($coursestolink, $newchildcategory) {
        move_courses($coursestolink, $newchildcategory);
    }
}
