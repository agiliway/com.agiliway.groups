<?php

class CRM_Groups_Form_MultiEventRegistration extends CRM_Event_Form_Participant {

  public function preProcess() {
    parent::preProcess();
    CRM_Utils_System::setTitle(ts('Group Event Registration'));
  }

  public function buildQuickForm() {
    parent::buildQuickForm();

    if ($this->_showFeeBlock) {
      $this->assign('accessContribution', CRM_Core_Permission::access('CiviContribute'));
    } else {
      $this->changeSelectToMultiple();
      $this->addGroupSelect();
      $this->assign('urlPath', 'civicrm/groups/multi-event-registration');
    }
  }

  /**
   * Changes 'contact_id' select to multiple
   */
  private function changeSelectToMultiple() {
    if ($this->elementExists('contact_id')) {
      $element = $this->getElement('contact_id');
      $element->setAttribute('data-select-params', '{"multiple":true}');
    }
  }

  /**
   * Adds 'group' select to form
   */
  private function addGroupSelect() {
    $groupOptionList = CRM_Groups_Common_Group::retrieveGroupList();
    $groupInputName = 'contact_group';
    $placeholder = (empty($groupOptionList)) ? ts('Have not exist available groups') : ts('- select -');

    $this->add('select', $groupInputName , ts('Groups'), $groupOptionList, FALSE, [
      'placeholder' => $placeholder,
      'class' => 'crm-select2',
      'multiple' => 'multiple'
    ]);

    $this->assign('groupInputName', $groupInputName);
    $this->assign('groupSelector', '#' . $groupInputName);
    $this->assign('insertAfterSelector', '.crm-participant-form-contact-id:first');
    $this->assign('targetInputSelector', '#contact_id');

  }

  public function postProcess() {
    if ($this->_action != CRM_Core_Action::ADD) {
      return;
    }

    $params = $this->controller->exportValues($this->_name);
    $contactIdList = $this->getContactIdList();

    foreach ($contactIdList as $contactId) {
      $this->processForm($contactId, $params);
    }
  }

  /**
   * Gets list of contact id from form
   *
   * @return array
   */
  private function getContactIdList() {
    $values = $this->controller->exportValues();
    $contactIdList = [];

    if ($values['contact_id']) {
      $contactIdDataString = $values['contact_id'];
      $contactIdList = explode(',', $contactIdDataString);
    }

    return $contactIdList;
  }

  /**
   * Process for single contact
   * Resets form data for previous contacts
   *
   * @param $contactId
   * @param $params
   */
  private function processForm($contactId, $params) {
    //set single contact to params
    $params['contact_id'] = $contactId;

    //reset variables
    $this->setVar('_id', NULL);
    $this->setVar('_contactId', NULL);
    $this->setVar('_contactID', NULL);
    $this->setVar('_contactIds', NULL);

    //set single contact to submitValues
    $submitValues = $this->getVar('_submitValues');
    $submitValues['contact_id'] = $contactId;
    $this->setVar('_submitValues', $submitValues);

    //set single contact to POST
    $_POST['contact_id'] = $contactId;

    //set single contact to 'contact_id' select
    $form = $this->controller->_pages['MultiEventRegistration'];
    if ($form->elementExists('contact_id')) {
      $element = $form->getElement('contact_id');
      $element->setValue($contactId);
    }

    $this->fixDuplicateContact();

    $statusMsg = $this->submit($params);
    CRM_Core_Session::setStatus($statusMsg, ts('Saved'), 'success');
  }

  /**
   * Fixes contact duplicate
   */
  private function fixDuplicateContact() {
    $eventId = $this->_eventId;

    if (empty($eventId) && !empty($params['event_id'])) {
      $eventId = $params['event_id'];
    }

    if ($this->_single || empty($eventId)) {
      return;
    }

    $duplicateContacts = 0;
    foreach ($this->_contactIds as $k => $dupeCheckContactId) {
      $eventParticipant = new CRM_Event_BAO_Participant();
      $eventParticipant->contact_id = $dupeCheckContactId;
      $eventParticipant->event_id = $eventId;
      $eventParticipant->find(TRUE);

      if (!empty($eventParticipant->id)) {
        $duplicateContacts++;
        unset($this->_contactIds[$k]);
      }
    }

    if ($duplicateContacts > 0) {
      $message = ts("%1 contacts have already been assigned to this event. They were not added a second time.",
        [1 => $duplicateContacts]
      );
      CRM_Core_Session::setStatus($message);
    }

    if (count($this->_contactIds) == 0) {
      CRM_Core_Session::setStatus(ts("No participants were added."));
    } else {
      $this->_contactIds = array_values($this->_contactIds);
    }
  }

}
