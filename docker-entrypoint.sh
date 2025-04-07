#!/bin/bash

# Устанавливаем нужный umask
umask 000

# Запускаем основной процесс (php-fpm)
exec php-fpm
