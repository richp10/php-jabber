<?php
// By phillipj - https://www.ejabberd.im/node/3126
// set your Jabber server hostname, username, and password here
define('JABBER_SERVER', 'mycoolserver');
define('JABBER_USERNAME', 'cooladmin');
define('JABBER_PASSWORD', 'coolpassword');

define('RUN_TIME', 5);    // set a maximum run time of 5 seconds
define('CBK_FREQ', 1);    // fire a callback event every second

// Including original "Jabber Client Library" - class
require_once('class_Jabber.php');

// create an instance of the Jabber class
$display_debug_info = false;
$AddUserErrorCode = 12000;
$UserLogin = 'test100';
$UserPass = 'test100';
$FirstName = 'Bob';
$LastName = 'Bobbles.';
$Patronymic = 'Ivanovich :)';
$sex = 'Male';
$role = 'Participant';

$jab = new Jabber($display_debug_info);
$addmsg = new AddMessenger($jab, $UserLogin, $UserPass);

// set handlers for the events we wish to be notified about
$jab->set_handler("connected", $addmsg, "handleConnected");
$jab->set_handler("authenticated", $addmsg, "handleAuthenticated");
//$jab->set_handler("error",$addmsg,"handleError");

// connect to the Jabber server
if ($jab->connect(JABBER_SERVER)) {
    $AddUserErrorCode = 12001;
    $jab->execute(CBK_FREQ, RUN_TIME);
}

$jab->disconnect();

unset($jab, $addmsg);

echo '<P>******** Exit of User Creation! ErrorCode=' . $AddUserErrorCode . ' ********</P>';

// If AddUserErrorCode is 0, we can try to fill user's Vcard, using brand new credentials :)

$AddVcardErrorCode = 14000;
$jab = new Jabber($display_debug_info);
$avcard = new AddVcard($jab, $UserLogin, $UserPass, $FirstName, $LastName, $Patronymic, $sex, $role);

$jab->set_handler("connected", $avcard, "handleConnected");
$jab->set_handler("authenticated", $avcard, "handleAuthenticated");

if ($jab->connect(JABBER_SERVER)) {
    $AddVcardErrorCode = 14001;
    $jab->execute(CBK_FREQ, RUN_TIME);
}

$jab->disconnect();

unset($jab, $avcard);

echo '<P>******** Exit of Add Vcard! ErrorCode=' . $AddVcardErrorCode . ' ********</P>';
?>