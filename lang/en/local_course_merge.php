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
 * Language strings for local_course_merge.
 *
 * @package   local_course_merge
 * @copyright 2016 Lafayette College ITS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$string['course_merge:create_course'] = 'Create merged course';
$string['coursemergesamecategory'] = 'Courses in the same category';
$string['coursemergesameparent'] = 'Courses with the same parent category';
$string['coursemergeunlimited'] = 'No restrictions';
$string['coursenameformat'] = 'Course name format';
$string['coursenameformat_desc'] = 'Regular expression describing the standard course fullname. Leave blank to disable fullname prepopulation.';
$string['coursestomerge'] = 'Courses to merge';
$string['coursestoodeep'] = 'The following courses cannot be merged with this course: {$a}';
$string['create'] = 'Create merged course';
$string['groupsync'] = 'Auto-create groups?';
$string['hidecourses'] = 'Hide child courses?';
$string['maxcategorydepth'] = 'Maximum category depth';
$string['maxcategorydepth_desc'] = 'Restrict the courses which can be merged together by category depth.';
$string['mergedcoursenameformat'] = 'Merged course name format';
$string['mergedcoursenameformat_desc'] = 'The format to suggest for a merged course name.';
$string['pluginname'] = 'Course Merge Wizard';
$string['respectpermissions'] = 'Respect standard permissions';
$string['respectpermissions_desc'] = 'By default this plugin uses its own capabilities and disregards whether teachers are allowed to create courses. Check this setting to force the plugin to respect those permissions.';
