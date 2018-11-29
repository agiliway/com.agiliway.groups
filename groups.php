<?php

require_once 'groups.civix.php';
use CRM_Groups_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function groups_civicrm_config(&$config) {
  _groups_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function groups_civicrm_xmlMenu(&$files) {
  _groups_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function groups_civicrm_install() {
  _groups_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function groups_civicrm_postInstall() {
  _groups_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function groups_civicrm_uninstall() {
  _groups_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function groups_civicrm_enable() {
  _groups_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function groups_civicrm_disable() {
  _groups_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function groups_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _groups_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function groups_civicrm_managed(&$entities) {
  _groups_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function groups_civicrm_caseTypes(&$caseTypes) {
  _groups_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function groups_civicrm_angularModules(&$angularModules) {
  _groups_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function groups_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _groups_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_buildForm().
 *
 * @param $formName
 * @param $form
 */
function groups_civicrm_buildForm($formName, &$form) {
  if ($formName == 'CRM_Activity_Form_Activity') {
    $activity = new CRM_Groups_Hook_buildForm_Activity($form);
    $activity->addGroupSelect();
  }
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @param $menu
 */
function groups_civicrm_navigationMenu(&$menu) {
  $multiEventRegistrationMenuItem = [
      'name' => ts('Group Event Registration'),
      'url' => 'civicrm/groups/multi-event-registration?action=add&reset=1&context=standalone',
      'permission' => 'access CiviCRM',
      'operator' => NULL,
      'separator' => NULL,
    ];

  _groups_civix_insert_navigation_menu($menu, 'Events/', $multiEventRegistrationMenuItem);
}
