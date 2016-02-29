<?php

class local_course_merge {
    public static function preload_courses($courses) {
        global $DB;

        if(!empty($courses)) {
            $courselist = $DB->get_records_list('course', 'id', $courses, null, 'id,fullname,shortname,startdate');
            return $courselist;
        } else {
            return array();
        }
    }

    public static function get_fullnames($courses) {
        $fullnames = array();
        foreach($courses as $course) {
            $fullnames[$course->id] = $course->fullname;
        }
        return $fullnames;
    }

    public static function get_startdate($courses) {
        $startdate = null;
        if(!empty($courses)) {
            foreach($courses as $course) {
                if($startdate === null || $course->startdate > $startdate) {                    $startdate = $course->startdate;
                }
            }
        }
        return $startdate;
    }

    public static function prepare_targets($courses) {
        $targets = array();
        if(!empty($courses)) {
            foreach($courses as $course) {
                $targets[$course->id] = $course->fullname;
            }
        }
        return $targets;
    }
}
