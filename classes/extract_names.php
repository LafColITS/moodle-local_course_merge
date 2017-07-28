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
 * Helper functions for name processing.
 *
 * @package   local_course_merge
 * @copyright 2016 Lafayette College ITS
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_course_merge;

defined('MOODLE_INTERNAL') || die();

class extract_names {

    public static function get_default_fullname($course) {
        $fullname = get_config('local_course_merge', 'mergedcoursenameformat');
        return self::replace_tokens($fullname, $course);
    }

    public static function get_default_idnumber($course) {
        $idnumber = get_config('local_course_merge', 'mergedcourseidnumberformat');
        return self::replace_tokens($idnumber, $course);
    }

    public static function get_default_shortname($course) {
        $shortname = get_config('local_course_merge', 'mergedcourseshortnameformat');
        return self::replace_tokens($shortname, $course);
    }

    public static function post_process($data, $courseids) {
        global $DB;

        $fields = array('fullname', 'shortname', 'idnumber');
        $courses = $DB->get_records_list('course', 'id', $courseids, '', 'fullname');
        $sections = array();
        foreach ($courses as $course) {
            $sections[] = ltrim(self::replace_token('[SECTION]', $course), '0');
        }
        sort($sections);
        $sectionlist = implode('', $sections);
        foreach ($fields as $field) {
            $data->{$field} = str_replace('[SECTIONS]', $sectionlist, $data->{$field});
        }
        return $data;
    }

    private static function replace_tokens($pattern, $course) {
        preg_match_all('(\[[A-Z]+\])', $pattern, $matches);
        foreach ($matches[0] as $match) {
            $pattern = str_replace($match, self::replace_token($match, $course), $pattern);
        }
        return $pattern;
    }

    private static function replace_token($key, $course) {
        switch($key) {
            case '[DEPT]':
                $pattern = get_config('local_course_merge', 'extractnamedept');
                $subject = $course->fullname;
                break;
            case '[NUM]':
                $pattern = get_config('local_course_merge', 'extractnamenum');
                $subject = $course->fullname;
                break;
            case '[SECTION]':
                $pattern = get_config('local_course_merge', 'extractnamesection');
                $subject = $course->fullname;
                break;
            case '[SECTIONS]':
                return $key;
                break;
            case '[TERM]':
                $pattern = get_config('local_course_merge', 'extractnameterm');
                $subject = $course->fullname;
                break;
            case '[TERMCODE]':
                $pattern = get_config('local_course_merge', 'extracttermcode');
                $subject = $course->idnumber;
                break;
            case '[TITLE]':
                $pattern = get_config('local_course_merge', 'extractnametitle');
                $subject = $course->fullname;
                break;
            default:
                return '';
                break;
        }
        preg_match($pattern, $subject, $matches);
        if (!empty($matches) && count($matches) >= 2) {
            return $matches[1];
        } else {
            return '';
        }
    }
}
