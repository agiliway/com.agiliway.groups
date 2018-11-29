# Groups Registration and Group Assignment 

The **Groups Registration and Group Assignment** extension (`com.agiliway.groups`) allows using groups of constituents/for registering to an event and assigning activities.

Features of the extension: 
* **Group registration to an event**, e.g. constituents/with a particular role, a mailing list, or a group of constituents who have attended a particular event
* **Assigning an activity to a group of constituents** as a whole or to each particular constituent on the group list
* **Manual editing of a pulled up list of group members**: a user can delete some contacts from the pulled up  list of the group or add a new contact to it
* **Individual notifications**: each individual constituent on the group will receive a corresponding notification

## Requirements

 * CiviCRM v5.x
 * Drupal 7.x

## Installation (git/cli)
 
To install the extension on an existing CiviCRM site:
```
mkdir sites/all/modules/civicrm/ext
cd sites/all/modules/civicrm/ext
git clone https://github.com/agiliway/com.agiliway.groups groups
cv en groups
```
