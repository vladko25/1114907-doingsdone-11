<section class="content__side">
    <h2 class="content__side-heading">Проекты</h2>

    <nav class="main-navigation">
      <ul class="main-navigation__list">
        <?php if(isset($array_projects)): ?>
          <?php foreach($array_projects as $project_name): ?>
            <li class="main-navigation__list-item">
              <a class="main-navigation__list-item-link" href="pages/form-task.html"><?=$project_name; ?></a>
              <span class="main-navigation__list-item-count"><?=projectItemCount($array_tasks, $project_name); ?></span>
            </li>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </nav>

    <a class="button button--transparent button--plus content__side-button" href="pages/form-project.html" target="project_add">Добавить проект</a>
  </section>

  <main class="content__main">
    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="index.php" method="post" autocomplete="off">
      <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

      <input class="search-form__submit" type="submit" name="" value="Искать">
    </form>

    <div class="tasks-controls">
      <nav class="tasks-switch">
        <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
        <a href="/" class="tasks-switch__item">Повестка дня</a>
        <a href="/" class="tasks-switch__item">Завтра</a>
        <a href="/" class="tasks-switch__item">Просроченные</a>
      </nav>

      <label class="checkbox">
        <!--добавить сюда атрибут "checked", если переменная $show_complete_tasks равна единице-->
        <input class="checkbox__input visually-hidden show_completed" type="checkbox"
          <?php if($show_complete_tasks): ?> 
            checked
          <?php endif; ?>
        >
        <span class="checkbox__text">Показывать выполненные</span>
      </label>
    </div>

    <table class="tasks">
      <?php if(isset($array_tasks)): ?>
        <?php foreach($array_tasks as $task_item): ?>
          <?php
            if($task_item['done']) {
              if(!$show_complete_tasks) {
                continue;
              }
            }
          ?>
          <tr class="tasks__item task
            <?php if($task_item['done']): ?>
              task--complete 
            <?php endif; ?>
            <?php if(hotTasks($task_item['date']) && $task_item['date'] != null): ?>
              task--important
            <?php endif; ?>
          ">
            <td class="task__select">
              <label class="checkbox task__checkbox">
                <input class="checkbox__input visually-hidden" type="checkbox"
                  <?php if($task_item['done']): ?>
                    checked
                  <?php endif; ?>
                >
                <span class="checkbox__text"><?=$task_item['name']?></span>
              </label>
            </td>
            <td class="task__date"><?=$task_item['date']?></td>
            <td class="task__controls"></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </table>
</main>