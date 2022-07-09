-- CREAR BASE DE DATOS


CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(200) UNIQUE,
    password VARCHAR(60) NOT NULL,
    remember_token VARCHAR(200),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE types (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    url VARCHAR(100) UNIQUE NOT NULL,
    image VARCHAR(300) NOT NULL,
    description VARCHAR(200) NOT NULL
);

CREATE TABLE recipes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    type_id INT,
    url VARCHAR(200) UNIQUE,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    ingredients TEXT NOT NULL,
    steps MEDIUMTEXT NOT NULL,
    duration INT NOT NULL,
    persons INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (type_id) REFERENCES types(id)
);


CREATE TABLE photos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    recipe_id INT NOT NULL,
    url TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (recipe_id) REFERENCES recipes(id) ON DELETE CASCADE
);




INSERT INTO users (id, name, email, password) VALUES 
    (1, 'Admin Admin', 'admin@admin.com', '$2y$10$PmLZkKoPL8TGTHatixfac.OZvrISWVd0aBGK80r0vH72nMQn9h11W') /* password: admin */,
    (2, 'User User', 'user@user.com', '$2y$10$5qLzfwGXLZM6LOQolLYaAur43mjzMSGaNeqhM1nMhLr59VEQJivJK' /* password: user */);

INSERT INTO types (id, name, url, image, description) VALUES
    (1,'Pollo', 'pollo', 'https://cdn2.cocinadelirante.com/sites/default/files/styles/gallerie/public/images/2017/01/pechugasdepollo.jpg', 'Las mejores recetas de cocina para la familia'),
    (2,'Tallarines', 'tallarines', 'https://cocina-casera.com/wp-content/uploads/2018/04/tallarines-rojos.jpg', 'Las mejores recetas de cocina para la familia'),
    (3,'Postres', 'postres', 'https://hips.hearstapps.com/hmg-prod.s3.amazonaws.com/images/copas-de-cheesecake-1557229055.jpg', 'Las mejores recetas de cocina para la familia'),
    (4,'Mariscos', 'mariscos', 'https://dam.cocinafacil.com.mx/wp-content/uploads/2014/03/Parrillada-de-mariscos.jpg', 'Las mejores recetas de cocina para la familia'),
    (5,'Guisos', 'guisos', 'https://www.chilerecetas.cl/images/fotos/guiso_de_zapallito_italiano.jpg', 'Las mejores recetas de cocina para la familia'),
    (6,'Carnes', 'carnes', 'https://cdn2.cocinadelirante.com/sites/default/files/images/2019/04/como-sauvizar-carnes.jpg', 'Las mejores recetas de cocina para la familia'),
    (7,'Tortillas', 'tortillas', 'https://locosxlaparrilla.com/wp-content/uploads/2015/02/Receta-recetas-locos-x-la-parrilla-locosxlaparrilla-receta-tortilla-acelga-tortilla-acelga-tortilla-receta-tortilla-2.jpg', 'Las mejores recetas de cocina para la familia'),
    (8,'Arroz', 'arroz', 'https://somoscocineros.com/assets/uploads/recipes/63/receta-facil-y-rapida-de-arroz-blanco.png', 'Las mejores recetas de cocina para la familia'),
    (9,'Ensaladas', 'ensaladas', 'https://eldiariony.com/wp-content/uploads/sites/2/2020/04/shutterstock_1564648540.jpg?quality=60&strip=all&w=1200', 'Las mejores recetas de cocina para la familia'),
    (10,'Aperitivos', 'aperitivos', 'https://www.recetasderechupete.com/wp-content/uploads/2019/12/Entrantes-navide%C3%B1os-525x360.jpg', 'Las mejores recetas de cocina para la familia');

