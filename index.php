<?php
require_once('functions.php');
require_once('data.php');
date_default_timezone_set("Europe/Moscow");

$page_content = include_template("main.php", [
    'show_complete_tasks' => $show_complete_tasks,
    'array_projects' => $array_projects,
    'array_tasks' => $array_tasks,
    'tasks_count' => $tasks_count
    ]
);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'title' => 'Дела в порядке',
    'cur_user_name' => $cur_user_name['user_name']
    ]
);

print($layout_content);

?>