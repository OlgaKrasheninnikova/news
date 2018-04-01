Список действий необходимый для запуска проекта:

1. Склонировать репозиторий
2. Создать базу данных Mysql. Прописать данные для доступа к БД в файл config/db.php
3. composer create-project
4. Запустить миграцию стороннего модуля авторизации dektrium/yii2-user:
  ./yii migrate/up --migrationPath=@vendor/dektrium/yii2-user/migrations
5. Запусить стандартную миграцию Yii2 для создани Rbac структуры БД:
  ./yii migrate --migrationPath=@yii/rbac/migrations/
6. Запустить собственную миграцию проекта:
  ./yii migrate
7. chmod 0777 web/assets/
8. Зарегистрировать тестовых пользователей стандартными средствами авторизации. Присвоить пользователям права администратора и менеджера можно с помощью утилит:
 ./yii rbac/make-admin {id}
 ./yii rbac/make-manager {id}
В качестве {id} следует передать имя либо email либо id пользователя.