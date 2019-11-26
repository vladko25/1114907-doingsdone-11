<?php
require_once('data.php');

if (!isset($_GET['id']))
{
	getContent($show_complete_tasks, $array_projects, $array_tasks, $cur_user_name);
}
else
{
	$id = $_GET['id'];
	$cur_tasks = getTasksOfActiveProject($con, $cur_user_id, $id);

	if(!count($cur_tasks))
	{
		http_response_code(404);
		$error = "Ошибка 404. Страница не найдена";
	}

	getContent($show_complete_tasks, $array_projects, $cur_tasks, $cur_user_name, $error);
}

?>