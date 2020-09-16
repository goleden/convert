# 文件格式转换

目前支持word转html，word转pdf，html转pdf，pdf转图片，图片转pdf。

已生产使用。

## 安装

composer require goleden/convert

## 相关工具

Imagick、LibreOffice、wkhtmltopdf

## 相关问题

- libreoffice转换，格式会有部分问题。

- pdf大文件，imagick转化较慢，甚至会出现内存泄露问题，可以单张转换，已提供convertPage方法。

- pdf转图片，需要安装ghostscript

- libreoffice需要安装对应字体

- libreoffice修改临时目录权限，或者设置www用户的$HOME目录


## 即将支持

office文件转其他（windows）
