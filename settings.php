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
 * Local course merge settings definitions.
 *
 * @package   local_course_merge
 * @copyright 2016 Lafayette College ITS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/course_merge/locallib.php');

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_course_merge', get_string('pluginname', 'local_course_merge'));
    $ADMIN->add('localplugins', $settings);

    $settings->add(new admin_setting_heading('general', new lang_string('generalsettings', 'local_course_merge'),
        new lang_string('generalsettingsinfo', 'local_course_merge')));

    $settings->add(new admin_setting_configselect(
        'local_course_merge/maxcategorydepth',
        get_string('maxcategorydepth', 'local_course_merge'),
        get_string('maxcategorydepth_desc', 'local_course_merge'),
        COURSE_MERGE_DEPTH_SAME_PARENT,
        array(
            COURSE_MERGE_DEPTH_UNLIMITED => get_string('coursemergeunlimited', 'local_course_merge'),
            COURSE_MERGE_DEPTH_SAME_CATEGORY => get_string('coursemergesamecategory', 'local_course_merge'),
            COURSE_MERGE_DEPTH_SAME_PARENT => get_string('coursemergesameparent', 'local_course_merge'),
        )
    ));

    $categories = local_course_merge\helper::get_category_selector();
    $settings->add(new admin_setting_configselect('local_course_merge/defaultcategory',
        new lang_string('defaultcategory', 'local_course_merge'),
        new lang_string('defaultcategory_desc', 'local_course_merge'),
        COURSE_MERGE_DEFAULT_CATEGORY,
        $categories)
    );

    $settings->add(new admin_setting_configcheckbox('local_course_merge/respectpermissions',
        get_string('respectpermissions', 'local_course_merge'), get_string('respectpermissions_desc', 'local_course_merge'), 1));

    $settings->add(new admin_setting_configcheckbox('local_course_merge/usenametemplates',
        new lang_string('usenametemplates', 'local_course_merge'),
        new lang_string('usenametemplates_desc', 'local_course_merge'), 0));

    $settings->add(new admin_setting_heading('extractname', new lang_string('extractname', 'local_course_merge'),
        new lang_string('extractnameinfo', 'local_course_merge')));

    $settings->add(new admin_setting_configtext('local_course_merge/extractnametitle',
        new lang_string('extractnametitle', 'local_course_merge'),
        new lang_string('extractnametitle_desc', 'local_course_merge'), '', PARAM_NOTAGS));

    $settings->add(new admin_setting_configtext('local_course_merge/extractnamedept',
        new lang_string('extractnamedept', 'local_course_merge'),
        new lang_string('extractnamedept_desc', 'local_course_merge'), '', PARAM_NOTAGS));

    $settings->add(new admin_setting_configtext('local_course_merge/extractnamenum',
        new lang_string('extractnamenum', 'local_course_merge'),
        new lang_string('extractnamenum_desc', 'local_course_merge'), '', PARAM_NOTAGS));

    $settings->add(new admin_setting_configtext('local_course_merge/extractnameterm',
        new lang_string('extractnameterm', 'local_course_merge'),
        new lang_string('extractnameterm_desc', 'local_course_merge'), '', PARAM_NOTAGS));

    $settings->add(new admin_setting_configtext('local_course_merge/extractnamesection',
        new lang_string('extractnamesection', 'local_course_merge'),
        new lang_string('extractnamesection_desc', 'local_course_merge'), '', PARAM_NOTAGS));

    $settings->add(new admin_setting_configtext('local_course_merge/extracttermcode',
        new lang_string('extracttermcode', 'local_course_merge'),
        new lang_string('extracttermcode_desc', 'local_course_merge'), '', PARAM_NOTAGS));

    $settings->add(new admin_setting_configtext('local_course_merge/mergedcoursenameformat',
        get_string('mergedcoursenameformat', 'local_course_merge'),
        get_string('mergedcoursenameformat_desc', 'local_course_merge'), '', PARAM_NOTAGS));

    $settings->add(new admin_setting_configtext('local_course_merge/mergedcourseshortnameformat',
        get_string('mergedcourseshortnameformat', 'local_course_merge'),
        get_string('mergedcourseshortnameformat_desc', 'local_course_merge'), '', PARAM_NOTAGS));

    $settings->add(new admin_setting_configtext('local_course_merge/mergedcourseidnumberformat',
        get_string('mergedcourseidnumberformat', 'local_course_merge'),
        get_string('mergedcourseidnumberformat_desc', 'local_course_merge'), '', PARAM_NOTAGS));
}
