<?php
require_once('data.php');

if (!isset($_GET['id'])) {
	$page_content = include_template('main.php', [
	    'show_complete_tasks' => $show_complete_tasks,
	    'array_projects' => $array_projects,
	    'array_tasks' => $array_tasks
	    ]
	);

	$layout_content = include_template('layout.php', [
	    'content' => $page_content,
	    'title' => 'Дела в порядке',
	    'cur_user_name' => $cur_user_name['user_name']
	    ]
	);

	print($layout_content);
}
else {
	$id = $_GET['id'];

	$cur_tasks = getRows($con,
		"SELECT * FROM tasks t 
		JOIN projects p ON t.project_id = p.id
		WHERE p.user_id = '$cur_user_id' AND p.id = '$id'"
	);
	
	if(!count($cur_tasks)) {
		http_response_code(404);
		$error = "Ошибка 404. Страница не найдена";

		$page_content = include_template('main.php', [
	    	'show_complete_tasks' => $show_complete_tasks,
	    	'array_projects' => $array_projects,
	    	'array_tasks' => $cur_tasks,
	    	'error' => $error
	    	]
	    );

	    $layout_content = include_template('layout.php', [
	    	'content' => $page_content,
	    	'title' => 'Дела в порядке',
	    	'cur_user_name' => $cur_user_name['user_name']
	    	]
	    );

	    print($layout_content);
	}
	else {
		$page_content = include_template('main.php', [
	    	'show_complete_tasks' => $show_complete_tasks,
	    	'array_projects' => $array_projects,
	    	'array_tasks' => $cur_tasks
	    	]
	    );

		$layout_content = include_template('layout.php', [
	    	'content' => $page_content,
	    	'title' => 'Дела в порядке',
	    	'cur_user_name' => $cur_user_name['user_name']
	    	]
	    );

	    print($layout_content);
	}
}

?>