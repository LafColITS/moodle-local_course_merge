<?php

$capabilities = array(
    'local/course_merge:create_course' => array(
        'captype'      => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes'   => array(
            'student'        => CAP_PROHIBIT,
            'teacher'        => CAP_PREVENT,
            'editingteacher' => CAP_ALLOW,
            'manager'          => CAP_ALLOW
        )
    ),
);
