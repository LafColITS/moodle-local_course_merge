<?php

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');

class local_course_merge_create_form extends moodleform {

    private $courses;
    private $fullnames = array();
    private $startdate = null;

    function definition() {
        $mform = $this->_form;

        $course = $this->_customdata['id'];

        $this->preload_courses();

        $mform->addElement('select', 'courses', 'Bar', $this->get_fullnames());

        $mform->addElement('date_selector', 'startdate', 'foo');
        $mform->setDefault('startdate', $this->get_startdate());

        $mform->addElement('hidden', 'id', $course);
        $mform->setType('id', PARAM_INT);

        $this->add_action_buttons(true, get_string('create'));
    }

    private function preload_courses() {
        global $DB;

        $courselist = array_merge($this->_customdata['link'], array($this->_customdata['id']));
        $this->courses = $DB->get_records_list('course', 'id', $courselist, null, 'id,fullname,shortname,startdate');
    }

    private function get_fullnames() {
        if(empty($this->fullnames)) {
            foreach($this->courses as $course) {
                $this->fullnames[$course->id] = $course->fullname;
            }
        }
        return $this->fullnames;
    }

    private function get_startdate() {
        if($this->startdate === null) {
            foreach($this->courses as $course) {
                if($this->startdate === null || $course->startdate > $this->startdate) {
                    $this->startdate = $course->startdate;
                }
            }
        }
        return $this->startdate;
    }
}
