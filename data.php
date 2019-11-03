<?php
// показывать или нет выполненные задачи
$show_complete_tasks = rand(0, 1);

$array_projects = [
	"Входящие",
	"Учёба",
	"Работа",
	"Домашние дела",
	"Авто"
];

$array_tasks = [
  [
    'name' => 'Собеседование в IT компании',
    'date' => '01.12.2019',
    'category' => $array_projects[2],
    'done' => false
  ],
  [
    'name' => 'Выполнить тестовое задание',
    'date' => '25.12.2019',
    'category' => $array_projects[2],
    'done' => false
  ],
  [
    'name' => 'Сделать задание первого раздела',
    'date' => '21.12.2019',
    'category' => $array_projects[1],
    'done' => true
  ],
  [
    'name' => 'Встреча с другом',
    'date' => '22.12.2019',
    'category' => $array_projects[0],
    'done' => false
  ],
  [
    'name' => 'Купить корм для кота',
    'date' => null,
    'category' => $array_projects[3],
    'done' => false
  ],
  [
    'name' => 'Заказать пиццу',
    'date' => null,
    'category' => $array_projects[3],
    'done' => false
  ]
];