INSERT INTO recipes (id,user_id, type_id, url, title, description, ingredients, steps, duration, persons) VALUES 
    (
        1,
        1,
        2,
        '1',
        'Porotos con mazamorra',
        'Es un hecho establecido hace demasiado tiempo que un lector se distraerá con el contenido del texto de un sitio mientras que mira su diseño. El punto de usar Lorem Ipsum es que tiene una',
        'Es un hecho establecido hace demasiado tiempo que un lector se distraerá con el contenido del texto de un sitio mientras que mira su diseño. El punto de usar Lorem Ipsum es que tiene una distribución más o menos normal de las letras, al contrario de usar textos como por ejemplo "Contenido aquí, contenido aquí"',
        'Es un hecho establecido hace demasiado tiempo que un lector se distraerá con el contenido del texto de un sitio mientras que mira su diseño. El punto de usar Lorem Ipsum es que tiene una distribución más o menos normal de las letras, al contrario de usar textos como por ejemplo "Contenido aquí, contenido aquí". Estos textos hacen parecerlo un español que se puede leer. Muchos paquetes de autoedición y editores de páginas web usan el Lorem Ipsum como su texto por defecto, y al hacer una búsqueda de "Lorem Ipsum" va a dar por resultado muchos sitios web que usan este texto si se encuentran en estado de desarrollo. Muchas versiones han evolucionado a través de los años, algunas veces por accidente, otras veces a propósito (por ejemplo insertándole humor y cosas por el estilo).',
        60,
        4
    ),
    (
        2,
        2,
        1,
        '2',
        'Arroz con carne al jugo',
        'hecho Es un establecido hace demasiado tiempo que un lector se distraerá con el contenido del texto de un sitio mientras que mira su diseño. El punto de usar Lorem Ipsum es que tiene una',
        'hecho Es un establecido hace demasiado tiempo que un lector se distraerá con el contenido del texto de un sitio mientras que mira su diseño. El punto de usar Lorem Ipsum es que tiene una distribución más o menos normal de las letras, al contrario de usar textos como por ejemplo "Contenido aquí, contenido aquí"',
        'hecho Es un establecido hace demasiado tiempo que un lector se distraerá con el contenido del texto de un sitio mientras que mira su diseño. El punto de usar Lorem Ipsum es que tiene una distribución más o menos normal de las letras, al contrario de usar textos como por ejemplo "Contenido aquí, contenido aquí". Estos textos hacen parecerlo un español que se puede leer. Muchos paquetes de autoedición y editores de páginas web usan el Lorem Ipsum como su texto por defecto, y al hacer una búsqueda de "Lorem Ipsum" va a dar por resultado muchos sitios web que usan este texto si se encuentran en estado de desarrollo. Muchas versiones han evolucionado a través de los años, algunas veces por accidente, otras veces a propósito (por ejemplo insertándole humor y cosas por el estilo).',
        50,
        4
    ),
    (
        3,
        1,
        1,
        '3',
        'Puré de zanahorias',
        'Es un hecho establecido hace demasiado tiempo que un lector se distraerá con el contenido del texto de un sitio mientras que mira su diseño. El punto de usar Lorem Ipsum es que tiene una',
        'Es un hecho establecido hace demasiado tiempo que un lector se distraerá con el contenido del texto de un sitio mientras que mira su diseño. El punto de usar Lorem Ipsum es que tiene una distribución más o menos normal de las letras, al contrario de usar textos como por ejemplo "Contenido aquí, contenido aquí"',
        'Es un hecho establecido hace demasiado tiempo que un lector se distraerá con el contenido del texto de un sitio mientras que mira su diseño. El punto de usar Lorem Ipsum es que tiene una distribución más o menos normal de las letras, al contrario de usar textos como por ejemplo "Contenido aquí, contenido aquí". Estos textos hacen parecerlo un español que se puede leer. Muchos paquetes de autoedición y editores de páginas web usan el Lorem Ipsum como su texto por defecto, y al hacer una búsqueda de "Lorem Ipsum" va a dar por resultado muchos sitios web que usan este texto si se encuentran en estado de desarrollo. Muchas versiones han evolucionado a través de los años, algunas veces por accidente, otras veces a propósito (por ejemplo insertándole humor y cosas por el estilo).',
        20,
        4
    ),
    (
        4,
        1,
        3,
        '4',
        'Huevos a la parmesana',
        'Es un hecho establecido hace demasiado tiempo que un lector se distraerá con el contenido del texto de un sitio mientras que mira su diseño. El punto de usar Lorem Ipsum es que tiene una',
        'Es un hecho establecido hace demasiado tiempo que un lector se distraerá con el contenido del texto de un sitio mientras que mira su diseño. El punto de usar Lorem Ipsum es que tiene una distribución más o menos normal de las letras, al contrario de usar textos como por ejemplo "Contenido aquí, contenido aquí"',
        'Es un hecho establecido hace demasiado tiempo que un lector se distraerá con el contenido del texto de un sitio mientras que mira su diseño. El punto de usar Lorem Ipsum es que tiene una distribución más o menos normal de las letras, al contrario de usar textos como por ejemplo "Contenido aquí, contenido aquí". Estos textos hacen parecerlo un español que se puede leer. Muchos paquetes de autoedición y editores de páginas web usan el Lorem Ipsum como su texto por defecto, y al hacer una búsqueda de "Lorem Ipsum" va a dar por resultado muchos sitios web que usan este texto si se encuentran en estado de desarrollo. Muchas versiones han evolucionado a través de los años, algunas veces por accidente, otras veces a propósito (por ejemplo insertándole humor y cosas por el estilo).',
        50,
        4
    );

INSERT INTO photos (recipe_id, url) VALUES
    (1, 'img/default/porotos.jpg'),
    (2, 'img/default/arroz.jpg'),
    (3, 'img/default/pure.jpg'),
    (4, 'img/default/huevos.jpg');