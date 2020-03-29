# WpFatFreeCMS

Проект не годится для использования в качестве рабочего проекта!

Простой движок для сайта PHP, основан на  микрофреймворке Fatfree. Созданный по типу Wordpress.

При построении проекта были использованы следующие материалы:
  * [Fatfree](https://fatfreeframework.com)
  * [Composer](https://getcomposer.org)
  * [TinyMCE](https://www.tinymce.com)
  * [Bootstrap](https://bootstrap-4.ru/docs/4.4/getting-started/download/)
  * [Jquery](https://jquery.com/download/)
  * [Awesome](https://fontawesome.com/start)
  * [Validator](https://github.com/vlucas/valitron)
  * And more...

# Установка проекта

  ### В папку assets  необходимо добавить следующие файлы
  * Bootstrap
  *	Jquery
  *	Awesome
  * TinyMce

  - Скачать архив [YaDisk](https://yadi.sk/d/4Hgv2LhsTQp_YA)

  ### Установите зависимости фреймворка используя Composer:
    ```php
    $ composer install
    ```
  ### Для соединения с базой данных необходимо заполнить в  файле config.ini соответствующие поля.
    ```php
    DB = "mysql:host=localhost;port=3306;dbname=name"
    DBUSER = "root"
    DBPASSWORD = ""
    ```
  - Перед этим соответственно создайте базу данных. Файл для импорта находится в папке sql
  - Назначьте в настройках сервера корневую папку public.
  - Назначьте  права папкам(chmod) 777, в которые пишутся логи и настройки(tmp и возможно config;
  - Файл robots.txt отредактируйте согласно вашим условиям и желаниям
  - Файл sitemap.xml создается автоматически из существующих страниц и постов в настройках сайта
  - Изначально в проекте присутствуен некий демонстрационный контент.
  - Добавлена учетная запись для первоначального входа admin/admin;
  - Вывод ошибок перенаправлен на страницу ошибки 404. Для более детальной отладки
  закоментируйте маршрут файле index.php

  -Более  детальная инфомация по проекту возможно появится  [моем сайте](http://baytheway.ru), если руки дойдут.
  ***

# Структура и расположение папки assets
![Структура папки assets ](https://github.com/wwowa-rus/wpfatfree/raw/master/img/assets.jpg)

# Updates
  - 03-18-2020: Первое размещение в репозитарий.

# Credits
  * 'No Credits'

# LICENSE
  - никаких лицензий и ограничений
