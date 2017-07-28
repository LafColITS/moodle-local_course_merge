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
 * Helper functions.
 *
 * @package   local_course_merge
 * @copyright 2016 Lafayette College ITS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_course_merge;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/course_merge/locallib.php');

class helper {
    public static function course_exists($course, $url) {
        global $DB;

        if ($DB->record_exists('course', array('shortname' => $course->shortname))) {
            throw new \moodle_exception('shortnameexists', 'local_course_merge', $url, $course->shortname);
        }

        if (!empty($course->idnumber) && $DB->record_exists('course', array('idnumber' => $course->idnumber))) {
            throw new \moodle_exception('idnumberexists', 'local_course_merge', $url, $course->idnumber);
        }
    }

    public static function meta_link_enabled() {
        $enrolplugins = \core_plugin_manager::instance()->get_enabled_plugins('enrol');
        return array_key_exists('meta', $enrolplugins);
    }

    public static function get_parent_coursecat($category) {
        $parents = \coursecat::get($category, MUST_EXIST, true)->get_parents();
        return \coursecat::get(end($parents), MUST_EXIST, true);
    }

    public static function get_category_selector() {
        $categories = \coursecat::make_categories_list();
        $default = array(COURSE_MERGE_DEFAULT_CATEGORY  => get_string('defaultcategorytop', 'local_course_merge'));
        return $default + $categories;
    }
}
