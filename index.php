<?php

require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->dirroot.'/enrol/meta/locallib.php');

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

$mform = new local_course_merge_create_form('index.php', array('id' => $course->id)); // Creation form.

if ($mform->is_cancelled()) {
    die('Cancelled');
} else if ($data = $mform->get_data()) {
    // Process data.
    $coursestolink = array_merge($data->link, array($id));

    // Setup course.
    $tocreate = new stdClass();
    $tocreate->category  = $course->category;
    $tocreate->startdate = $data->startdate;
    $tocreate->fullname  = $data->fullname;
    $tocreate->shortname = $data->shortname;
    $tocreate->idnumber  = $data->idnumber;

    // Create the course.
    $newcourse = create_course($tocreate);
    if(!$newcourse) {
        die('Course not created');
    }

    // Create all the meta links.
    $enrol = enrol_get_plugin('meta');
    foreach($coursestolink as $target) {
        $eid = $enrol->add_instance($newcourse, array('customint1' => $target));
        enrol_meta_sync($newcourse->id);
    }

    // Create the groups if desired.

    // We're done. Go to course.
    $returnurl = new moodle_url('/course/view.php', array('id' => $newcourse->id));
    redirect($returnurl);
} else {
    // Prep the form.
    $mform->set_data(array('startdate' => $course->startdate, 'category' => $course->category));
}

// Display the form.
echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
