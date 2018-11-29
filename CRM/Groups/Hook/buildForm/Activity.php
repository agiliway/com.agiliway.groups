<?php

class CRM_Groups_Hook_buildForm_Activity {

  /**
   * Form object
   *
   * @var object
   */
  private $formObject;

  public function __construct(&$formObject) {
    $this->formObject = $formObject;
  }

  /**
   * Main boot point of build form hook
   */
  public function run() {
    $this->addGroupSelect();
  }

  /**
   * Adds group select to form
   */
  public function addGroupSelect() {
    if ($this->formObject->getAction() == CRM_Core_Action::ADD) {
      $groupOptionList = CRM_Groups_Common_Group::retrieveGroupList();
      $groupInputName = 'contact_group';
      $placeholder = (empty($groupOptionList)) ? ts('Have not exist available groups') : ts('- select -');

      $this->formObject->add('select', $groupInputName , ts('Groups'), $groupOptionList, FALSE, [
        'placeholder' => $placeholder,
        'class' => 'crm-select2',
        'multiple' => 'multiple'
      ]);

      $this->formObject->assign('groupInputName', $groupInputName);
      $this->formObject->assign('groupSelector', '#' . $groupInputName);
      $this->formObject->assign('insertAfterSelector', '.crm-activity-form-block-target_contact_id:first');
      $this->formObject->assign('targetInputSelector', '#target_contact_id');

      CRM_Core_Region::instance('page-body')->add([
        'template' => CRM_Groups_ExtensionUtil::path() . '/templates/CRM/Groups/Form/Field/Group.tpl'
      ]);
    }
  }

}
