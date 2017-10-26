@local @local_course_merge
Feature: The course merge helper allows a manager to enforce a naming convention
  In order to create a new merged course with a standard name
  As a teacher or administrator
  I need to define a standard naming convention

  Background:
    Given the following "categories" exist:
      | name       | category | idnumber |
      | Fall 2016  | 0        | CAT1     |
      | History    | CAT1     | CAT2     |
    And the following "courses" exist:
      | fullname               | shortname             | category | idnumber    |
      | HIST 300.01-FA2016 Foo | HIST 300.01-Fall 2016 | CAT2     | 1000.201610 |
      | HIST 300.02-FA2016 Foo | HIST 300.02-Fall 2016 | CAT2     | 1001.201610 |
    And the following "users" exist:
      | username | firstname | lastname | email |
      | teacher1 | Terry | Teacher | teacher1@example.com |
      | student1 | Sally | Student | student1@example.com |
      | student2 | Steve | Student | student2@example.com |
      | student3 | Sadie | Student | student3@example.com |
      | student4 | Shawn | Student | student4@example.com |
    And the following "course enrolments" exist:
      | user     | course                | role           |
      | teacher1 | HIST 300.01-Fall 2016 | editingteacher |
      | teacher1 | HIST 300.02-Fall 2016 | editingteacher |
      | student1 | HIST 300.01-Fall 2016 | student        |
      | student2 | HIST 300.01-Fall 2016 | student        |
      | student3 | HIST 300.02-Fall 2016 | student        |
      | student4 | HIST 300.02-Fall 2016 | student        |
    And I log in as "admin"
    And I navigate to "Manage enrol plugins" node in "Site administration > Plugins > Enrolments"
    And I click on "Enable" "link" in the "Course meta link" "table_row"
    And I navigate to "Course Merge Helper" node in "Site administration > Plugins > Local plugins"
    And I set the field "Respect standard permissions" to "0"
    And I set the field "Use name templates" to "1"
    And I set the field "Course title" to "/[A-Z]+\s[0-9]+\.[0-9]+-[A-Za-z]+[0-9]{4,}\s(.*)/"
    And I set the field "Department" to "/([A-Z]+)\s[0-9]+\.[0-9]+-[A-Za-z]+[0-9]{4,}\s.*/"
    And I set the field "Number" to "/[A-Z]+\s([0-9]+)\.[0-9]+-[A-Za-z]+[0-9]{4,}\s.*/"
    And I set the field "Term" to "/[A-Z]+\s[0-9]+\.[0-9]+-([A-Za-z]+[0-9]{4,})\s.*/"
    And I set the field "Section number" to "/[A-Z]+\s[0-9]+\.([0-9]+)-[A-Za-z]+[0-9]{4,}\s.*/"
    And I set the field "Term code" to "/[0-9]+\.([0-9]+)/"
    And I set the field "Merged course name format" to "[DEPT] [NUM]-[TERM] [TITLE]"
    And I set the field "Merged course shortname format" to "[DEPT] [NUM]-[TERM]"
    And I set the field "Merged course idnumber" to "[DEPT][NUM].[SECTIONS].[TERMCODE]"
    And I press "Save changes"
    And I log out

  @javascript
  Scenario: Teacher creates course
    When I log in as "teacher1"
    And I am on "HIST 300.01-FA2016 Foo" course homepage
    And I navigate to "Create merged course shell" node in "Course administration"
    And I set the following fields to these values:
      | Courses to merge | HIST 300.02-FA2016 Foo |
      | Course full name | HIST 300-FA2016 (Terry Teacher) |
    And I wait until the page is ready
    And I press "Create"
    And I should see "HIST 300-FA2016 (Terry Teacher)"
    And I navigate to "Edit settings" node in "Course administration"
    And the following fields match these values:
      | Course ID number | HIST300.12.201610 |
