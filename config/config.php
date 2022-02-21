<?php

	// This function has a parameter $location that should contain the SQLite database path and name.
	// Returns the handle of the database connection
	function sqlite_open($location)
	{
		$handle = new SQLite3($location);
		return $handle;
	}
	
		
?>