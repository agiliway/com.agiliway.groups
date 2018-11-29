<?php

/**
 * Gets contacts by 'contacts group' ids
 *
 * @param array $params
 *
 * @return array
 * @throws \API_Exception
 */
function civicrm_api3_custom_group_contact_get_groups_contacts($params) {
  if (!CRM_Core_Permission::check('access CiviCRM')) {
    throw new API_Exception('Not enough permissions','1');
  }

  $groupsContacts = new CRM_Groups_API_CustomGroupsContacts($params);
  return $groupsContacts->run();
}

/**
 * Adjust Metadata for 'get_groups_contacts' action
 *
 * @param array $params
 */
function _civicrm_api3_custom_group_contact_get_groups_contacts_spec(&$params) {
  $params['group_id'] = [
    'title' => 'Group ID',
    'description' => ts('Group ID'),
    'api.required' => 1,
    'type' => CRM_Utils_Type::T_INT,
  ];
}
