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
 * Local course merge course creation form.
 *
 * @package   local_course_merge
 * @copyright 2016 Lafayette College ITS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_course_merge;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/formslib.php');
require_once($CFG->libdir . '/coursecatlib.php');
require_once('locallib.php');

class create_form extends \moodleform {

    public function definition() {
        global $DB;
        $mform = $this->_form;

        // Get category course data and category context.
        $course = $this->_customdata['id'];
        $coursedata = $DB->get_record('course', array('id' => $course), '*', MUST_EXIST);
        $categorycontext = \context_coursecat::instance($coursedata->category);

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
        $mform->addElement('text', 'idnumber', get_string('idnumbercourse'), 'maxlength="100" size="20"');
        $mform->setType('idnumber', PARAM_RAW);

        // Hide child courses.
        $mform->addElement('checkbox', 'hidecourses', get_string('hidecourses', 'local_course_merge'));
        $mform->setDefault('hidecourses', 1);

        // Move child courses to a category.
        if (has_capability('local/course_merge:categorize_course', $categorycontext)) {
            $categories = helper::get_category_selector();
            $mform->addElement('select', 'newchildcategory', get_string('newchildcategory', 'local_course_merge'), $categories);
            $mform->setDefault('newchildcategory', get_config('local_course_merge', 'defaultcategory'));
        } else {
            $mform->addElement('hidden', 'newchildcategory', COURSE_MERGE_DEFAULT_CATEGORY);
        }
        $mform->setType('newchildcategory', PARAM_INT);

        // Set templated defaults.
        if (get_config('local_course_merge', 'usenametemplates')) {
            $mform->setDefault('fullname', extract_names::get_default_fullname($coursedata));
            $mform->setDefault('shortname', extract_names::get_default_shortname($coursedata));
            $mform->setDefault('idnumber', extract_names::get_default_idnumber($coursedata));

            // Prevent teacher from changing templated information (except fullname).
            if (!has_capability('local/course_merge:override_format', \context_course::instance($course))) {
                $mform->hardFreeze('shortname');
                $mform->hardFreeze('idnumber');
            }
        }

        // Metadata.
        $mform->addElement('hidden', 'startdate', $coursedata->startdate);
        $mform->setType('startdate', PARAM_INT);
        $mform->addElement('hidden', 'enddate', $coursedata->enddate);
        $mform->setType('enddate', PARAM_INT);
        $mform->addElement('hidden', 'category');
        $mform->setType('category', PARAM_INT);
        $mform->addElement('hidden', 'id', $course);
        $mform->setType('id', PARAM_INT);

        $this->add_action_buttons(true, get_string('create'));
    }

    public function validation($data, $files) {
        global $DB;
        $errors = array();
        $coursestolink = $data['link'];
        $newchildcategory = $data['newchildcategory'];

        $maxdepth = get_config('local_course_merge', 'maxcategorydepth');
        if ($maxdepth != COURSE_MERGE_DEPTH_UNLIMITED) {
            $droppedcourses = array();
            $validcategories = array($data['category']);
            if ($maxdepth == COURSE_MERGE_DEPTH_SAME_PARENT) {
                $parent = helper::get_parent_coursecat($data['category']);
                $children = $DB->get_fieldset_select('course_categories', 'id', 'parent = ?', array($parent->__get('id')));
                $validcategories = array_merge($validcategories, $children);
            }
            $courses = $DB->get_records_list('course', 'id', $coursestolink, null, 'id,fullname,category');
            foreach ($courses as $course) {
                if (!in_array($course->category, $validcategories)) {
                    $droppedcourses[] = $course->fullname;
                }
            }
            if (!empty($droppedcourses)) {
                $errors['link'] = get_string('coursestoodeep', 'local_course_merge', implode(', ', $droppedcourses));
            }
        }

        // If the child category is selected, verify that the user has rights there.
        if ($newchildcategory != COURSE_MERGE_DEFAULT_CATEGORY) {
            $categorycontext = \context_coursecat::instance($newchildcategory);
            if (!has_capability('local/course_merge:categorize_course', $categorycontext)) {
                $errors['newchildcategory'] = get_string('childcategorypermissions', 'local_course_merge');
            }
        }

        return $errors;
    }
}
