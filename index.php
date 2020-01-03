<?php 
include_once "config.php"; 
$connection = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
if(!$connection){
	throw new Exception("Not Connected");
}

$query ="SELECT * FROM task WHERE complete=0 ORDER BY date";
$result = mysqli_query($connection, $query);

$completeTaskQuery ="SELECT * FROM task WHERE complete=1 ORDER BY date";
$resultCompleteTasks = mysqli_query($connection, $completeTaskQuery);




?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,300italic,700,700italic">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.css">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/milligram/1.3.0/milligram.css">
	<title>Tasks Project</title>
	<style type="text/css">
		body{
			margin-top: 30px;
		}
		#main{
			padding: 0px 150px 0px 150px;
		}
		#action{
			width: 150px;
		}
	</style>
</head>
<body>
	<div class="container" id="main">
		<h1>Task Manager</h1>
		<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
		tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>

		
		<?php if(mysqli_num_rows($resultCompleteTasks)>0) { ?>
			<h4>Completed Tasks</h4>
			<table>
					<thead>
						<tr>
							<th></th>
							<th>Id</th>
							<th>Task</th>
							<th>Date</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
		<?php
			 while($cdata = mysqli_fetch_assoc($resultCompleteTasks)) {
		 	$timestamp = strtotime($cdata['date']);
			$cdate = date("jS M, Y",$timestamp);
		 ?>

		 	<tr>
				<td><input class="label-inline" type="checkbox"></td>
				<td><?php echo $cdata['id'] ?></td>
				<td><?php echo $cdata['task']; ?></td>
				<td><?php echo $cdate; ?></td>
				<td><a class="delete" data-taskid="<?php echo $cdata['id'] ?>" href="#"><b>Delete</b></a> | <a onclick="return confirm('Are you sure?')" class="incomplete" data-taskid="<?php echo $cdata['id'] ?>" href='#'><b>Mark Incomplete</b></a></td>
			</tr>
				<?php }
		 	?>
		 </tbody>
		</table>
		 <?php 
		}
		?>


		<?php 
		if(mysqli_num_rows($result)==0){ ?>
			<p>No task Found</p>
		<?php } else{ ?>
			<h4>Upcoming Tasks</h4>
			<form action="tasks.php" method="POST">
				<table>
					<thead>
						<tr>
							<th></th>
							<th>Id</th>
							<th>Task</th>
							<th>Date</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
					<?php while($data = mysqli_fetch_assoc($result)){ 
							$timestamp = strtotime($data['date']);
							$date = date("jS M, Y",$timestamp);
						?>
						<tr>
							<td><input name="taskids[]" class="label-inline" type="checkbox" value="<?php echo $data['id']; ?>"></td>
							<td><?php echo $data['id'] ?></td>
							<td><?php echo $data['task']; ?></td>
							<td><?php echo $date; ?></td>
							<td><a class="delete" data-taskid="<?php echo $data['id'] ?>" href="#"><b>Delete</b></a> | <a class="complete" data-taskid="<?php echo $data['id'] ?>" href='#'><b>Complete</b></a></td>



						</tr>
					<?php }
						mysqli_close( $connection );
					 ?>
					</tbody>
				</table>
				<select id="action" name="action">
					<option value="0">With Selected</option>
					<option value="bulkdelete">Delete</option>
					<option value="bulkcomplete">Mark as Complete</option>
				</select>
				<input class="button-primary" id="bulksubmit" value="submit" type="submit">
				</form>
		<?php } ?>
		<p>...</p>
		<h4>Add Tasks</h4>
		<form method="POST" action="tasks.php">
			<fieldset>
				<?php 
					$added = $_GET['added']?? "";
					if($added){
						echo "<p>Added Successfully</p>";
					}
				?>
				<label for="task">Task</label>
				<input type="text" id="task" placeholder="Task Details" name="task">
				<label for="date">Date</label>
				<input type="text" id="date" placeholder="Task Date" name="date" >
				<input name="action" type="hidden" value="add">
				<input class="button-primary" value="Add Task" type="submit">
				
			</fieldset>
		</form>
	</div>

<form  method="POST" id="completeform" action="tasks.php">
    <input type="hidden" name="action" value="complete">
    <input type="hidden" id="taskid" name="taskid">
</form>


<form  method="POST" id="deleteform" action="tasks.php">
    <input type="hidden" name="action" value="delete">
    <input type="hidden" id="deletetaskid" name="taskid">
</form>

<form  method="POST" id="incompleteform" action="tasks.php">
    <input type="hidden" name="action" value="incomplete">
    <input type="hidden" id="itaskid" name="taskid">
</form>
</body>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
<script>
	;(function($){
		$(document).ready(function(){
			
          $(".complete").on('click',function(){
          		var id = $(this).data("taskid");
                $("#taskid").val(id);
                $("#completeform").submit();
          });

       

          $(".delete").on('click',function(){
          		if(confirm("Are you sure to delete this task?")){
          			var id = $(this).data("taskid");
	                $("#deletetaskid").val(id);
	                $("#deleteform").submit();
          		}
          });

          $(".incomplete").on('click',function(){
          		var id = $(this).data("taskid");
                $("#itaskid").val(id);
                $("#incompleteform").submit();
          });


           $("#bulksubmit").on("click",function(){
              if($("#action").val()=='bulkdelete'){
                    if(!confirm("Are you sure to delete?")){
                        return false;
                    }
                }
            })



		});
	})(jQuery);
</script>
</html>
