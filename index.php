<?php
/**
 * Created by PhpStorm.
 * User: johangriesel
 * Date: 13052016
 * Time: 08:48
 * @package    ${NAMESPACE}
 * @subpackage ${NAME}
 * @author     johangriesel <info@stratusolve.com>
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Basic Task Manager</title>
        <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
    </head>
    <body>

        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close refresh-modal" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                    </div>
                    <div class="modal-body">
                        <form id="formId" action="update_task.php" method="post">
                            <div class="msg"></div>
                            <div id="info" class="row">
                                <div id="name-group" class="col-md-12" style="margin-bottom: 5px;;">
                                    <input id="InputTaskName" type="text" name="task_name" placeholder="Task Name" class="form-control">
                                </div>
                                <div id="desc-group" class="col-md-12">
                                    <textarea id="InputTaskDescription" name="task_desc" placeholder="Description" class="form-control"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default refresh-modal" data-dismiss="modal">Close</button>
                        <button id="deleteTask" type="button" class="btn btn-danger">Delete Task</button>
                        <button id="saveTask" type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">

                </div>
                <div class="col-md-6">
                    <h2 class="page-header">Task List</h2>
                    <!-- Button trigger modal -->
                    <button id="newTask" type="button" class="btn btn-primary btn-lg" style="width:100%;margin-bottom: 5px;" data-toggle="modal" data-target="#myModal">
                        Add Task
                    </button>
                    <div class="delete-msg"></div>
                    <div id="TaskList" class="list-group">
                        <!-- Assignment: These are simply dummy tasks to show how it should look and work. You need to dynamically update this list with actual tasks -->
                    </div>
                </div>
                <div class="col-md-3">

                </div>
            </div>
        </div>
    </body>
    <script type="text/javascript" src="assets/js/jquery-1.12.3.min.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var currentTaskId = -1;
            $('#myModal').on('show.bs.modal', function (event) {
                var triggerElement = $(event.relatedTarget); // Element that triggered the modal
                var modal = $(this);
                if (triggerElement.attr("id") == 'newTask') {
                    modal.find('.modal-title').text('New Task');
                    $('#deleteTask').hide();
                    currentTaskId = -1;
                } else {
                    modal.find('.modal-title').text('Task details');
                    $('#deleteTask').show();
                    currentTaskId = triggerElement.attr("id");
                    console.log('Task ID: ' + triggerElement.attr("id"));
                    TaskDetails(currentTaskId);
                }
            });
            $(document).on('click','#saveTask',function () {
                //Assignment: Implement this functionality
                //
                //$('#myModal').modal('hide');
                if($('.modal-title').text() === 'New Task'){
                      saveTaskList();
                }else{
                     //alert('Save... Id:' + currentTaskId); 
                     saveTaskList();
                }          
            });
            $('#deleteTask').click(function () {
                //Assignment: Implement this functionality
                alert('Delete... Id:' + currentTaskId);
                $('#myModal').modal('hide');
                $.get("list_tasks.php?data=delete&id="+currentTaskId, function (data) {
                $(".delete-msg").html('<div class="alert alert-danger">' + data+ '</div>');
                setTimeout(function(){location.reload(); }, 3000);
            });
               
            });
            
            $('.refresh-modal').click(function () {
                location.reload();
            });
            
            $(window).load(function () {
                displayTaskList();
            });
        });        
        function displayTaskList() {
            $.post("list_tasks.php", function (data) {
                $("#TaskList").html(data);
            });
        }       
        function TaskDetails(currentTaskId) {
            $.get("list_tasks.php?data=update&id="+currentTaskId, function (data) {
                $("#info").html(data);
            });
        }
        function updateTaskList() {
            $.post("list_tasks.php", function (data) {
                $("#TaskList").html(data);
            });
        }
        function saveTaskList() {
                var formData = $("#formId").serialize();
                var url = $("#formId").attr('action');
                // process the form
                $.ajax({
                    type: "POST", // define the type of HTTP verb we want to use (POST for our form)
                    url: url, // the url where we want to POST                   
                    data: formData, // our data object
                    dataType: 'json', // what type of data do we expect back from the server
                    encode: true,
                    beforeSend: function () {
                        $('#saveTask').text("saving....");
                    },
                    success: function (data) {
                        if (!data.success) {
                            // handle errors for taskname
                            if (data.errors.name) {
                                $('#name-group').addClass('has-error'); // add the error class to show red input
                                $('#name-group').append('<div class="help-block">' + data.errors.name + '</div>'); // add the actual error message under our input
                            }
                            // handle errors for taskdesc
                            if (data.errors.desc) {
                                $('#desc-group').addClass('has-error'); // add the error class to show red input
                                $('#desc-group').append('<div class="help-block">' + data.errors.desc + '</div>'); // add the actual error message under our input
                            }
                        } else {
                            $('#saveTask').text("Save changes");
                            // ALL GOOD! just show the success message!
                            $('.msg').html('<div class="alert alert-success">' + data.message + '</div>');                             
                             
                            setTimeout(function(){location.reload(); }, 3000);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert(errorThrown);
                    }
                });
        }

    </script>
</html>