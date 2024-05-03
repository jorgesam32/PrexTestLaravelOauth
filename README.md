# PrexTestLaravelOauth
![Logo](https://laravel.com/img/logomark.min.svg)
API REST desarrollado con php 8.2, framework Laravel 10 y base de datos MySQL.

La API permite la obtención de información de imágenes Gif, extraidas desde 
la base de datos de Ghipy por medio de integración con su API. 

Las busquedas de los Gifs se haran por query o por id de una imagen en específico. 

Se agrega además servicio que permite el almacenamiento de imágenes favoritas para un usuario especifico.

El uso de los servicios estará validado con uso de token bearer oauth2.0, generadon con uso de Laravel/passport. El mismo token será devuelto por la función de login posterior a validación de usuario (email, password).

Se incluye para su despliegue archivo DOCKERFILE.

Se incluye para las pruebas archivo de colección de POSTMAN para ejecución de todos los request posibles de realizar en la API. 

### Diagrama de caso de uso:
![use case](https://github.com/jorgesam32/PrexTestLaravelOauth/assets/73084137/4939f818-df1a-4181-8c43-698f79548029)




### Diagrama de entidad relación:
![der](https://github.com/jorgesam32/PrexTestLaravelOauth/assets/73084137/a5e4ad18-e851-4b76-824e-9f05f0534457)

### Diagrama de secuencias:
![Secuence](https://github.com/jorgesam32/PrexTestLaravelOauth/assets/73084137/41b08825-c2a4-4ded-bf1d-0f8e45bce0b8)

# Requerimientos:
Contar con MySQL versión 8 instalado.

Contar con servidor Apache o Nginx.

PHP versión 8.2 o superior.

Laravel Version 10.

Composer.

Artisan.

# Instalación:
Clonar este repositorio y modificar el archivo .env para establecer las configuraciones de las variables de entorno.

Contando con todos los requerimientos o utilizando el archivo DOCKERFILE dentro de este repositorio ejecutar los siguientes comandos en una terminal.
$ cd "path_del_repositorio_clonado"

Para ubicarse en la ruta donde se ubica el proyecto

$ composer install

Para instalar las dependencias de Laravel.

$ composer require laravel/passport

$ php artisan migrate

$ php artisan passport:install
Este comando creará las claves de encriptación necesarias para generar tokens de acceso seguros. Además, el comando creará clientes de "acceso personal" y "concesión de contraseña" que serán utilizados para generar tokens de acceso.

# Rutas y Pruebas

Ejecute en la terminal el siguiente comando para iniciar el servior web:

$ php artisan serve

Estarán disponibles para su uso las siguientes rutas y servicios de la API:

Registro de Usuarios: 
curl --header "Content-Type: application/json" \
  --request POST \
  --data '{
    "name" : "user1",
    "email" : "user@correo.com",
    "password" : "user1"
}' \
  http://127.0.0.1:8000/api/auth/signUp

Login de usuario:
curl --header "Content-Type: application/json" \
  --request POST \
  --data '{
    "email" : "user@correo.com",
    "password" : "user1"
}' \
  http://127.0.0.1:8000/api/auth/login

  Solicitar Gif por query:
  curl --header "Content-Type: application/json" \
  --request GET \
  --header 'Authorization: Bearer **********************' \
  --data '{
    "QUERY": "footer", "LIMIT": 5, "OFFSET": 0
}' \
  http://127.0.0.1:8000/api/search

  Solicitar Gif por ID:
  curl --header "Content-Type: application/json" \
  --request GET \
  --header 'Authorization: Bearer **********************' \
  --data '{
    "ID": "cizpdnZvFTGSQp12YN"
}' \
  http://127.0.0.1:8000/api/searchById

Registrar GIF favorito por usuario:
  curl --header "Content-Type: application/json" \
  --request POST \
  --header 'Authorization: Bearer **********************' \
  --data '{
    "GIF_ID" : 1,
    "ALIAS" : "Footer",
    "USER_ID" : 1
}' \
  http://127.0.0.1:8000/api/registerFavorites


  ### Routes de la API:
  
![routes](https://github.com/jorgesam32/PrexTestLaravelOauth/assets/73084137/937b0405-85ae-4e60-88b8-4432a7b4f101)


Se puede utilizar el archivo de colección de POSTMAN incluido en el repositorio para ejecutar pruebas sobre todos los servicios.

![colección](https://github.com/jorgesam32/PrexTestLaravelOauth/assets/73084137/fa24a182-ffca-4d1f-b433-c0daef132ea1)




