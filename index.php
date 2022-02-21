<?php 
	require_once('layout/header.php');
	require_once('config/config.php');

	$db = sqlite_open('database/todo.db');
	
	$msg = "";
	
	if (isset($_POST["action"])) {
		
		if (isset($_POST['id'])) {
			$id = $_POST["id"];
			
			if ($_POST["action"] == "delete") {
				$sql = "DELETE FROM tasks WHERE id= ".$id;
				$msg = "Task deleted!";
			} else if ($_POST["action"] == "complete") {
				$sql = "UPDATE tasks SET completed=1 WHERE id= ".$id;
				$msg = "Task completed!";
			} else if ($_POST["action"] == "edit") {
				$task = str_replace("'", "´", trim($_POST["task"]));
				$priority = trim($_POST["priority"]);
				
				$sql = "UPDATE tasks SET name='".$task."', priority=".$priority." WHERE id= ".$id;
				$msg = "Task updated!";
			}
			
		}
		
		if ($_POST["action"] == "add") {
			$task = str_replace("'", "´", trim($_POST["task"]));
			$priority = trim($_POST["priority"]);
			
			$sql = "INSERT INTO tasks ('name', 'priority', 'completed') VALUES ('$task', $priority, 0)";
			$msg = "Task created!";			
		}	
		
		$result = $db->query($sql);		
	}
		
	$result = $db->query('SELECT * FROM tasks ORDER BY priority ASC, name ASC');
	
?>

<h2>ToDo List</h2>

<?php if ($msg != "") { ?>
	<div class="alert alert-success alert-dismissible fade show" role="alert">
	  <?php echo $msg;?>
	  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
	</div>
<?php } ?>
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTask">Add Task</button>
<div class="table-responsive">
	<table class="table table-striped table-sm">
	  <thead>
		<tr>
		  <th scope="col">#</th>
		  <th scope="col">Name</th>
		  <th scope="col">Priority</th>
		  <th scope="col">Completed</th>
		  <th scope="col">Actions</th>
		</tr>
	  </thead>
	  <tbody>
	  <?php 
			$totalTasks=0;
			$completedTasks = 0;
	  
			while ($row = $result->fetchArray()) {
				$totalTasks++;
				if ($row['completed'] == 1)
					$completedTasks++;
				
				echo "<tr>";
				echo "<td>".$row['id']."</td>";
				echo "<td>".$row['name']."</td>";
				echo "<td>".getPriority($row['priority'])."</td>";
				echo "<td>";
				echo ($row['completed'] == 1) ? 'Yes' : 'No';
				echo "</td>";
				echo "<td><input type='button' class='btn btn-warning float-sm-start px-2' name='edit' id='edittask' value='Edit' data-id='".$row['id']."' data-task='".$row['name']."' data-priority='".$row['priority']."'>		
				<form class='float-sm-start px-2' method='post' onsubmit='return confirmDelete();'><input type='hidden' name='id' value='".$row['id']."'><input type='hidden' name='action' value='delete'><input type='submit' class='btn btn-danger' name='submit' value='Delete'></form>";
				echo ($row['completed'] == 0) ? "<form method='post'><input type='hidden' name='id' value='".$row['id']."'><input type='hidden' name='action' value='complete'><input type='submit' class='btn btn-success' name='complete' value='Complete'></form>" : "";
				echo "</td>";
				echo "</tr>";
			}
	 ?>		
	  </tbody>
	</table>
	Total Tasks: <?php echo $totalTasks;?><br>
	Completed Tasks: <?php echo $completedTasks;?><br>
	
</div>

<div class="modal fade" id="addTask" tabindex="-1" aria-labelledby="addTaskLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Task</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
   	  <form method="post" id="form-add">
      <div class="modal-body">
			<label>Task Name</label>
			<input type="text" class="form-control" name="task" placeholder="Insert the task name" required />
			<label>Priority</label>
			<select class="form-control" name="priority" required>
				<option value="">Select Priority</option>
				<option value="0">High</option>
				<option value="1">Medium</option>
				<option value="2">Low</option>
			</select>
			<input type="hidden" name="action" value="add" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
	  </form>
    </div>
  </div>
</div>


<div class="modal fade" id="editTask" tabindex="-1" aria-labelledby="editTaskLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Task</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
   	  <form method="post" id="form-add">
      <div class="modal-body">
			<label>Task Name</label>
			<input type="text" class="form-control" name="task" id="edit_task" placeholder="Insert the task name" required />
			<label>Priority</label>
			<select class="form-control" name="priority" id="edit_priority" required>
				<option value="">Select Priority</option>
				<option value="0">High</option>
				<option value="1">Medium</option>
				<option value="2">Low</option>
			</select>
			<input type="hidden" name="action" value="edit" />
			<input type="hidden" name="id" id="edit_id" value="" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
	  </form>
    </div>
  </div>
</div>
<?php 
	function getPriority($priority) {
			
		if ((int)$priority == 0)
			return "High";
		else if ((int)$priority == 1)
			return "Medium";
		else if ((int)$priority == 2)
			return "Low";
		
	}	
?>


<?php require_once('layout/footer.php'); ?>