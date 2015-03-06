<?php
/**
 * @package membernotification
 */
class MemberNotificationLeftMainExtension extends Extension {

	function init() {
		$req = $this->owner->getRequest();

		Requirements::javascript('membernotification/javascript/notification.js');
		Requirements::css('membernotification/css/notification.css');
	}
	
}

class MemberNotificationExtension extends DataExtension {
	private static $belongs_many_many = array(
		'MemberNotifications' => 'MemberNotification'
	);
}