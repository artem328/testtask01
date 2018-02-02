# Тестовое задание

## Окружение для разработки
### Требования
- Vagrant
- VirtualBox

### Запуск
Чтоб запустить контейнер с настроенным окружением, необходимо
запустить команду `vagrant up`. 
При первом запуске, скачается образ системы и установятся необходимые пакеты. 
Затем команда будет запускать настроенную систему.

### Данные
#### HTTP
Адрес сервера: [http://192.168.33.10](http://192.168.33.10)

#### MySQL
- Сервер: `localhost`
- Пользователь: `vagrant`
- База данных: `vagrant`
- Пароль: `secret`

#### Путь к файлам сайта
Сайт находится в `/home/ubuntu/site/`

## Миграции
Чтоб запустить миграцию, необходимо запустить команду
`php index.php migrate`

### Vagrant
Для начала нужно подключится к виртуальной машине по ssh,
выполнив команду `vagrant ssh`.
Для запуска миграции в Vagrant, необходимо перед запуском комады 
объявить переменную окружения `CI_ENV` со значением `development-vagrant`.
Например `export CI_ENV=development-vagrant; php index.php migrate`