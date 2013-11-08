<?php defined('BASEPATH') OR exit('No direct script access allowed');

$lang['client_register_header']                  = 'Registration';
$lang['client_register_step1']                   = '<strong>Step 1:</strong> Register';
$lang['client_register_step2']                   = '<strong>Step 2:</strong> Activate';

$lang['client_login_header']                     = 'Login';

// titles
$lang['client_add_title']                        = 'Add Client';
$lang['client_list_title'] 					   = 'Clients';
$lang['client_inactive_title']                   = 'Inactive Clients';
$lang['client_active_title']                     = 'Active Clients';
$lang['client_registred_title']                  = 'Registered Clients';

// labels
$lang['client_edit_title']                       = 'Edit Client "%s"';
$lang['client_details_label']                    = 'Details';
$lang['client_first_name_label']                 = 'First Name';
$lang['client_last_name_label']                  = 'Last Name';
$lang['client_email_label']                      = 'E-mail';
$lang['client_group_label']                      = 'Group';
$lang['client_activate_label']                   = 'Activate';
$lang['client_password_label']                   = 'Password';
$lang['client_password_confirm_label']           = 'Confirm Password';
$lang['client_name_label']                       = 'Name';
$lang['client_joined_label']                     = 'Joined';
$lang['client_last_visit_label']                 = 'Last visit';
$lang['client_never_label']                      = 'Never';

$lang['client_no_inactives']                     = 'There are no inactive clients.';
$lang['client_no_registred']                     = 'There are no registered clients.';

$lang['account_changes_saved']                 = 'The changes to your account have been saved successfully.';

$lang['indicates_required']                    = 'Indicates required fields';

// -- Registration / Activation / Reset Password ----------------------------------------------------------

$lang['client_register_title']                   = 'Register';
$lang['client_activate_account_title']           = 'Activate Account';
$lang['client_activate_label']                   = 'Activate';
$lang['client_activated_account_title']          = 'Activated Account';
$lang['client_reset_password_title']             = 'Reset Password';
$lang['client_password_reset_title']             = 'Password Reset';


$lang['client_error_clientname']                   = 'The clientname you selected is already in use';
$lang['client_error_email']                      = 'The email address you entered is already in use';

$lang['client_full_name']                        = 'Full Name';
$lang['client_first_name']                       = 'First Name';
$lang['client_last_name']                        = 'Last Name';
$lang['client_clientname']                         = 'Clientname';
$lang['client_display_name']                     = 'Display Name';
$lang['client_email_use'] 					   = 'used to login';
$lang['client_email']                            = 'E-mail';
$lang['client_confirm_email']                    = 'Confirm E-mail';
$lang['client_password']                         = 'Password';
$lang['client_remember']                         = 'Remember Me';
$lang['client_group_id_label']                   = 'Group ID';

$lang['client_level']                            = 'Client Role';
$lang['client_active']                           = 'Active';
$lang['client_lang']                             = 'Language';

$lang['client_activation_code']                  = 'Activation code';

$lang['client_reset_instructions']			   = 'Enter your email address or clientname';
$lang['client_reset_password_link']              = 'Forgot your password?';

$lang['client_activation_code_sent_notice']      = 'An email has been sent to you with your activation code.';
$lang['client_activation_by_admin_notice']       = 'Your registration is awaiting approval by an administrator.';

// -- Settings ---------------------------------------------------------------------------------------------

$lang['client_details_section']                  = 'Name';
$lang['client_password_section']                 = 'Change password';
$lang['client_other_settings_section']           = 'Other settings';

$lang['client_settings_saved_success']           = 'The settings for your client account have been saved.';
$lang['client_settings_saved_error']             = 'An error occurred.';

// -- Buttons ----------------------------------------------------------------------------------------------

$lang['client_register_btn']                     = 'Register';
$lang['client_activate_btn']                     = 'Activate';
$lang['client_reset_pass_btn']                   = 'Reset Pass';
$lang['client_login_btn']                        = 'Login';
$lang['client_settings_btn']                     = 'Save settings';

// -- Errors & Messages ------------------------------------------------------------------------------------

// Create
$lang['client_added_and_activated_success']      = 'New client has been created and activated.';
$lang['client_added_not_activated_success']      = 'New client has been created, the account needs to be activated.';

// Edit
$lang['client_edit_client_not_found_error']        = 'Client not found.';
$lang['client_edit_success']                     = 'Client successfully updated.';
$lang['client_edit_error']                       = 'Error occurred when trying to update client.';

