<?php
/*
Plugin Name: Private Folder Generator
Description: Automatically creates a user-specific folder upon registration.
Version: 1.0
Author: Antonio Guiotto
*/

// Function to create user-specific folder upon registration
function create_user_folder_on_registration($user_id) {
    // Get user data
    $user_info = get_userdata($user_id);
    $first_name = sanitize_file_name($user_info->first_name); // Sanitize first name
    $last_name = sanitize_file_name($user_info->last_name);   // Sanitize last name
    $fiscal_code = sanitize_file_name(get_user_meta($user_id, 'fiscal_code', true)); // Get and sanitize fiscal code

    // Construct folder name: first name + last name + fiscal code
    $folder_name = strtolower($first_name . '_' . $last_name . '_' . $fiscal_code);

    // Define the path for the user's folder
    $upload_dir = wp_upload_dir(); // Get the upload directory
    $user_folder_path = $upload_dir['basedir'] . '/user-documents/' . $folder_name;

    // Create the directory if it doesn't exist
    if (!file_exists($user_folder_path)) {
        mkdir($user_folder_path, 0755, true); // Create the folder with appropriate permissions
    }

    // Optionally store the folder path in user meta for easy reference
    update_user_meta($user_id, 'user_folder_path', $user_folder_path);
}

// Hook into Ultimate Member's registration completion
add_action('um_registration_complete', 'create_user_folder_on_registration', 10, 1);
