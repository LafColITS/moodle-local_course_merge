<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

class local_course_merge_select_form extends moodleform {
    function definition() {
        $mform = $this->_form;

        $course = $this->_customdata['id'];

        $options = array('showhidden' => true, 'requiredcapabilities' => array('moodle/course:update'), 'multiple' => true, 'excludecourseid' => $course);
        $mform->addElement('course', 'link', 'Foo', $options);
        $mform->addRule('link', get_string('required'), 'required', null, 'client');

        $mform->addElement('hidden', 'id', $course);
        $mform->setType('id', PARAM_INT);

        $this->add_action_buttons(true, get_string('continue'));
    }
}
