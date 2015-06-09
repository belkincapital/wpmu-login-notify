<?php
/**
 * Plugin Name: WPMU Login Notification
 * Description: Send an email to the site admin when someone successfully logs into their sites wp-admin. Optional, uncomment line 31 to use site email as sender email on non WPMU sites, otherwise the default WP email is used (ie: wordpress@mysite.com). On multisite, the Network Admin email is used as the sender email. You can change it via wp-admin/network/settings.php.
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

    if (function_exists('is_multisite') && is_multisite()) { 
        $admin_name = get_site_option( 'name' );
        $admin_email = get_site_option( 'admin_email' );
        $headers = "From: $admin_name <$admin_email>";
    } else {
        $admin_email = get_option('admin_email');
        //$headers = "From: Admin <$admin_email>"; 
    }
    
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
