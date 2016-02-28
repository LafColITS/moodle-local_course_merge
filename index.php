<?php

require_once(dirname(__FILE__) . '/../../config.php');

$id     = required_param('id', PARAM_INT);
$course = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);

$coursecontext = context_course::instance($course->id);

require_login($course);

$PAGE->set_url('/local/course_merge/index.php', array('id' => $id));
$PAGE->set_pagelayout('admin');
$PAGE->set_context($coursecontext);

// Permissions.
require_capability('local/course_merge:create_course', $coursecontext);

// Finish page setup.
$PAGE->set_title(get_string('create', 'local_course_merge'));
$PAGE->set_heading(get_string('create', 'local_course_merge'));

$mform = new local_course_merge_select_form('index.php', array('id' => $id));
if ($mform->is_cancelled()) {
    die('Cancelled');
} else if ($data = $mform->get_data()) {
    $mform2 = new local_course_merge_create_form('index.php', array('id' => $id, 'link' => $data->link));
    echo $OUTPUT->header();
    $mform2->display();
    echo $OUTPUT->footer();
} else {
    // Display selection page.
    echo $OUTPUT->header();
    $mform->display();
    echo $OUTPUT->footer();
}
