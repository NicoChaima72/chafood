<?php

define(
    "PHOTO_GET",
    "SELECT photos.id, photos.recipe_id, photos.url,
        photos.created_at, photos.updated_at
    FROM photos"
);

define(
    "PHOTO_FIND",
    "SELECT photos.id, photos.recipe_id, photos.url,
        photos.created_at, photos.updated_at
    FROM photos"
);

define(
    "PHOTO_INSERT",
    "INSERT INTO photos (recipe_id, url) VALUES (:recipe_id, :url)"
);

define(
    "PHOTO_UPDATE",
    "UPDATE photos SET url = :url WHERE id = :id"
);

define(
    "PHOTO_DELETE",
    "DELETE FROM photos WHERE id = :id"
);
