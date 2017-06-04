# 隨手用 Slim 3 Framework 做的簡單 API
使用套件：
- slim3 Framework
- phpdotenv
- Eloquent

因為種種原因，簡單處理:
- 不使用完整的 restful...
- 不另切controller，直接在 route 處理

# .env
複製 .env.example 為 .env
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
- 利用 storage/database-schema.sql 建立 table...

# API list

- `get` : 取得文章列表
`/test_api/get/article_list`<br>
e.g. `/test_api/get/article_list?page=1&amount=10`<br>
page: 指定頁數, 預設 1<br>
amount: 指定每頁筆數, 預設 5 筆, 至多 50 筆

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

