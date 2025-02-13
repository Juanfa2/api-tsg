## Instalacion de proyecto

### Instalacion de dependencias
~~~
composer install
~~~

### Crear el archivo ***.env*** a partir de ***.env.example***

### Migraciones y seed
Carga las tablas en la base de datos
~~~
php artisan migrate
~~~

Carga el usuario administrador
~~~
php artisan db:seed
~~~


### Secret key para app y jwt

App laravel
~~~
php artisan key:generate
~~~

tymon/jwt
~~~
php artisan jwt:secret
~~~

Con estas configuraciones e importando la api documentation ***TSG.postman_collection_documentation.json*** de postman se puede usar la api

Cualquier duda o consulta

Email: **juanfrancisco.m.sanchez@gmail.com**
