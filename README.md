# 文件格式转换
目前支持word转html，html转pdf，pdf转图片，图片转pdf。

## 安装
composer require goleden/convert

## 相关工具
Imagick、LibreOffice、wkhtmltopdf

### docker下安装
- 镜像 php:php72-fpm-alpine
- imagick
	apk add --no-cache file-dev
	apk add --no-cache imagemagick-dev
    printf "\n" | pecl install imagick-3.4.4
    docker-php-ext-enable imagick

- LibreOffice
    apk add --no-cache libreoffice