// Activate
$lang['client_activate_success']                 = '%s clients out of %s successfully activated.';
$lang['client_activate_error']                   = 'You need to select clients first.';

// Delete
$lang['client_delete_self_error']                = 'You cannot delete yourself!';
$lang['client_mass_delete_success']              = '%s clients out of %s successfully deleted.';
$lang['client_mass_delete_error']                = 'You need to select clients first.';

// Register
$lang['client_email_pass_missing']               = 'Email or password fields are not complete.';
$lang['client_email_exists']                     = 'The email address you have chosen is already in use with a different client.';
$lang['client_register_error']				   = 'We think you are a bot. If we are mistaken please accept our apologies.';
$lang['client_register_reasons']                 = 'Join up to access special areas normally restricted. This means your settings will be remembered, more content and less ads.';


// Activation
$lang['client_activation_incorrect']             = 'Activation failed. Please check your details and make sure CAPS LOCK is not on.';
$lang['client_activated_message']                = 'Your account has been activated, you can now log in to your account.';


// Login
$lang['client_logged_in']                        = 'You have logged in successfully.'; # TODO: Translate this in spanish
$lang['client_already_logged_in']                = 'You are already logged in. Please logout before trying again.';
$lang['client_login_incorrect']                  = 'E-mail or password do not match. Please check your login and make sure CAPS LOCK is not on.';
$lang['client_inactive']                         = 'The account you are trying to access is currently inactive.<br />Check your e-mail for instructions on how to activate your account - <em>it may be in the junk folder</em>.';


// Logged Out
$lang['client_logged_out']                       = 'You have been logged out.';

// Forgot Pass
$lang['client_forgot_incorrect']                 = "No account was found with these details.";

$lang['client_password_reset_message']           = "Your password has been reset. You should recieve the email within the next 2 hours. If you don't, it might have gone into your junk mail by accident.";

// Emails ----------------------------------------------------------------------------------------------------

// Activation
$lang['client_activation_email_subject']         = 'Activation Required';
$lang['client_activation_email_body']            = 'Thank you for activting your account with %s. To log in to the site, please visit the link below:';


$lang['client_activated_email_subject']          = 'Activation Complete';
$lang['client_activated_email_content_line1']    = 'Thank you for registering at %s. Before we can activate your account, please complete the registration process by clicking on the following link:';
$lang['client_activated_email_content_line2']    = 'In case your email program does not recognize the above link as, please direct your browser to the following URL and enter the activation code:';

// Reset Pass
$lang['client_reset_pass_email_subject']         = 'Password Reset';
$lang['client_reset_pass_email_body']            = 'Your password at %s has been reset. If you did not request this change, please email us at %s and we will resolve the situation.';

// Profile
$lang['profile_of_title']             = '%s\'s Profile';

$lang['profile_client_details_label']   = 'Client Details';
$lang['profile_role_label']           = 'Role';
$lang['profile_registred_on_label']   = 'Registered on';
$lang['profile_last_login_label']     = 'Last login';
$lang['profile_male_label']           = 'Male';
$lang['profile_female_label']         = 'Female';

$lang['profile_not_set_up']           = 'This client does not have a profile set up.';

$lang['profile_edit']                 = 'Edit your profile';

$lang['profile_personal_section']     = 'Personal';

$lang['profile_display_name']         = 'Display Name';
$lang['profile_dob']                  = 'Date of Birth';
$lang['profile_dob_day']              = 'Day';
$lang['profile_dob_month']            = 'Month';
$lang['profile_dob_year']             = 'Year';
$lang['profile_gender']               = 'Gender';
$lang['profile_gender_nt']            = 'Not Telling';
$lang['profile_gender_male']          = 'Male';
$lang['profile_gender_female']        = 'Female';
$lang['profile_bio']                  = 'About me';

$lang['profile_contact_section']      = 'Contact';

$lang['profile_phone']                = 'Phone';
$lang['profile_mobile']               = 'Mobile';
$lang['profile_address']              = 'Address';
$lang['profile_address_line1']        = 'Line #1';
$lang['profile_address_line2']        = 'Line #2';
$lang['profile_address_line3']        = 'Line #3';
$lang['profile_address_postcode']     = 'Post/Zip Code';
$lang['profile_website']              = 'Website';

$lang['profile_avatar_section']       = 'Avatar';

$lang['profile_edit_success']         = 'Your profile has been saved.';
$lang['profile_edit_error']           = 'An error occurred.';

// -- Buttons ------------------------------------------------------------------------------------------------

$lang['profile_save_btn']             = 'Save profile';
/* End of file client_lang.php */