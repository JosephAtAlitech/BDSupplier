<?php
	include 'includes/session.php';

	if(isset($_POST['edit'])){
		$id = $_POST['id'];
		$edit_day_type = $_POST['edit_day_type'];
		$edit_offday_cause = $_POST['edit_offday_cause'];

		$sql = "UPDATE calender_tbl SET day_type = '$edit_day_type', offday_cause = '$edit_offday_cause' WHERE id = '$id'";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Calender Event updated successfully';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
		header('location:calendar-view.php');
	}
	
	
	
/*for offday calendar*/

	if(isset($_POST['offdayedit'])){
		$id = $_POST['id'];
		$edit_day_type = $_POST['edit_day_type'];
		$edit_offday_cause = $_POST['edit_offday_cause'];

		$sql = "UPDATE calender_tbl SET day_type = '$edit_day_type', offday_cause = '$edit_offday_cause' WHERE id = '$id'";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Offday Event updated successfully';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
		header('location:offday-view.php');
	}
	
	


/*for onday calendar*/

	if(isset($_POST['onday_edit'])){
		$id = $_POST['id'];
		$edit_day_type = $_POST['edit_day_type'];
		$edit_offday_cause = $_POST['edit_offday_cause'];

		$sql = "UPDATE calender_tbl SET day_type = '$edit_day_type', offday_cause = '$edit_offday_cause' WHERE id = '$id'";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Onday Event updated successfully';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
		header('location:onday-view.php');
	}

/*for holiday calendar*/

	if(isset($_POST['holidayedit'])){
		$id = $_POST['id'];
		$edit_day_type = $_POST['edit_day_type'];
		$edit_offday_cause = $_POST['edit_offday_cause'];

		$sql = "UPDATE calender_tbl SET day_type = '$edit_day_type', offday_cause = '$edit_offday_cause' WHERE id = '$id'";
		if($conn->query($sql)){
			$_SESSION['success'] = 'Holiday Event updated successfully';
		}
		else{
			$_SESSION['error'] = $conn->error;
		}
		header('location:holiday-view.php');
	}	


?>