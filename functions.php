<?php
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date) : bool {
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form (int $number, string $one, string $two, string $many): string
{
    $number = (int) $number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = []) {
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
* Подсчитывает количество задач в проекте
* @param array $project_list Ассоциативный массив с задачами
* @param string $selected_project_name Название проекта, в котором подсчитываем количество задач
* @return int Количество задач в проекте
 */
function projectItemCount($con, $selected_project_name, $cur_user_id) {
  $count = 0;
  $project_list = getRows($con, 
    "SELECT p.id, COUNT(t.id) AS tasks_count
    FROM projects p
    LEFT JOIN tasks t USING(user_id)
    WHERE t.user_id = '$cur_user_id' AND p.id = t.project_id
    GROUP BY p.id");
  if (isset($project_list['tasks_count'])) {
      $count = $project_list['tasks_count'];
  }
  return $count;
}

/**
* Высчитывает оставшееся время до выполнения задачи и сравнивает полученное время с 24 часами
* @param date $task_date Указанное в задаче время выполнения
* @return bool Возвращает true, если кол-во часов до выполнения задачи меньше или равно 24
 */
function hotTasks($task_date) {
    $hot_time = false;
    $current_date = time();

    if ($task_date != null) {
        $diff = floor((strtotime($task_date)-$current_date)/3600);

        if($diff <= 24) {
            $hot_time = true;
        }
    }

    return $hot_time;
}

/**
* Получает записи из БД по запросу
* @param string $con Ресурс соединения
* @param string $mysql_query Запрос SQL
* @return array Возвращает записи из БД в виде многомерного массива
 */
function getRows($db_connect, $mysql_query) {
    $result = mysqli_query($db_connect, $mysql_query);
    if (!$result) {
        $error = mysqli_error($db_connect);
        print("Соединение не удалось: " . $error);
        return false;
    }
    $result_array = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $result_array;
}

/**
* Получает id, названия проектов и количество задач в этих проектах
* @param string $con Ресурс соединения
* @param int $user_id Значение id у текущего пользователя
* @return array Возвращает id, названия проектов и количество задач в виде многомерного массива
 */
function getProjectsOfUser($db_connect, int $cur_user_id) {
    $result_array = getRows($db_connect,
        "SELECT p.id, p.project_name, count(t.id) AS tasks_count
        FROM projects p
        LEFT JOIN tasks t ON p.id = t.project_id
        WHERE p.user_id = '$cur_user_id'
        GROUP BY p.id, p.project_name"
    );

    return $result_array;
}

/**
* Получает все задачи для текущего пользователя из БД
* @param string $con Ресурс соединения
* @param int $user_id Значение id у текущего пользователя
* @return array Возвращает задачи для текущего пользователя в виде многомерного массива
 */
function getTasksOfUser($db_connect, int $cur_user_id) {
    $array_tasks = getRows($db_connect,
        "SELECT t.task_name, DATE_FORMAT(deadline, '%d.%m.%Y') AS deadline, status, u.user_name
        FROM tasks t
        JOIN projects p USING(user_id)
        JOIN users u ON t.user_id = u.id
        WHERE t.user_id = '$cur_user_id' AND t.project_id = p.id"
    );

    return $array_tasks;
}

/**
* Получает задачи для выбранного проекта
* @param string $con Ресурс соединения
* @param int $user_id Значение id у текущего пользователя
* @return array Возвращает задачи для выбранного проекта в виде многомерного массива
 */
function getTasksOfActiveProject($db_connect, int $cur_user_id, $get_id) {
    $array_tasks = getRows($db_connect,
        "SELECT * FROM tasks t 
        JOIN projects p ON t.project_id = p.id
        WHERE p.user_id = '$cur_user_id' AND p.id = '$get_id'"
    );

    return $array_tasks;
}

/**
* Выводит контент через шаблонизатор
* @param int $show_complete_tasks
* @param array $projects Список отображаемых проектов
* @param array $tasks Список отображаемых задач
* @param string $user_name Имя текущего пользователя
* @param string $error Сообщение об ошибке, необязательный параметр
* @return Выводит на экран главную страницу с данными
 */
function getContent($show_complete_tasks, $projects, $tasks, $user_name, $error = "") {
        $page_content = include_template('main.php', [
            'show_complete_tasks' => $show_complete_tasks,
            'array_projects' => $projects,
            'array_tasks' => $tasks,
            'error' => $error
            ]
        );

        $layout_content = include_template('layout.php', [
            'content' => $page_content,
            'title' => 'Дела в порядке',
            'cur_user_name' => $user_name
            ]
        );

        print($layout_content);
}