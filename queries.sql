-- Придумайте пару пользователей
INSERT INTO users (name, email, password)
VALUES	('Ivan', 'vano@mail.ru', 'gladiolus'),
		('Sasha', 'sashatorpedon@gmail.ru', 'whiteblack');

-- Существующий список проектов
INSERT INTO projects (name, user_id)
VALUES	('Входящие', 2), ('Учёба', 1), ('Работа', 1), ('Домашние дела', 2), ('Авто', 1);

-- Существующий список задач
INSERT INTO tasks (user_id, project_id, status, name, deadline)
VALUES	(1, 3, 0, 'Собеседование в IT компании', '2019-12-01'),
		(1, 3, 0, 'Выполнить тестовое задание', '2019-12-25'),
		(1, 2, 1, 'Сделать задание первого раздела', '2019-12-21'),
		(2, 1, 0, 'Встреча с другом', '2019-12-22'),
		(2, 4, 0, 'Купить корм для кота', '2019-11-12'),
		(2, 4, 0, 'Заказать пиццу', NULL);

-- получить список из всех проектов для одного пользователя
SELECT * FROM projects
WHERE user_id=2;

-- получить список из всех задач для одного проекта
SELECT * FROM tasks
WHERE project_id=3;

-- пометить задачу как выполненную
UPDATE tasks SET status=1
WHERE id=4;

-- обновить название задачи по её идентификатору
UPDATE tasks SET name='Заказать пиццу из кафе Satva'
WHERE id=6;
