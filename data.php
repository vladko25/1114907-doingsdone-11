<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);
$cur_user_id = rand(1, 3);
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
    $sql = "SELECT user_name FROM users WHERE id = '$cur_user_id'";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $cur_user_name = mysqli_fetch_assoc($result);
    }

    $sql = "SELECT p.project_name, COUNT(t.id) AS tasks_count FROM projects p
    JOIN tasks t ON p.id = t.project_id
    WHERE t.user_id = '$cur_user_id' AND t.project_id = p.id
    GROUP BY project_id";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $array_projects = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    $sql = "SELECT t.task_name, p.project_name, DATE_FORMAT(deadline, '%d.%m.%Y') AS deadline, status FROM tasks AS t
    JOIN projects AS p USING(user_id)
    WHERE t.user_id = '$cur_user_id' AND t.project_id = p.id";
    $result = mysqli_query($con, $sql);
    if ($result) {
        $array_tasks = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}