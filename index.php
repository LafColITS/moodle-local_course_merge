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
 * Create a new meta course from multiple child courses.
 *
 * @package   local_course_merge
 * @copyright 2016 Lafayette College ITS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->dirroot.'/enrol/meta/locallib.php');

$id     = required_param('id', PARAM_INT);
$course = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);

$coursecontext = context_course::instance($course->id);

require_login($course);

$PAGE->set_url('/local/course_merge/index.php', array('id' => $id));
$PAGE->set_pagelayout('admin');
$PAGE->set_context($coursecontext);

// Permissions.
if (get_config('local_course_merge', 'respectpermissions')) {
    require_capability('moodle/course:create', context_coursecat::instance($course->category));
} else {
    require_capability('local/course_merge:create_course', $coursecontext);
}

// Course meta link has to be active.
if (!local_course_merge_helper::meta_link_enabled()) {
    $returnurl = new moodle_url('/course/view.php', array('id' => $course->id));
    print_error('metalinknotenabled', 'local_course_merge');
}

// Finish page setup.
$PAGE->set_title(get_string('create', 'local_course_merge'));
$PAGE->set_heading(get_string('create', 'local_course_merge'));

$mform = new local_course_merge_create_form('index.php', array('id' => $course->id)); // Creation form.

if ($mform->is_cancelled()) {
    $returnurl = new moodle_url('/course/view.php', array('id' => $course->id));
    redirect($returnurl);
} else if ($data = $mform->get_data()) {
    // Process data.
    $coursestolink = array_merge($data->link, array($id));

    // The [SECTIONS] variable is supported at this time; we need to post-process the data.
    if (get_config('local_course_merge', 'usenametemplates')) {
        $data = local_course_merge_extract_names::post_process($data, $coursestolink);
    }

    // Final check that this course is unique.
    local_course_merge_helper::course_exists($data, new moodle_url('/course/view.php', array('id' => $course->id)));

    // Setup course.
    $tocreate = new stdClass();
    $tocreate->category  = $course->category;
    $tocreate->startdate = $data->startdate;
    $tocreate->fullname  = $data->fullname;
    $tocreate->shortname = $data->shortname;
    $tocreate->idnumber  = $data->idnumber;
    $tocreate->visible   = 0;

    // Create the course.
    $newcourse = create_course($tocreate);
    if (!$newcourse) {
        die('Course not created');
    }

    // Create all the meta links.
    $enrol = enrol_get_plugin('meta');
    $instances = array();
    foreach ($coursestolink as $target) {
        $instances[$target] = $enrol->add_instance($newcourse, array('customint1' => $target));
        enrol_meta_sync($newcourse->id);
    }

    // Create the groups. We do this separately because the teachers don't
    // have an enrolment yet.
    foreach ($instances as $targetid => $eid) {
        $update = new stdClass();
        $update->customint1 = $targetid;
        $update->customint2 = ENROL_META_CREATE_GROUP;
        $instance = $DB->get_record('enrol', array('courseid' => $newcourse->id,
            'enrol' => 'meta', 'id' => $eid), '*', MUST_EXIST);
        $enrol->update_instance($instance, $update);
    }

    // Hide child courses.
    if (!empty($data->hidecourses) && $data->hidecourses) {
        foreach ($coursestolink as $oldcourseid) {
            $oldcourse = course_get_format($oldcourseid)->get_course();
            $oldcourse->visible = 0;
            update_course($oldcourse);
        }
    }

    // If set, move child courses.
    if (!empty($data->newchildcategory) && $data->newchildcategory != COURSE_MERGE_DEFAULT_CATEGORY) {
        move_courses($coursestolink, $data->newchildcategory);
    }

    // We're done. Go to course.
    $returnurl = new moodle_url('/course/view.php', array('id' => $newcourse->id));
    redirect($returnurl);
} else {
    // Prep the form.
    $mform->set_data(array('category' => $course->category));
}

// Display the form.
echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
