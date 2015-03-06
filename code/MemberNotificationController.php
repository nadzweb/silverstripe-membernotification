<?php
/**
 * @package membernotification
 */
class MemberNotificationController extends Controller {

	private static $allowed_actions = array(
		'notifications',
	);

	public function init() {
		parent::init();
		if(!Member::currentUser()) {
			return $this->httpError(403);
		}
	}
	
	public function index(SS_HTTPRequest $request) {
        $currentUserID = Member::currentUser()->ID;
		$notifications = MemberNotification::get()
            ->innerJoin(
                'MemberNotification_NotificationMembers',
                '"MemberNotification"."ID"="MemberNotification_NotificationMembers"."MemberNotificationID"')
			->where("\"MemberNotification_NotificationMembers\".\"MemberID\" = $currentUserID");
			
		$notificationArray = array ();
		if($notifications) {
			foreach($notifications as $notification) {
				$date = new Date();
				$date->setValue($notification->LastEdited);
				$user = Member::get()->byID($notification->CreatedByMemberID);
				$notificationArray[] = array(
					"title" => $notification->MemberNotificationTitle,
					"message" => $notification->MemberNotificationMessage,
					"date" => $date->Format('j M Y'),
					"user" => (($user) ? $user->FirstName . ' ' . $user->SurName : ''),
				);
			}		
		}
		
		return $this->jsonPacket($notificationArray);
    }
	
	public function jsonPacket($data){
		$this->response->addHeader('Content-Type', 'application/json');
	    SSViewer::set_source_file_comments(false);
		$json = json_encode($data);
	    $json = str_replace('\t', " ", $json);
	    $json = str_replace('\r', " ", $json);
		$json = str_replace('\n', " ", $json);
		$json = preg_replace('/\s\s+/', ' ', $json);
		return $json;
		
	}
	
	
	
}