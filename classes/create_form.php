<?php

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');
include_once($CFG->libdir . '/coursecatlib.php');
require('locallib.php');

class local_course_merge_create_form extends moodleform {

    function definition() {
        $mform = $this->_form;

        $course = $this->_customdata['id'];

        // Course chooser.
        $options = array('requiredcapabilities' => array('moodle/course:update'), 'multiple' => true, 'exclude' => array($course));
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
        $mform->addElement('date_selector', 'startdate', get_string('startdate'));

        // Hide child courses.
        $mform->addElement('checkbox', 'hidecourses', get_string('hidecourses', 'local_course_merge'));
        $mform->setDefault('hidecourses', 1);

        // Auto-create groups.
        $mform->addElement('checkbox', 'groupsync', get_string('groupsync', 'local_course_merge'));
        $mform->setDefault('groupsync', true);

        $mform->addElement('hidden', 'category');
        $mform->setType('category', PARAM_INT);
        $mform->addElement('hidden', 'id', $course);
        $mform->setType('id', PARAM_INT);

        $this->add_action_buttons(true, get_string('create'));
    }

    function validation($data, $files) {
        global $DB;
        $errors = array();

        $maxdepth = get_config('local_course_merge', 'maxcategorydepth');
        if ($maxdepth != COURSE_MERGE_DEPTH_UNLIMITED) {
            $droppedcourses = array();
            $validcategories = array($data['category']);
            if ($maxdepth == COURSE_MERGE_DEPTH_SAME_PARENT) {
                $parent = coursecat::get($data['category'])->get_parent_coursecat();
                $children = $DB->get_fieldset_select('course_categories', 'id', 'parent = ?', array($parent->__get('id')));
                $validcategories = array_merge($validcategories, $children);
            }
            $courses = $DB->get_records_list('course', 'id', $data['link'], null, 'id,fullname,category');
            foreach($courses as $course) {
                if(!in_array($course->category, $validcategories)) {
                    $droppedcourses[] = $course->fullname;
                }
            }
            if(!empty($droppedcourses)) {
                $errors['link'] = get_string('coursestoodeep', 'local_course_merge', implode(', ', $droppedcourses));
            }
        }

        return $errors;
    }
}
