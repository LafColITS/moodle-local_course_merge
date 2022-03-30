@local @local_course_merge
Feature: The course merge helper allows a teacher to create a new course
  In order to create a new merged course
  As a teacher or administrator
  I need to see my courses and create new enrolments

  Background:
    Given the following "categories" exist:
      | name         | category | idnumber | visible |
      | Test Category 1   | 0        | CAT1     | 0 |
      | Test Category 2   | CAT1     | CAT2     | 0 |
      | Test Category 3   | CAT1     | CAT3     | 0 |
      | Test Category 4   | 0        | CAT4     | 0 |
      | Test Category 5   | CAT4     | CAT5     | 0 |
      | Hidden stuff | 0        |          | 1 |
    And the following "courses" exist:
      | fullname | shortname | category | visible | startdate | enddate |
      | Course 1 | C1        | CAT2     | 1 | 1514764800 | 0 |
      | Course 2 | C2        | CAT5     | 1 | 1514764800 | 0 |
      | Course 3 | C3        | CAT3     | 1 | 1514764800 | 1522540800 |
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
      | teacher2 | C1     | editingteacher |
      | teacher1 | C3     | editingteacher |
      | teacher2 | C2     | editingteacher |
      | student1 | C1     | student        |
      | student2 | C1     | student        |
      | student2 | C2     | student        |
      | student3 | C2     | student        |
      | student3 | C3     | student        |
      | student4 | C3     | student        |
    And I log in as "admin"
    And I navigate to "Plugins > Enrolments > Manage enrol plugins" in site administration
    And I click on "Enable" "link" in the "Course meta link" "table_row"
    And I navigate to "Plugins > Local plugins > Course Merge Helper" in site administration
    And I set the field "Respect standard permissions" to "0"
    And I press "Save changes"
    And I log out

  Scenario: Teacher lacks permissions by default
    When I log in as "admin"
    And I navigate to "Plugins > Local plugins > Course Merge Helper" in site administration
    And I set the field "Respect standard permissions" to "1"
    And I press "Save changes"
    And I log out
    And I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I should not see "Create merged course shell"

  @javascript
  Scenario: Teacher cannot exceed category depth
    When I log in as "teacher2"
    And I am on "Course 2" course homepage
    And I navigate to "Create merged course shell" in current page administration
    And I set the following fields to these values:
      | Courses to merge  | Course 1 |
      | Course full name  | Test merged course |
      | Course short name | Test course        |
      | Course ID number  | C4                 |
    And I press "Create"
    And I should see "The following courses cannot be merged with this course: Course 1"
    And I log out
    And I log in as "admin"
    And I navigate to "Plugins > Local plugins > Course Merge Helper" in site administration
    And I set the field "Maximum category depth" to "No restrictions"
    And I press "Save changes"
    And I log out
    And I log in as "teacher2"
    And I am on "Course 2" course homepage
    And I navigate to "Create merged course shell" in current page administration
    And I set the following fields to these values:
      | Courses to merge  | Course 1 |
      | Course full name  | Test merged course |
      | Course short name | Test course        |
      | Course ID number  | C4                 |
    And I press "Create"
    And I should not see "The following courses cannot be merged with this course: Course 1"

  @javascript
  Scenario: Create a new course and hide old courses
    Given I am on the "My courses" page logged in as "student3"
    And I should see "Course 2"
    And I click on "All" "button" in the "Course overview" "block"
    When I click on "Past" "link" in the "Course overview" "block"
    Then I should see "Course 3"
    And I log out
    And I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I navigate to "Create merged course shell" in current page administration
    And I set the following fields to these values:
      | Courses to merge  | Course 3 |
      | Course full name  | Test merged course |
      | Course short name | Test course        |
      | Course ID number  | C4                 |
    And I press "Create"
    And I should see "Test merged course"
    And I am on the "Test merged course" "Enrolment methods" page
    And I should see "Course meta link (Course 1)"
    And I should see "Course meta link (Course 3)"
    And I am on "Test merged course" course homepage
    And I follow "Participants"
    And I should see "Sally Student"
    And I should see "Steve Student"
    And I should see "Sadie Student"
    And I should see "Shawn Student"
    And I am on "Test merged course" course homepage
    And I navigate to "Settings" in current page administration
    And I set the following fields to these values:
      | Course visibility | Show |
    And I press "Save and display"
    And I log out
    And I log in as "student3"
    And I click on "Past" "button" in the "Course overview" "block"
    When I click on "In progress" "link" in the "Course overview" "block"
    Then I should see "Test merged course"
    And I should see "Course 2"
    And I should not see "Course 3"

  @javascript
  Scenario: Create new courses with groups
    Given I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I navigate to "Create merged course shell" in current page administration
    And I set the following fields to these values:
      | Courses to merge  | Course 3 |
      | Course full name  | Test merged course |
      | Course short name | Test course        |
      | Course ID number  | C4                 |
    And I press "Create"
    And I should see "Test merged course"
    And I am on the "Test merged course" "Groups" page
    And I should see "Course 1 course (4)"
    And I should see "Course 3 course (3)"

  @javascript
  Scenario: Create a new course and move the old courses
    Given I log in as "admin"
    And I navigate to "Plugins > Local plugins > Course Merge Helper" in site administration
    And I set the field "Default child course category" to "Hidden stuff"
    And I press "Save changes"
    And I am on "Course 1" course homepage
    And I navigate to "Create merged course shell" in current page administration
    And I set the following fields to these values:
      | Courses to merge                    | Course 3           |
      | Course full name                    | Test merged course |
      | Course short name                   | Test course        |
      | Course ID number                    | C4                 |
      | Move child courses to this category | Hidden stuff       |
    And I press "Create"
    And I should see "Test merged course"
    And I go to the courses management page
    And I follow "Test Category 1"
    And I should not see "Course 1"
    And I should not see "Course 3"
    And I follow "Hidden stuff"
    And I should see "Course 1"
    And I should see "Course 3"

  @javascript
  Scenario: Create a new course with the same end date
    Given I log in as "teacher1"
    And I am on "Course 3" course homepage
    And I navigate to "Create merged course shell" in current page administration
    And I set the following fields to these values:
      | Courses to merge  | Course 1 |
      | Course full name  | Test merged course |
      | Course short name | Test course        |
      | Course ID number  | C4                 |
    And I press "Create"
    And I should see "Test merged course"
    And I navigate to "Settings" in current page administration
    And the following fields match these values:
      | id_enddate_day       | 1       |
      | id_enddate_month     | April   |
      | id_enddate_year      | 2018    |
      | id_enddate_enabled   | 1       |
