<?php
/**
 * Represents a single user notification object.
 * 
 * @package membernotification
 */
class MemberNotification extends DataObject implements PermissionProvider {

	private static $db = array(
		'MemberNotificationTitle' => 'Varchar',
		'MemberNotificationMessage' => 'Varchar(200)',
		'CreatedByMemberID' => 'Int',
	);
	
	private static $many_many = array(
		'NotificationMembers' => 'Member'
	);
	
	private static $many_many_extraFields = array(
		'NotificationMembers' => array(
			'ReadStatus' => 'Boolean'
		)
	);
	
	private static $summary_fields = array(
		'MemberNotificationTitle', 
		'MemberNotificationMessage',
	);
	
	private static $searchable_fields = array(
		'MemberNotificationTitle',
		'MemberNotificationMessage',
	);
	
	public function getTitle(){
		return $this->MemberNotificationTitle;
	}
	/*
	 * Modify the default fields shown to the user
	 */
	public function getCMSFields() {
		$fields = parent::getCMSFields();  
		$fields->removeByName('NotificationMembers');
		$fields->removeByName('CreatedByMemberID');
		
		$fields->addFieldToTab('Root.Main', new TextField('MemberNotificationTitle', 'User notification title'));		
		$fields->addFieldToTab('Root.Main', new TextareaField('MemberNotificationMessage', 'User notification message (200) characters only'));	
		
		// members - many_many relation
		$membersMap = Member::get()
			->sort('FirstName')
			->map('ID', 'FirstName')
			->toArray();
		$membersField = new ListboxField('NotificationMembers', 'Select users');
		$membersField->setMultiple(true)->setSource($membersMap);
		$fields->addFieldToTab('Root.Main', $membersField);
		
		return $fields;
	}
	
	function onBeforeWrite() {
		$this->CreatedByMemberID = Member::currentUser()->ID;
		parent::onBeforeWrite();
	}
	
	function canView($member = false) {
		return Permission::check('MEMBERNOTIFICATION_VIEW');
	}
	
	function canEdit($member = false) {
		return Permission::check('MEMBERNOTIFICATION_EDIT');
	}
	
	function canDelete() {
		return Permission::check('MEMBERNOTIFICATION_DELETE');
	}
	
	function canCreate() {
		return Permission::check('MEMBERNOTIFICATION_CREATE');
	}
	
	function providePermissions() {
	   return array(
		 'MEMBERNOTIFICATION_VIEW' => 'Read member notification object',
		 'MEMBERNOTIFICATION_EDIT' => 'Edit member notification object',
		 'MEMBERNOTIFICATION_DELETE' => 'Delete member notification object',
		 'MEMBERNOTIFICATION_CREATE' => 'Create member notification object',
	   );
	}
	
}
