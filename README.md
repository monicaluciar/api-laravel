# API REST con Laravel


# Resumen

## Introduction

Una api para consultar y registrar clientes por regiones y comunas de Chile

### **Conventions**

La URL base para enviar los API request es `https://localhost/api`

La API sigue las convenciones que sean posibles. Las solicitudes se realizan mediante los metodos `GET`, `POST` and `DELETE`. Las solicitudes y respuestas estan codificadas en formato JSON.


# Requerimientos

- Sistema operativo Linux, MacOS o Windows (mediante WSL)
- Docker Engine instalado en su sistema ( Si utliza windows debe instala Docker Desktop)

# Instalacion

1. Clonar el repositorio
2. Crear y configurar el archivo enviroment `.env`
3. En el directorio de la aplicaion ejecute el siguiente comando

```
./vendor/bin/sail up
```
Esto creara los contenedores para su aplicacion

<aside>
Opcionalmente, en lugar de escribir repetidamente `vendor/bin/sail` para ejecutar los comandos de `Sail`, puede que desee configurar un alias de shell que le permita ejecutar los comandos de Sail más fácilmente:

```
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
``` 

Para asegurarte de que esto está siempre disponible, puedes añadirlo al archivo de configuración de tu shell en tu directorio personal, como `~/.zshrc` o `~/.bashrc`, y luego reiniciar tu shell.

Una vez configurado el alias de shell, puede ejecutar comandos Sail simplemente escribiendo sail. El resto de los ejemplos de esta documentación asumirán que has configurado este alias:

```
sail up
```
</aside>
4. Generar una key
```sail artisan key:generate
```

# Migraciones

Para ejecutar las migraciones de base de datos ejecute el comando:
```
    sail artisan migrate
```

# Sedeers

Ejecute los siguientes comandos para crear la data de regiones y comunas para los clientes en la base de datos

```
    sail artisan db:seed --class:RegionsTableSedeeer
    sail artisan db:seed --class:CommunesTableSedeer
```

## SUPPORTED ENDPOINTS

| HTTP method | Endpoint |
| --- | --- |
| GET | Lista de clientes |
| GET | Obtener la informacion de un cliente |
| DELETE | Eliminar un cliente |
| POST | Registrar un usuario |
| POST | Iniciar sesion para obtener un token |


## Status Codes

HTTP response codes are used to indicate general classes of success and error. 

### Success Code

| HTTP Status Quote | Description |
| --- | --- |
| 200 | Successfully processed request. |

### Error Codes

Error responses contain more detail about the error in the response body, in the `"code"` and `"message"`properties.

| HTTP Status Quote | code | message |
| --- | --- | --- |
| 400 | invalid_json | The request body could not be decoded as JSON |
|  | invalid_request_url | This request URL is not valid. |
|  | invalid_request | This request is not supported. |
| 401 | unauthorized | The bearer token is not valid. |



# Endpoints

## Open Endpoints

Endpoints que no requieren autenticacion

# Registrar un nuevo usuario

Category: Authentication
Description: Crear un nuevo usuario para utilizar el servicio
Type: POST
URL: https://localhost/api/auth/register

### Headers request

| Key| Value | 
| --- | --- | 
| x-api-key  | API_KEY
| accept| application\json |
|  |  |  

### Body Params

| Name | Type | Required? | Description |
| --- | --- | --- | --- |
| email | string | True |  |
| password | string | True |  |
|  |  |  |  |

### Responses

**Status 200**
```{
    "success": true,
    "message": "Usario creado exitosamente"
}
```
**Status 422**
```{
    "success": false,
    "error": {}
}
```

# Iniciar sesion

Category: Authentication
Description: Crear un nuevo usuario para utilizar el servicio
Type: POST
URL: https://localhost/api/auth/register

### Headers request

| Key| Value | 
| --- | --- | 
| x-api-key  | API_KEY
| accept| application\json |
|  |  |  

### Body Params

| Name | Type | Required? | Description |
| --- | --- | --- | --- |
| email | string | True |  |
| password | string | True |  |
|  |  |  |  |

### Responses

**Status 200**
```{
    "success": true,
    "token": TOKEN
}
```
**Status 401**
```{
    "success": false,
    "error": "No autorizado"
    }
```
**Status 422**
```{
    "success": false,
    "error": {}
}
```

## Endpoints que requieren autenticación

Los endpoints cerrados requieren que se incluya un Token válido en la cabecera de la
solicitud. Se puede adquirir un token desde el endpoint de inicio de sesión anterior.

# Consultar clients

Category: Cliente
Description: Crear un nuevo usuario para consultar los clientes
Type: GET
URL: https://localhost/api/customers

### Authorization

Bearer Token

### Headers request

| Key| Value | 
| --- | --- | 
| x-api-key  | API_KEY
| accept| application\json |
|  |  |  


### Responses

**Status 200**

```
  // example data
  {
    "success": true,
    "data": [
        {
            "name": "Vivienne Klocko",
            "last_name": "Von",
            "address": "92386 Boehm Turnpike Apt. 972\nPort Josefachester, RI 21811",
            "commune": "Huechuraba",
            "region": "Región Metropolitana"
        },
        {
            "name": "Marion Wiza",
            "last_name": "Carroll",
            "address": "721 Cierra Throughway Apt. 144\nAliyahfort, AK 16713",
            "commune": "Huechuraba",
            "region": "Región Metropolitana"
        }
    ]
}
```
**Status 401**
```{
    "message": "Unauthenticated."
}
```

# Crear un cliente

Category: Cliente
Description: Crear un cliente
Type: POST
URL: https://localhost/api/customer

### Authorization

Bearer Token

### Headers request

| Key| Value | 
| --- | --- | 
| x-api-key  | API_KEY
| accept| application\json |
|  |  |  

### Body Params

| Name | Type | Required? | Description |
| --- | --- | --- | --- |
| dni| string | true |  |
| name| string | true|  |
|  last_name|  string| true |  |
|  email| string(email)  | true |  |
|  address| string | false |  |
|  region|string  | true |  |
|  comuna| string |  true|  |

### Responses

**Status 200**
```{
    "success": true,
}
```
**Status 401**
```{
    "success": false,
    "error": "No autorizado"
    }
```
**Status 422**
```{
    "success": false,
    "error": {}
}
```

# Eliminar un cliente

Category: Cliente
Description: Eliminar un cliente por dni o email
Type: DELETE
URL: https://localhost/api/customer

### Authorization

Bearer Token

### Headers request

| Key| Value | 
| --- | --- | 
| x-api-key  | API_KEY
| accept| application\json |
|  |  |  

### Body Params

| Name | Type | Required? | Description |
| --- | --- | --- | --- |
| dni| string | false |  |
|  email| string(email)  | true |  |


### Responses

**Status 200**
```{
    "success": true,
}
```
**Status 401**
```{
    "success": false,
    "error": "No autorizado"
    }
```
**Status 404**
```{
    "success": false,
    "error": "El registro no existe"
}
```