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
 * Unit tests for the course merge helper.
 *
 * @package    local_course_merge
 * @category   test
 * @copyright  2017 Lafayette College ITS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot.'/local/course_merge/locallib.php');

class local_course_merge_mergecourses_testcase extends advanced_testcase {
    public function test_course_merge() {
        global $DB;
        $this->setAdminUser();
        $this->resetAfterTest(true);

        // Configure the plugin.
        set_config('maxcategorydepth', COURSE_MERGE_DEPTH_SAME_CATEGORY, 'local_course_merge');

        // Create test data.
        $coursestartdate = time();
        $courseenddate   = $coursestartdate + 86400;
        $category1 = $this->getDataGenerator()->create_category();
        $category2 = $this->getDataGenerator()->create_category(array('parent' => $category1->id));
        $course1 = $this->getDataGenerator()->create_course(array('category' => $category2->id,
            'startdate' => $coursestartdate, 'enddate' => $courseenddate));
        $course2 = $this->getDataGenerator()->create_course(array('category' => $category2->id,
            'startdate' => $coursestartdate, 'enddate' => $courseenddate));

        // Sanity check.
        $courses = $DB->count_records('course', array('category' => $category2->id));
        $this->assertEquals(2, $courses);

        // Create a merged course.
        $data = new stdClass();
        $data->category  = $course1->category;
        $data->startdate = $course1->startdate;
        $data->enddate   = $course1->enddate;
        $data->fullname  = $course1->fullname;
        $data->shortname = 'Shorter name';
        $data->idnumber  = '';
        $coursestolink   = array($course1->id, $course2->id);
        $course3 = \local_course_merge\merge_course::create_course($data, $coursestolink);
        $courses = $DB->count_records('course', array('category' => $category2->id));
        $this->assertEquals(3, $courses);
    }
}
