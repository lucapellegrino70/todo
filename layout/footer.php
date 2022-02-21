

	</body>
	
	<!-- Load Bootstrap JS, jQuery and Popper JS -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
	
	
	<script>
		$(document).ready(function() {
			
			// Click event when user click the Edit button on the task row of the table
			// It gets the id, task name and priority and sets the input/select fields with those value and display the Edit Task modal 
			$('#edittask').click(function() {
				var id = $(this).data("id");
				var task = $(this).data("task");
				var priority = $(this).data("priority");
				
				$('#edit_id').val(id);
				$('#edit_task').val(task);
				$('#edit_priority').val(priority);
				
				$('#editTask').modal('show');
				
			});
		});

		// When the button to delete a row (task) is clicked it asks for confirmation	
		function confirmDelete() {
			if (confirm('Do you really want to delete the record?'))
				return true;
			else
				return false;
		
		}	

	</script>
</html>
	