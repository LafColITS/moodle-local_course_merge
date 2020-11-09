# Changelog

## 3.7.0 (November 9, 2020)

- Change default branch to "main"
- Update CI tool to version 3
- Drop support for Moodle 3.6
- Allow admin to configure which form fields are advanced

## 3.6.2 (January 9, 2020)

- Ensure course default settings are used.

## 3.6.1 (May 19, 2019)

- Set most fields to advanced by default

## 3.6.0 - 2018-11-27

- Update internal API in light of [MDL-62742](https://tracker.moodle.org/browse/MDL-62742)

## 3.4.0 - 2018-05-13

- Updated for GDPR compliance

## 3.3.3 - 2018-01-17

- Fixed bug in which new courses end dates were not actually set.

## 3.3.2 - 2017-08-25

- Added missing language string

## 3.3.1 - 2017-07-28

- New courses are created with the source courses' end date, if set
- Added PHPUnit tests
- Miscellaneous code cleanup

## 3.3.0 - 2017-06-27

- Changed version numbering to match stable version
- Managers may now move source courses into a designated category

## 1.1.2 - 2017-04-24

- Updated tests for Moodle 3.3

## 1.1.0 - 2016-12-21

- Teachers can now modify the course full name on creation
- Groups will always be created
- Changed the verbiage for hiding the original courses
- Removed the option to change the course start date

## 1.0.1 - 2016-11-08

- Tested up to Moodle 3.2 beta
- Fixed bug which prevented teachers from creating courses in some cases

## 1.0.0 - 2016-10-27

- Initial stable release
- Fixed bug which prevented teachers from creating courses within hidden categories

## 0.2.1 - 2016-08-09

- Suppressed display of the tool on the front page

## 0.2.0 - 2016-06-22

- Ensure that course meta link is enabled
- Change permissions model to respect core permissions by default
- Rebrand as "Course Merge Helper"

## 0.1.0 - 2016-05-23

- Initial public release
