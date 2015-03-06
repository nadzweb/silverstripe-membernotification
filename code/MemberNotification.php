<?php
/**
 * Represents a single user notification object.
 * 
 * @package membernotification
 */
class MemberNotification extends DataObject {

	private static $db = array(
		'MemberNotificationTitle' => 'Varchar',
		'MemberNotificationMessage' => 'Varchar(100)',
		'CreatedByMemberID' => 'Int',
	);
	
	private static $many_many = array(
		'NotificationMembers' => 'Member'
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
		$fields->addFieldToTab('Root.Main', new TextareaField('MemberNotificationMessage', 'User notification message'));	
		
		// members - many_many relation
		$membersMap = Member::get()
			->sort('FirstName')
			->map('ID', 'FirstName')
			->toArray();
		$membersField = new ListboxField('NotificationMembers', 'Select members');
		$membersField->setMultiple(true)->setSource($membersMap);
		$fields->addFieldToTab('Root.Main', $membersField);
		
		return $fields;
	}
	
	function onBeforeWrite() {
		$this->CreatedByMemberID = Member::currentUser()->ID;
		parent::onBeforeWrite();
	}
	
	/** allow all users for crud operations **/
	public function canView($member = null) {
		return true;
	}
	public function canEdit($member = null) {
		return true;
	}
	public function canDelete($member = null) {
		return true;
	}
	public function canCreate($member = null) {
		return true;
	}
	
}
