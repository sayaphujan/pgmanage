<?
if($_SESSION['type']!=='admin') exit();

if($_GET['mark_as_resolved']) {
	
	mysqli_query($link, 'UPDATE engineering_messages SET `status`=\'completed\' WHERE id=\''.sf($_GET['mark_as_resolved']).'\'');
	
	header('location: /?page=engineering_messages');
	exit();

}

?>

<h2 class="form-signin-heading">Active Engineering Messages</h2>

<div class="clear"></div>


<table class="table table-striped table-bordered table-hover">
<tr><th width="25%">Project / Step</th><th width="10%">User</th><th width="10%">Date</th><th>Message</th><th width="5%"></th></tr>

<?

$q = mysqli_query($link, 'SELECT  engineering_messages.id, engineering_messages.message, engineering_messages.status, engineering_messages.date, projects.name as project_name, users.name as user_name, project_steps.name as step_name, project_steps.id as step_id, projects.id as project_id FROM engineering_messages, projects, project_steps, users WHERE engineering_messages.project = projects.id AND engineering_messages.step = project_steps.id AND engineering_messages.user = users.id AND engineering_messages.status=\'active\' ORDER BY engineering_messages.id ASC');


while($row = mysqli_fetch_assoc($q)) {
	echo '<tr>
		<td>'.$row['project_name'].'<br><a href="/?page=project_step&id='.$row['project_id'].'&get_step='.$row['step_id'].'&">'.$row['step_name'].'</a></td>
		<td>'.$row['user_name'].'</td>
		<td>'.$row['date'].'</td>
		<td>'.$row['message'].'</td>
		<td><button class="btn btn-success" onclick="document.location=\'/?page=engineering_messages&mark_as_resolved='.$row['id'].'\';">Resolved</button></td>
		</tr>';
}

?>

</table>      

<br />
<br />
<br />
<br />


<h2 class="form-signin-heading">Production Logs</h2>

<div class="clear"></div>


<table class="table table-striped table-bordered table-hover">
<tr><th width="25%">Project / Step</th><th width="10%">Sub Step</th><th width="10%">User</th><th width="10%">Date</th><th>Message</th></tr>

<?

$q = mysqli_query($link, 'SELECT  project_log.id, project_log.log, project_log.date, projects.name as project_name, users.name as user_name, project_steps.name as step_name, project_sub_steps.name as sub_step_name FROM project_log, projects, users, project_steps, project_sub_steps WHERE project_log.project = projects.id AND project_log.step = project_steps.id AND project_log.sub_step = project_sub_steps.id AND project_log.user = users.id ORDER BY project_log.id DESC LIMIT 100 ');



while($row = mysqli_fetch_assoc($q)) {
	echo '<tr>
		<td>'.$row['project_name'].'<br>('.$row['step_name'].')</td>
		<td>'.$row['sub_step_name'].'</td>
		<td>'.$row['user_name'].'</td>
		<td>'.$row['date'].'</td>
		<td>'.nl2br($row['log']).'</td>
		</tr>';
}

?>

</table>      
