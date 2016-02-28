<?php

function local_course_merge_extend_navigation_course($navigation, $course, $context) {
    if(has_capability('local/course_merge:create_course', $context)) {
        $url = new moodle_url('/local/course_merge/index.php', array('id' => $course->id));
        $navigation->add(get_string('create', 'local_course_merge'), $url,
                navigation_node::TYPE_SETTING, null, null, new pix_icon('i/settings', ''));
    }
}
