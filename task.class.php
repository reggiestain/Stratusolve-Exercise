<?php

/**
 * This class handles the modification of a task object
 */
class Task {

    public $TaskId;
    public $TaskName;
    public $TaskDescription;
    protected $TaskDataSource;

    public function __construct($Id = null) {
        $this->TaskDataSource = file_get_contents('Task_Data.txt');
        if (strlen($this->TaskDataSource) > 0)
            $this->TaskDataSource = json_decode($this->TaskDataSource); // Should decode to an array of Task objects
        else
            $this->TaskDataSource = array(); // If it does not, then the data source is assumed to be empty and we create an empty array

        if (!$this->TaskDataSource)
            $this->TaskDataSource = array(); // If it does not, then the data source is assumed to be empty and we create an empty array
        if (!$this->LoadFromId($Id))
            $this->Create();
    }

    protected function Create() {
        // This function needs to generate a new unique ID for the task
        // Assignment: Generate unique id for the new task
        $this->TaskId = $this->getUniqueId();
        $this->TaskName = 'New Task';
        $this->TaskDescription = 'New Description';
    }

    protected function getUniqueId() {
        // Assignment: Code to get new unique ID
        $uniqueId = uniqid();
        return $uniqueId;
    }

    protected function LoadFromId($Id = null) {
        if ($Id) {
            // Assignment: Code to load details here...
            $taskArray = $this->TaskDataSource;           
            foreach ($taskArray as $task) {
            if($task->TaskId == $_GET['id']){
               $html ="<div id='name-group' class='col-md-12' style='margin-bottom: 5px;'>
                       <input type='hidden' name='task_id' value='$task->TaskId' class='form-control'>
                       <input id='InputTaskName' type='text' name='task_name' value='$task->TaskName' class='form-control'>
                       </div>
                       <div id='desc-group' class='col-md-12'>
                       <textarea id='InputTaskDescription' name='task_desc' class='form-control'>$task->TaskDescription</textarea>
                       </div>";
            }  
          } 
          
          return $html;
        }else 
            return null;
        
    }
    
    public function TaskDetail($Id) {
        $html = $this->LoadFromId($Id);
        
        return $html;
    }

    public function Save($request) {
        //Assignment: Code to save task here
        $tempArray = $this->TaskDataSource;
        $data = [];
        if (!isset($request['task_id'])) {
            $data = ['TaskId' => $this->getUniqueId(),
                'TaskName' => $request['task_name'],
                'TaskDescription' => $request['task_desc']
            ];
            array_push($tempArray, $data);
            
            $msg = 'New task created successfully';
        } else {
            foreach ($tempArray as $key=> $value) {
                if ($value->TaskId == $request['task_id']) {
                    $tempArray[$key] = ['TaskId' =>$value->TaskId ,
                                    'TaskName' => $request['task_name'],
                                    'TaskDescription' => $request['task_desc']
                                    ];                
                }
            }            
            $msg = 'Task with ID '.$request['task_id'].' was updated successfully.';
        }
        
        $jsonData = json_encode($tempArray);
        file_put_contents('Task_Data.txt', $jsonData);
        
        return $msg;
    }

    public function Delete($Id) {
        //Assignment: Code to delete task here
        $tempArray = $this->TaskDataSource;
        foreach ($tempArray as $key=> $value) {
                if ($value->TaskId == $Id) {
                    unset($tempArray[$key]);              
                }
            }  
            
            $jsonData = json_encode($tempArray);
            file_put_contents('Task_Data.txt', $jsonData);
       
            $msg = 'Task with ID '.$Id.' was deleted successfully.';
            
            return $msg;
    }

}

?>

