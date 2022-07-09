<?php

define(
    "RECIPE_GET",
    "SELECT id, user_id, type_id,
        url, title,
        description, ingredients,
        steps, duration, persons, created_at, updated_at
    FROM recipes
    ORDER BY id DESC"
);

define(
    "RECIPE_GET_LATEST",
    "SELECT id, user_id, type_id,
        url, title,
        description, ingredients,
        steps, duration, persons, created_at, updated_at
    FROM recipes
    ORDER BY id DESC
    LIMIT 20"
);

define(
    "RECIPE_FIND",
    "SELECT id, user_id, type_id,
        url, title,
        description, ingredients,
        steps, duration, persons, created_at, updated_at
    FROM recipes"
);

define(
    "RECIPE_INSERT",
    "INSERT INTO recipes (user_id, type_id, title, description, ingredients, steps, duration, persons)
    VALUES (:user_id, :type_id, :title, :description, :ingredients, :steps, :duration, :persons)"
);

define(
    "RECIPE_UPDATE",
    "UPDATE recipes
    SET user_id = :user_id, type_id = :type_id, title = :title,
        description = :description, ingredients = :ingredients,
        steps = :steps, duration = :duration, persons = :persons
    WHERE id = :id"
);

define(
    "RECIPE_DELETE",
    "DELETE FROM recipes WHERE id = :id"
);

define(
    "RECIPE_UPDATE_URL",
    "UPDATE recipes SET url = :url WHERE id = :id"
);

define(
    "RECIPE_GET_TITLE",
    "SELECT id AS recipe_id, title AS recipe_title FROM recipes"
);
