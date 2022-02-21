<?php 

	// load the header file and config file
	require_once('layout/header.php');
	require_once('config/config.php');

	// Open database connection to SQLite DB
	$db = sqlite_open('database/todo.db');
	
	// Initialize variable for displaying messages
	$msg = "";
	
	// If $_POST action exists (in case a task is added/edited/deleted/confirmed)
	if (isset($_POST["action"])) {
		
		// If $_POST id exists
		if (isset($_POST['id'])) {
			// Gets the value of id
			$id = $_POST["id"];
			
			// If action is to delete a task
			if ($_POST["action"] == "delete") {
				
				// Set $sql variable with SQL statement for deleting the record
				$sql = "DELETE FROM tasks WHERE id= ".$id;
				
				// Set message to be displayed on the screen 
				$msg = "Task deleted!";
			} else if ($_POST["action"] == "complete") { // If action is to complete a task
				// Set $sql variable with SQL statement for completing task
				$sql = "UPDATE tasks SET completed=1 WHERE id= ".$id;
				
				// Set message to be displayed on the screen 
				$msg = "Task completed!";
			} else if ($_POST["action"] == "edit") { // If action is to save edited task
			
				// Gets the value of task name and priority
				$task = str_replace("'", "´", trim($_POST["task"]));
				$priority = trim($_POST["priority"]);
			
				// Set $sql variable with SQL statement for updating the record
				$sql = "UPDATE tasks SET name='".$task."', priority=".$priority." WHERE id= ".$id;
				
				// Set message to be displayed on the screen 
				$msg = "Task updated!";
			}
			
		}
		
		// If action is to add new task
		if ($_POST["action"] == "add") {
			// Gets the value of task name and priority
			$task = str_replace("'", "´", trim($_POST["task"]));
			$priority = trim($_POST["priority"]);
			
			// Set $sql variable with SQL statement for creating a new task
			$sql = "INSERT INTO tasks ('name', 'priority', 'completed') VALUES ('$task', $priority, 0)";
			
			// Set message to be displayed on the screen
			$msg = "Task created!";			
		}	
		
		// Execute the SQL statement 
		$result = $db->query($sql);		
	}
	
	// Gets all the tasks ordered by priority and name
	$result = $db->query('SELECT * FROM tasks ORDER BY priority ASC, name ASC');
	
?>
<div class="container">
	<h2>ToDo List</h2>

	<!-- if $msg variable is not empty, shows a message on screen -->
	<?php if ($msg != "") { ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
		  <?php echo $msg;?>
		  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php } ?>
	
	<!-- Display button for showing modal for creating new task -->
	<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTask">Add Task</button>
	
	<!-- Display table with all records -->
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
					
					// Increase counter for total tasks
					$totalTasks++;
					
					// If priority = 0 set red background with white text color
					if ((int)$row['priority'] == 0)
						$clsBackground = "class='bg-danger text-white'";
					else // If priority is not 0 set normal background color
						$clsBackground = "class='bg-light'"; 
					
					// If task is completed increase counter for completed tasks
					if ($row['completed'] == 1)
						$completedTasks++;
					
					echo "<tr $clsBackground>";
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

	<!-- Display modal for creating new task -->
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

	<!-- Display modal for editing existing task -->
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
</div>	
<?php 

	// Return a string for the priority
	// High = Priority 0
	// Medium = Priority 1
	// Low = Priority 2
	
	function getPriority($priority) {
			
		if ((int)$priority == 0)
			return "High";
		else if ((int)$priority == 1)
			return "Medium";
		else if ((int)$priority == 2)
			return "Low";
		
	}	
?>

<!-- Load footer with JS -->
<?php require_once('layout/footer.php'); ?>