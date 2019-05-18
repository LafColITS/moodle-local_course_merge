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

/**
 * Various helper functions for the course merge plugin.
 *
 * @package   local_course_merge
 * @copyright 2016 Lafayette College ITS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class helper {
    /**
     * Determine whether the new course would violate uniquness constraints.
     *
     * Determine whether the new course would have a non-unique shortname or idnumber
     * and throw an exception.
     *
     * @param object $course The course to be created
     * @param object $url Moodle URL object for the current (source) course
     */
    public static function course_exists($course, $url) {
        global $DB;

        if ($DB->record_exists('course', array('shortname' => $course->shortname))) {
            throw new \moodle_exception('shortnameexists', 'local_course_merge', $url, $course->shortname);
        }

        if (!empty($course->idnumber) && $DB->record_exists('course', array('idnumber' => $course->idnumber))) {
            throw new \moodle_exception('idnumberexists', 'local_course_merge', $url, $course->idnumber);
        }
    }

    /**
     * Determine whether course meta links are enabled.
     *
     * @return bool Whether the plugin is enabled
     */
    public static function meta_link_enabled() {
        $enrolplugins = \core_plugin_manager::instance()->get_enabled_plugins('enrol');
        return array_key_exists('meta', $enrolplugins);
    }

    /**
     * Get the immediate parent category of the given category.
     *
     * @param int $category The category
     * @return object The parent of the given category
     */
    public static function get_parent_coursecat($category) {
        $parents = \core_course_category::get($category, MUST_EXIST, true)->get_parents();
        return \core_course_category::get(end($parents), MUST_EXIST, true);
    }

    /**
     * Get list of categories for recategorizing child courses.
     *
     * @return array The categories for the form selector
     */
    public static function get_category_selector() {
        $categories = \core_course_category::make_categories_list();
        $default = array(COURSE_MERGE_DEFAULT_CATEGORY  => get_string('defaultcategorytop', 'local_course_merge'));
        return $default + $categories;
    }
}
