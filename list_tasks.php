<?php

/**
 * Created by PhpStorm.
 * User: johangriesel
 * Date: 15122016
 * Time: 15:14
 * @package    ${NAMESPACE}
 * @subpackage ${NAME}
 * @author     johangriesel <info@stratusolve.com>
 * Task_Data.txt is expected to be a json encoded string, e.g: [{"TaskId":1,"TaskName":"Test","TaskDescription":"Test"},{"TaskId":"2","TaskName":"Test2","TaskDescription":"Test2"}]
 */

require('Task.class.php');

$taskData = file_get_contents('Task_Data.txt');
$taskArray = json_decode($taskData);

if (isset($_GET['id'])) {    
    $taskObj = new Task();
    if($_GET['data'] === 'update'){    
        $html  = $taskObj->TaskDetail($_GET['id']);    
    }
    
    if($_GET['data'] === 'delete'){    
        $html  = $taskObj->Delete($_GET['id']);    
    }
    die($html);
} else {

$html = '<a id="newTask" href="#" class="list-group-item" data-toggle="modal" data-target="#myModal">
                    <h4 class="list-group-item-heading">No Tasks Available</h4>
                    <p class="list-group-item-text">Click here to create one</p>
                </a>';
if (strlen($taskData) < 1) {
    die($html);
}

if (sizeof($taskArray) > 0) {
    $html = '';
    foreach ($taskArray as $task) {
        $html .= '<a id="' . $task->TaskId . '" href="#" class="list-group-item task-list" data-toggle="modal" data-target="#myModal">
                    <h4 class="list-group-item-heading">' . $task->TaskName . '</h4>
                    <p class="list-group-item-text">' . $task->TaskDescription . '</p>
                </a>';
    }
}
die($html);
}

?>