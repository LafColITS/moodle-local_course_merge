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
 * Navigation for the course merge tool.
 *
 * @package   local_course_merge
 * @copyright 2016 Lafayette College ITS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function local_course_merge_extend_navigation_course($navigation, $course, $context) {
    $respectpermissions = get_config('local_course_merge', 'respectpermissions');
    if ($course->category == 0) {
        return;
    }
    if (($respectpermissions && has_capability('moodle/course:create', context_coursecat::instance($course->category)))
    || (!$respectpermissions && has_capability('local/course_merge:create_course', $context))) {
        $url = new moodle_url('/local/course_merge/index.php', array('id' => $course->id));
        $navigation->add(get_string('create', 'local_course_merge'), $url,
                navigation_node::TYPE_SETTING, null, null, new pix_icon('i/settings', ''));
    }
}
