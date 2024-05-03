# PrexTestLaravelOauth
API REST desarrollado con php 8.2, frameworkLaravel 10 y base de datos MySQL.

La API permite la obtención de información de imágenes Gif, extraidas desde 
la base de datos de Ghipy por medio de integración con su API. 

Las busquedas de los Gifs se haran por query o por id de una imagen en específico. 

Se agrega además servicio que permite el almacenamiento de imágenes favoritas para un usuario especifico.

El uso de los servicios estará validado con uso de token bearer oauth2.0, generadon con uso de Laravel/passport. El mismo token será devuelto por la función de login posterior a validación de usuario (email, password).

Se incluye para su despliegue archivo DOCKERFILE.

Se incluye para las pruebas archivo de colección de POSTMAN para ejecución de todos los request posibles de realizar en la API. 
