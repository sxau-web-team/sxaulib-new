<?php 
$page_title = '删除资源';
include ('../includes/admin_header.html');
echo '<div class="uk-width-medium-4-5"><div class="uk-panel uk-panel-header uk-panel-box"><h1>删除文章</h1>';

// Check for a valid user ID, through GET or POST:
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // From view_users.php
	$id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form submission.
	$id = $_POST['id'];
} else { // No valid ID, kill the script.
	echo '<p class="error">This page has been accessed in error.</p>';
	include ('../includes/admin_footer.html'); 
	exit();
}

require ('../mysqli_connect.php');

// Check if the form has been submitted:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if ($_POST['sure'] == 'Yes') { // Delete the record.

		// Make the query:
		$q = "DELETE FROM source WHERE ID=$id LIMIT 1";		
		$r = @mysqli_query ($dbc, $q);
		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.

			// Print a message:
			echo '<p>资源已删除！</p>';	

		} else { // If the query did not run OK.
			echo '<p class="error">资源不能被删除</p>'; // Public message.
			//echo '<p>' . mysqli_error($dbc) . '<br />Query: ' . $q . '</p>'; // Debugging message.
		}
	
	} else { // No confirmation of deletion.
		echo '<p>未删除！</p>';	
	}

} else { // Show the form.

	
	$q = "SELECT CONCAT(s_name, ', ', s_pro) FROM source WHERE ID=$id";
	$r = @mysqli_query ($dbc, $q);

	if (mysqli_num_rows($r) == 1) {

		// Get the post's information:
		$row = mysqli_fetch_array ($r, MYSQLI_NUM);
		
		// Display the record being deleted:
		echo "<h3>标题: $row[0]</h3>
		你确定要删除此资源吗?";
		
		// Create the form:
		echo '<form action="delete_source.php" method="post">
	<input type="radio" name="sure" value="Yes" /> 是
	<input type="radio" name="sure" value="No" checked="checked" /> 否
	<button type="submit" class="uk-button uk-button-danger" name="submit" >确认</button>
	<input type="hidden" name="id" value="' . $id . '" />
	</form>';
	
	} else { 
		echo '<p class="error">This page has been accessed in error.</p>';
	}

} // End of the main submission conditional.
echo '</div></div>';
mysqli_close($dbc);
		
include ('../includes/admin_footer.html');
?>