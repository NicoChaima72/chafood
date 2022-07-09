<?php

define(
    "TYPE_GET",
    "SELECT id, name, url, image, description
    FROM types
    ORDER BY name"
);

define(
    "TYPE_GET_POPULAR",
    "SELECT types.id, types.name, types.url, types.image, types.description
    FROM types
    LEFT JOIN recipes
    ON types.id = recipes.type_id
    GROUP BY types.id, types.name, types.url, types.image, types.description
    ORDER BY count(recipes.type_id) DESC, types.name
    LIMIT 7;"
);

define(
    "TYPE_FIND",
    "SELECT id, name, url, image, description
    FROM types"
);
