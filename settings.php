<?php

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_course_merge', get_string('pluginname', 'local_course_merge'));
    $ADMIN->add('localplugins', $settings);

    $settings->add(new admin_setting_configtext('local_course_merge/coursenameformat', get_string('coursenameformat', 'local_course_merge'), get_string('coursenameformat_desc', 'local_course_merge'), '', PARAM_NOTAGS));
    $settings->add(new admin_setting_configtext('local_course_merge/maxcategorydepth', get_string('maxcategorydepth', 'local_course_merge'), get_string('maxcategorydepth_desc', 'local_course_merge'), 1, PARAM_INT));
    $settings->add(new admin_setting_configtext('local_course_merge/mergedcoursenameformat', get_string('mergedcoursenameformat', 'local_course_merge'), get_string('mergedcoursenameformat_desc', 'local_course_merge'), '', PARAM_NOTAGS));
    $settings->add(new admin_setting_configcheckbox('local_course_merge/respectpermissions', get_string('respectpermissions', 'local_course_merge'), get_string('respectpermissions_desc', 'local_course_merge'), 0));
}
