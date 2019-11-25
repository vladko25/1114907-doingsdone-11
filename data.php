<?php
require_once('functions.php');
date_default_timezone_set("Europe/Moscow");

$show_complete_tasks = 1;
$cur_user_id = 1;
$cur_user_name = '';
$array_projects = [];
$array_tasks = [];
$tasks_count = 0;
$error = '';

$con = mysqli_connect("localhost", "root", "","doingsdone");
mysqli_set_charset($con, "utf8");

if (!$con) {
    $error = mysqli_connect_error();
}
else {
    $cur_user_name = getRow($con, "SELECT user_name FROM users WHERE id = '$cur_user_id'");
    $array_projects = getRows($con,
        "SELECT p.id, p.project_name, COUNT(t.id) AS tasks_count
        FROM projects p
        LEFT JOIN tasks t ON p.id = t.project_id
        WHERE t.user_id = '$cur_user_id'
        GROUP BY project_id"
    );
    $array_tasks = getRows($con,
        "SELECT t.task_name, p.project_name, DATE_FORMAT(deadline, '%d.%m.%Y') AS deadline, status
        FROM tasks AS t
        JOIN projects AS p USING(user_id)
        WHERE t.user_id = '$cur_user_id' AND t.project_id = p.id"
    );
}