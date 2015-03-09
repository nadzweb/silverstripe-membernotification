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
		
		if($request->isGET()){
			return $this->getNotifications($request);
		}
		
		if($request->isPOST()){
			return $this->postNotifications($request);
		}
		
    }
	
	private function getNotifications($request){
		$currentUserID = Member::currentUser()->ID;
		$notifications = MemberNotification::get()
			->innerJoin(
				'MemberNotification_NotificationMembers',
				'"MemberNotification"."ID"="MemberNotification_NotificationMembers"."MemberNotificationID"')
			->where("\"MemberNotification_NotificationMembers\".\"MemberID\" = $currentUserID")
			->sort('LastEdited', 'DESC');
		

		$notificationArray = array ();
		if($notifications) {
			foreach($notifications as $notification) {
				$notificationReadStatus = DB::query("Select ReadStatus FROM \"MemberNotification_NotificationMembers\"
					WHERE \"MemberNotificationID\" = $notification->ID AND \"MemberID\" = $currentUserID LIMIT 1")->value();

				$date = new Date();
				$date->setValue($notification->LastEdited);
				$user = Member::get()->byID($notification->CreatedByMemberID);
				$notificationArray[] = array(
					"id" => $notification->ID,
					"title" => $notification->MemberNotificationTitle,
					"message" => $notification->MemberNotificationMessage,
					"date" => $date->Format('j M Y'),
					"user" => (($user) ? $user->FirstName . ' ' . $user->SurName : ''),
					"read" => $notificationReadStatus,
				);
			}		
		}
		
		return $this->jsonPacket($notificationArray);
	}
	
	
	private function postNotifications($request){
		$currentUserID = Member::currentUser()->ID;
		$ids = $request->postVars('commentIds');
		$commentIds = $ids['commentIds'];
		if( $commentIds ){
			DB::query("UPDATE \"MemberNotification_NotificationMembers\" SET \"ReadStatus\" = '1' 
				WHERE \"MemberID\" = $currentUserID AND \"MemberNotificationID\" IN (".Convert::raw2sql($commentIds).")") ;
			return $this->jsonPacket(array('success' => true));
			
		}
	}
	
	private function jsonPacket($data){
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