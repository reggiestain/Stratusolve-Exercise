<?php

/**
 * This script is to be used to receive a POST with the object information and then either updates, creates or deletes the task object
 */
require('Task.class.php');
// Assignment: Implement this script

$errors = array();      // array to hold validation errors
$data = array();      // array to pass back data
// validate the variables ======================================================
// if any of these variables don't exist, add an error to our $errors array

if (empty($_POST['task_name']))
    $errors['name'] = 'Task Name is required.';

if (empty($_POST['task_desc']))
    $errors['desc'] = 'Task Descripyion is required.';

// return a response ===========================================================
// if there are any errors in our errors array, return a success boolean of false
if (!empty($errors)) {

    // if there are items in our errors array, return those errors
    $data['success'] = false;
    $data['errors'] = $errors;
} else {
    $taskObj = new Task();
    $msg  = $taskObj->Save($_POST);
    // show a message of success and provide a true success variable
    $data['success'] = true;
    $data['message'] = 'Success! '.$msg;
}
// return all our data to an AJAX call
echo json_encode($data);
?>