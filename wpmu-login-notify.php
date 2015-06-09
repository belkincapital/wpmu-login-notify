<?php
/**
 * Plugin Name: WPMU Login Notification
 * Description: Send an email to the site admin when someone successfully logs into their sites wp-admin. Optional, Edit line 23 to specify a sender email.
 * Author: Jason Jersey
 * Version: 1.0
 */


/**
 * Send email on successful login
 */
function send_email_on_login($username) {
    
    date_default_timezone_set('America/New_York');
    $ip   = $_SERVER['REMOTE_ADDR'];
    $ua   = $_SERVER['HTTP_USER_AGENT'];
    $name = get_bloginfo( 'name' );
    $url  = get_bloginfo( 'wpurl' );
    $date = date('l jS \of F Y h:i:s A');    
    $subject = "Login Alert for $name";
    $user = get_user_by( 'login', $username );
    $to   = $user->user_email;
    //$headers = "From: Admin <no-reply@mychildsvillage.com>";
    
    /* The unique token can be inserted in the message with %s */
    $message = "Your account was recently logged into from a device. Was this you?

Site URL:
$url

Date/Time:
$date (Eastern)

Username:
$username

IP Address:
$ip

User Agent:
$ua

If this was not you, please login to your account now and change your user password.
$url/wp-admin/profile.php
";

    wp_mail($to, $subject, $message, $headers);

}

add_action('wp_login', 'send_email_on_login');
