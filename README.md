# 隨手用 Slim 3 Framework 做的簡單 API
使用套件：
- slim3 frame
- phpdotenv
- Eloquent

因為種種原因，簡單處理
也不使用完整的 restful...

# .env
複制 .env.example 為 .env
- 填入 db 設定
- ROOT_URL 可以留空
(沒有自己的 domain 使用 subdirectory route 會有問題，slim3 手冊上還沒找到解決方式; 故用這個方法解決)

# requirement
- php7+
- apache + url rewrite
- mysql

# how to start
- 設定 .env
- compose install
- 利用 storage/*.sql 建立 table...

# API list

- `get` : 取得文章列表
`/test_api/get/article_list`

- `get` : 取得文章 id = 1
`/test_api/get/article/1`

- `get` : 刪除文章 id=1, soft delete
`/test_api/delete/article/1`

- `get` : 還原刪除文章 id=1
`/test_api/restore/article/1`

- `post` : 新增文章
`/test_api/add/article`<br>
form POST 欄位<br>
title: req, min: 3, max: 120<br>
description: req, min: 3, max: 255<br>
image: url format<br>
content:  req, min: 5, max: 65535<br>

