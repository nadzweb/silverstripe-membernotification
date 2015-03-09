# Member notification module #
The membernotification module allows CMS Administrators to add short messages(notifications) for content authors. The content authors can login to SilverStripe CMS and then view the messages by clicking on the comment icon located on top of page.


## Installation ##
* Download the module from here https://github.com/nadzweb/silverstripe-membernotification/archive/master.zip
* Extract the downloaded archive into your site root and rename the module to 'membernotification'.
* Run dev/build?flush=all to regenerate the manifest
* Optionally you can install it using [https://packagist.org/packages/nadzweb/membernotification](composer)

## Usage
* An administrator can login to CMS and add notifications from "Member Notification" tab on left menu
* Each notification has a title, short message(200 characters) and can be assigned to multiple members(users).
* Notifications have permissions applied, by default only Administrators will be able to add notifications
* A Content Author will receive the notification, the commment icon will show the unread notifications(red active icon) as seen in image screenshot below.
* Clicking on the notification icon will disable the red icon unless a new notification is received.

* Sample screenshots of notification page
![Alt text](https://raw.githubusercontent.com/nadzweb/silverstripe-membernotification/master/docs/screengrab01.jpg "Member Notification")