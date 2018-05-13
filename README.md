
Moodle Course Merge Helper
==========================

[![Build Status](https://api.travis-ci.org/LafColITS/moodle-local_course_merge.png)](https://api.travis-ci.org/LafColITS/moodle-local_course_merge)

This local module allows teachers to create a new course from existing courses using the [Course Meta Link](https://docs.moodle.org/31/en/Course_meta_link) enrollment method. The intended use case is a teacher combining multiple sections of a course into a single course while preserving the enrollments of the original courses.

Requirements
------------
- Moodle 3.4 (build 2017111300 or later)

Installation
------------
Copy the course_merge folder into your /local directory and visit your Admin Notification page to complete the installation.

Usage
-----
To create a new course go to one of the source courses and click "Create Merged Course" in the Course Administration block. You'll be taken to a page where you can select the other courses to link into the new course, the name and other settings for the new course, and whether to hide the source courses (on by default). Once you've made all your selections click "Create". The plugin will do the following:

* Create the new course with the given settings
* Create a course meta link enrollment between each source course and the new course
* Create a group for each source course in the new course
* Hide the source courses if requested
* Move the source courses into a designated category, if requested
* Take you into the new course

Configuration
-------------
The plugin is designed to permit course creation under controlled conditions, especially in environments where editing teachers do not otherwise have the capability to create courses.

### Category depth

The plugin can restrict the courses which may be linked into the new course. There are three options:

- *Same category*: The source course and the target courses must all be in the same category.
- *Same parent*: The source course and the target courses must all be in either the same category or share the same parent category
- *Unlimited*: No category-based restrictions. This is not recommended.

Given the following course and category structure:

* All courses
    * Fall 2015
    * Fall 2016
        * English
            * English 101.01
            * English 101.02
        * History
            * History 101.01
            * History 101.02

With *same category* selected, a teacher could start with History 101.01 and select History 101.02, but not any courses under the English category. With *same parent* selected, a teacher could work with courses in both History and English, but not courses outside a given term.

### Permissions

The plugin is designed to provide a controlled course-creation capability which doesn't otherwise exist. By default it respects the `moodle/course:create` capability, but you may choose to override this.

### Templates

The plugin allows the site administrator to define naming conventions for created courses. These are defined using a series of regular expressions. See the settings page itself for examples.

Acknowledgements
----------------
This plugin relies heavily on the AJAX course selector and related course meta link interface code developed by Damyon Wiese for the Moodle 3.1 release.

Author
------
Charles Fulton (fultonc@lafayette.edu)
