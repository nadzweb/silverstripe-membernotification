<?php

class MemberNotificationAdmin extends ModelAdmin {

    private static $managed_models = array(
        'MemberNotification',
    );

    private static $url_segment = 'member-notification';

    private static $menu_title = 'Member Notification';
	
	private static $menu_icon = 'framework/admin/images/menu-icons/16x16/blog.png';
}