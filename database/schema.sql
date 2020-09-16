DROP DATABASE IF EXISTS recetas;

CREATE DATABASE recetas;

USE recetas;

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




INSERT INTO users (name, email, password) VALUES 
    ('Admin Admin', 'admin@admin.com', '$2y$10$PmLZkKoPL8TGTHatixfac.OZvrISWVd0aBGK80r0vH72nMQn9h11W') /* password: admin */,
    ('User User', 'user@user.com', '$2y$10$5qLzfwGXLZM6LOQolLYaAur43mjzMSGaNeqhM1nMhLr59VEQJivJK' /* password: user */);

INSERT INTO types (name, url, image, description) VALUES
    ('Pollo', 'pollo', 'https://t2.rg.ltmcdn.com/es/images/4/9/6/muslos_de_pollo_a_la_naranja_71694_orig.jpg', 'Las mejores recetas de cocina para la familia'),
    ('Tallarines', 'tallarines', 'https://cocina-casera.com/wp-content/uploads/2018/04/tallarines-rojos.jpg', 'Las mejores recetas de cocina para la familia'),
    ('Postres', 'postres', 'https://hips.hearstapps.com/hmg-prod.s3.amazonaws.com/images/copas-de-cheesecake-1557229055.jpg', 'Las mejores recetas de cocina para la familia'),
    ('Mariscos', 'mariscos', 'https://dam.cocinafacil.com.mx/wp-content/uploads/2014/03/Parrillada-de-mariscos.jpg', 'Las mejores recetas de cocina para la familia'),
    ('Guisos', 'guisos', 'https://www.chilerecetas.cl/images/fotos/guiso_de_zapallito_italiano.jpg', 'Las mejores recetas de cocina para la familia'),
    ('Carnes', 'carnes', 'https://cdn2.cocinadelirante.com/sites/default/files/images/2019/04/como-sauvizar-carnes.jpg', 'Las mejores recetas de cocina para la familia'),
    ('Tortillas', 'tortillas', 'https://locosxlaparrilla.com/wp-content/uploads/2015/02/Receta-recetas-locos-x-la-parrilla-locosxlaparrilla-receta-tortilla-acelga-tortilla-acelga-tortilla-receta-tortilla-2.jpg', 'Las mejores recetas de cocina para la familia'),
    ('Arroz', 'arroz', 'https://somoscocineros.com/assets/uploads/recipes/63/receta-facil-y-rapida-de-arroz-blanco.png', 'Las mejores recetas de cocina para la familia'),
    ('Ensaladas', 'ensaladas', 'https://www.superama.com.mx/views/micrositio/recetas/images/comidasaludable/ensaladamixta/Web_fotoreceta.jpg', 'Las mejores recetas de cocina para la familia'),
    ('Aperitivos', 'aperitivos', 'https://www.recetasderechupete.com/wp-content/uploads/2019/12/Entrantes-navide%C3%B1os-525x360.jpg', 'Las mejores recetas de cocina para la familia');

INSERT INTO recipes (user_id, type_id, url, title, description, ingredients, steps, duration, persons) VALUES 
    (
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