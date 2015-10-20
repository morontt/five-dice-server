# five-dice-server

[![Circle CI](https://circleci.com/gh/morontt/five-dice-server.svg?style=shield&circle-token=ba923bb2f3ce136f1f6895c7ba008c845c5d955b)](https://circleci.com/gh/morontt/five-dice-server)
[![Join the chat at https://gitter.im/morontt/five-dice-server](https://badges.gitter.im/Join%20Chat.svg)](https://gitter.im/morontt/five-dice-server?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![Stories in Ready](https://badge.waffle.io/morontt/five-dice-server.svg?label=ready&title=Ready)](http://waffle.io/morontt/five-dice-server)
[![Stack Share](http://img.shields.io/badge/tech-stack-0690fa.svg?style=flat)](http://stackshare.io/morontt/five-dice)

Вариация на тему ["покер на костях"](https://ru.wikipedia.org/wiki/%D0%9F%D0%BE%D0%BA%D0%B5%D1%80_%D0%BD%D0%B0_%D0%BA%D0%BE%D1%81%D1%82%D1%8F%D1%85). Цель - сражение игровых движков друг с другом.

- [Правила](./doc/RULES.md)
- [API для роботов](./doc/API.md)

## Установка проекта

    sudo npm install -g bower
    sudo npm install -g babel

Запустить установочный скрипт:

```bash
  ./install.sh
```

Если используется веб-сервер Apache, то создать виртуальный хост наподобие следующего:

```apacheconf
<VirtualHost *:80>
    ServerName five-dice.loc
    DocumentRoot "path/to/five-dice-server/web"

    <Directory "path/to/five-dice-server/web">
        DirectoryIndex index.php index.html
        AllowOverride All
        Allow from all

        # Apache 2.2
        # Order deny,allow

        # Apache 2.4
        Require all granted
    </Directory>
</VirtualHost>
```
