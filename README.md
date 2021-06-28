# recruitment# RECRUITMENT


## Tecnologías  🚀

**Laravel** 7




### Pre-requisitos 📋

_Que cosas necesitas para instalar el software y como instalarlas_

**Composer** 1.9

**Laravel** 7

**PHP** 7.2 minímo

### Instalación 🔧

_Pasos para la instalación_


_Clonar repositorio_
```
https://github.com/gilson97cm/recruitment.git
```
_Instalar carpeta vendor_
```
composer install
```
_Paquetes externos_

```
composer require tymon/jwt-auth
```

## Iniciar el proyecto ⚙️

_Pasos para iniciar la base del proyecto_
_Agregar el servicio en el array de  providers en config/app.php_ 
```
'providers' => [
   ...
    Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
]
```
```
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
```
_Agregar el middleware en App\Http\Kernel.php_
```
 protected $routeMiddleware = [
    ...
      'jwtAuth' =>  \Tymon\JWTAuth\Http\Middleware\Authenticate::class,
  ];
```
```
php artisan jwt:secret
```
```
php artisan migrate
```
```
php artisan serve
```

## Endpoints 📋

_Listado de endpoints para el usuario y sus campos requeridos_
```
POST => '/login'  {"email":"","password":""}
GET  => '/logout'  {"token":""}  
POST => '/users' {"email":"","password":"", "active":""}
PUT => '/users' {"email":"","password":"", "active":""}
DELETE => '/users/{id}'
```
_Listado de endpoints para el contcato
```
GET => '/contacts' 
GET => '/contacts/{id}/edit' 
POST => '/contacts'
PUT => '/contacts'
DELETE => '/contacts/{id}'
```


