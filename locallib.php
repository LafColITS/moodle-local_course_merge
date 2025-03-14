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
 * Defined constants for local course merge.
 *
 * @package   local_course_merge
 * @copyright 2016 Lafayette College ITS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('COURSE_MERGE_DEPTH_UNLIMITED', 0);
define('COURSE_MERGE_DEPTH_SAME_CATEGORY', 1);
define('COURSE_MERGE_DEPTH_SAME_PARENT', 2);
define('COURSE_MERGE_DEFAULT_CATEGORY', -1);

/**
 * Get the advanced fields for the course merge tool.
 *
 * @return array
 */
function local_course_merge_get_advanced_settings() {
    $raw = get_config('local_course_merge', 'advancedsettings');
    $exploded = explode("\n", $raw);
    return array_map(function($a) {
        return trim($a);
    }, $exploded);
}
