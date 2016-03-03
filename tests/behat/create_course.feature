@local @local_course_merge
Feature: The course merge wizard allows a teacher to create a new course
  In order to create a new merged course
  As a teacher or administrator
  I need to see my courses and create new enrolments

Background:
  Given the following "categories" exist:
    | name       | category | idnumber |
    | Category 1 | 0        | CAT1     |
    | Category 2 | CAT1     | CAT2     |
    | Category 3 | CAT1     | CAT3     |
    | Category 4 | 0        | CAT4     |
    | Category 5 | CAT4     | CAT5     |
  And the following "courses" exist:
    | fullname | shortname | category |
    | Course 1 | C1        | CAT2     |
    | Course 2 | C2        | CAT5     |
    | Course 3 | C3        | CAT3     |
  And the following "users" exist:
    | username | firstname | lastname | email |
    | teacher1 | Terry | Teacher | teacher1@example.com |
    | teacher2 | Timmy | Teacher | teacher2@example.com |
    | student1 | Sally | Student | student1@example.com |
    | student2 | Steve | Student | student2@example.com |
    | student3 | Sadie | Student | student3@example.com |
    | student4 | Shawn | Student | student4@example.com |
  And the following "course enrolments" exist:
    | user     | course | role           |
    | teacher1 | C1     | editingteacher |
    | teacher1 | C3     | editingteacher |
    | teacher2 | C2     | editingteacher |
    | student1 | C1     | student        |
    | student2 | C1     | student        |
    | student2 | C2     | student        |
    | student3 | C2     | student        |
    | student3 | C3     | student        |
    | student4 | C3     | student        |
  And I log in as "admin"
  And I navigate to "Manage enrol plugins" node in "Site administration > Plugins > Enrolments"
  And I click on "Enable" "link" in the "Course meta link" "table_row"
  And I log out

@javascript
Scenario: Teacher sees only her courses
  When I log in as "teacher1"
  And I follow "Course 1"
  And I follow "Create merged course"
  And I set the following fields to these values:
    | Courses to merge | Course |
  Then I should not see "Course 2" in the ".form-autocomplete-suggestions" "css_element"

@javascript
Scenario: Admin sees all courses
  When I log in as "admin"
  And I follow "Courses"
  And I follow "Category 1"
  And I follow "Category 2"
  And I follow "Course 1"
  And I follow "Create merged course"
  And I set the following fields to these values:
    | Courses to merge | Course |
  Then I should see "Course 2" in the ".form-autocomplete-suggestions" "css_element"

@javascript
Scenario: Create a new course
  When I log in as "teacher1"
  And I follow "Course 1"
  And I follow "Create merged course"
  And I set the following fields to these values:
    | Courses to merge | Course |
  And I click on "Course 3" "list_item" in the ".form-autocomplete-suggestions" "css_element"
  And I set the following fields to these values:
    | Course full name  | Test merged course |
    | Course short name | Test course        |
    | Course ID number  | C4                 |
  And I press "Create"
  And I should see "Test merged course"
  And I navigate to "Enrolment methods" node in "Course administration > Users"
  And I should see "Course meta link (Course 1)"
  And I should see "Course meta link (Course 3)"
  And I navigate to "Enrolled users" node in "Course administration > Users"
  And I should see "Sally Student"
  And I should see "Steve Student"
  And I should see "Sadie Student"
  And I should see "Shawn Student"
