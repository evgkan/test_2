Запуск проекта:
---------------
1 Склонируйте/скачайте проект  
2 Запустите composer install(update)  
3 Заполните данные БД и твиттера в файле app/config/parameters.yml (образец app/config/parameters.yml.dist)  
4 Выполните команду php bin/console doctrine:schema:update --force  
5 Заполните данные для http basic auth в файле app/config/security.yml (по умолчанию admin/admin)  
6 Выполните команду php bin/console tweets:get для первоначального заполнения БД  
7 Настройте крон для выполнения команды php bin/console tweets:get с желаемой частотой обновления данных  

* в корень добавлен дамп с sql данными news.sql

Алгоритм работы:
----------------
После инициализации БД при первом выполнении php bin/console tweets:get из твиттера подтягиваются 200 последних записей и сохраняются в БД.
При последующих выполнениях этой команды запрашиваются только новые записи.


Выполненные задания:
--------------------
все основные + подсветка твитов при поиске


Проблемы:
---------
возможны sql-инъекции через поисковые запросы (параметризация Doctrine не работает при некоторых условиях)