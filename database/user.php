<?php

define(
    "USER_GET",
    "SELECT id, name, email, password, remember_token, created_at, updated_at FROM users"
);

define(
    "USER_FIND",
    "SELECT id, name, email, password, remember_token, created_at, updated_at FROM users"
);

define(
    "USER_INSERT",
    "INSERT INTO users (name, email, password)
    VALUES (:name, :email, :password)"
);

define(
    "USER_UPDATE",
    "UPDATE users SET name = :name WHERE id = :id"
);

define(
    "USER_UPDATE_TOKEN",
    "UPDATE users SET remember_token = :remember_token WHERE id = :id"
);

define(
    "USER_DELETE",
    "DELETE FROM users WHERE id = :id"
);
