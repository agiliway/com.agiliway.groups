<?php

class CRM_Groups_Common_Group {

  /**
   * Retrieves list of group
   * Format like select options
   *
   * @return array
   */
  public static function retrieveGroupList() {
    $groupsOptions = [];
    $groupDataList = CRM_Contact_BAO_Group::getGroups();

    foreach ($groupDataList as $groupData) {
      $groupsOptions[$groupData->id] = $groupData->title . ' (' . self::getContactCount($groupData->id) . ')';
    }

    return $groupsOptions;
  }

  /**
   * Gets count of contact in group
   *
   * @param $groupId
   *
   * @return int
   */
  private static function getContactCount($groupId) {
    try {
      $result = civicrm_api3('GroupContact', 'getcount', [
        'group_id' => $groupId,
        'options' => ['limit' => 0],
      ]);
    } catch (CiviCRM_API3_Exception $e) {
      return 0;
    }

    return (int) $result;
  }

}
