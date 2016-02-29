<?php

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');

class local_course_merge_create_form extends moodleform {

    function definition() {
        $mform = $this->_form;

        $course = $this->_customdata['id'];

        // Course chooser.
        $options = array('showhidden' => true, 'requiredcapabilities' => array('moodle/course:update'), 'multiple' => true, 'excludecourseid' => $course);
        $mform->addElement('course', 'link', get_string('coursestomerge', 'local_course_merge'), $options);
        $mform->addRule('link', get_string('required'), 'required', null, 'client');

        // Fullname.
        $mform->addElement('text', 'fullname', get_string('fullnamecourse'), 'maxlength="254" size="50"');
        $mform->setType('fullname', PARAM_TEXT);
        $mform->addRule('fullname', null, 'required', null, 'client');

        // Shortname.
        $mform->addElement('text', 'shortname', get_string('shortnamecourse'), 'maxlength="100" size="20"');
        $mform->setType('shortname', PARAM_TEXT);
        $mform->addRule('shortname', null, 'required', null, 'client');

        // ID number.
        $mform->addElement('text', 'idnumber', get_string('idnumbercourse'), 'maxlength="100" size="10"');
        $mform->setType('idnumber', PARAM_RAW);

        // Start date.
        $mform->addElement('date_selector', 'startdate', 'foo');

        $mform->addElement('hidden', 'id', $course);
        $mform->setType('id', PARAM_INT);

        $this->add_action_buttons(true, get_string('create'));
    }
}
