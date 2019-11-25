<?php
require_once('functions.php');
date_default_timezone_set("Europe/Moscow");

$show_complete_tasks = rand(0, 1);
$cur_user_id = 1;
$cur_user_name = '';
$array_projects = [];
$array_tasks = [];
$error = '';

$con = mysqli_connect("localhost", "root", "","doingsdone");
mysqli_set_charset($con, "utf8");

if (!$con)
{
    $error = mysqli_connect_error();
}
else
{
    $array_projects = getProjectsOfUser($con, $cur_user_id);
    $array_tasks = getTasksOfUser($con, $cur_user_id);
    $cur_user_name = $array_tasks[0]['user_name'];
}