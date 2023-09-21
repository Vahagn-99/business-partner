# Business Partner

Описание проекта и его цели.

## Требования

Перед запуском проекта убедитесь, что на вашей системе установлены следующие компоненты:

- [PHP](https://php.net) (версия 8.1 и выше)
- [Composer](https://getcomposer.org)
- [Node.js](https://nodejs.org) и [NPM](https://www.npmjs.com)

## Установка

1. Клонируйте репозиторий на вашем локальном компьютере:

```shell
git clone git@github.com:Vahagn-99/business-partner.git

cd business-partner && composer install

cp .env.example .env
cp .env .env.testing

php artisan migrate:fresh --seed

php artisan test

## Документация

{your-localhost}/api/documentation
