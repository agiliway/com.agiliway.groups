<?php

class CRM_Groups_API_CustomGroupsContacts {

  /**
   * Group id list
   *
   * @var array
   */
  private $groupIdList;

  /**
   * Prepared contact data list
   *
   * @var array
   */
  private $contactDataList = [];

  /**
   * Template of API response
   *
   * @var array
   */
  private $response = [
    'is_error' => 0,
    'error_message' => '',
    'values' => []
  ];

  public function __construct($params) {
    $this->groupIdList = $this->getValidateGroupIdList($params['group_id']);
  }

  /**
   * Runs API where:
   * Entity name is 'CustomGroupContact'
   * Action name is 'get_groups_contacts'
   *
   * @return array
   */
  public function run() {
    foreach ($this->groupIdList as $groupId) {
      $this->generateContactData($groupId);
    }

    $this->response['values'] = $this->contactDataList;

    return $this->response;
  }

  /**
   * Gets array of validated group id
   *
   * @param $groupIdData
   *
   * @return array
   */
  private function getValidateGroupIdList($groupIdData) {
    $groupIdList = [];

    if (!empty($groupIdData) && is_array($groupIdData)) {
      foreach ($groupIdData as $groupId) {
        if (!empty($groupId)) {
          $groupIdList[] = (int) $groupId;
        }
      }
    }

    return $groupIdList;
  }

  /**
   * Generates contact data and sets to 'contactDataList'
   *
   * @param $groupId
   */
  private function generateContactData($groupId) {
    try {
      $groupContact = civicrm_api3('GroupContact', 'get', [
        'sequential' => 1,
        'group_id' => $groupId,
        'options' => ['limit' => 0],
        'api.Contact.getSingle' => [
          'id' => '$value.contact_id',
          'return' => ['display_name', 'email'],
        ]
      ]);
    } catch (CiviCRM_API3_Exception $e) {
      return;
    }

    foreach ($groupContact['values'] as $item) {
      $this->contactDataList[$item['contact_id']] = [
        'contact_id' => $item['contact_id'],
        'display_name' => $item['api.Contact.getSingle']['display_name'],
        'email' => $item['api.Contact.getSingle']['email']
      ];
    }
  }

}
