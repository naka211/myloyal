<?php


//
// Mailing list settings
// --------------------------------------------------------------------------------------------

// Set to true if you want to use Mailchimp to collect addresses. Otherwise, set to false.
$use_Mailchimp = false;

// Fill in you API Key here if the above option is true
$mailchimp_API_Key = 'abc123abc123abc123abc123abc123-us1';

// The ID of the mailchimp list where you want to save the contacts
$mailchimp_list_ID = 'b1234346';

// If set to true, it enables Double Opt-in. See the following link for reference on how it works: http://kb.mailchimp.com/article/how-does-confirmed-optin-or-double-optin-work/
$mailchimp_double_optin = false;

// If Double Opt-in is disabled, you can still send a Welcome message by setting the following to true
$mailchimp_send_welcome = false;

// -------------------------------------------------------------------------------------------
// The emails are saved to this file if not using Mailchimp. Use a random name that can't be easily guessed.
$maillist_file = 'mail-list_MyLoYaL.txt';



//
// Contact form settings
// -------------------------------------------------------------------------------------------

// This is the email address where you'll receive the contact form messages
$target_address = 'ulvi@azweb.dk';

// By default, the Contact form FROM email is the same as the $target_address. However, some hosting providers won't allow email being sent from an address that isn't configured on the host's Mail service.
// If you are not getting emails from the form try setting this to an address that is properly configured on your host.
$from_address = '';

// Prefix for the email subject. Useful for filtering mail.
$subject_prefix = 'MyLoyal besked fra - ';
?>