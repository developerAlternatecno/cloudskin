**CLOUDSKIN** has received funding from the European Union’s Horizon research and innovation programme under grant agreement No 101092646

# Cloudskin - Backend

Este proyecto utiliza Laravel Backpack como framework de administración, con MySQL como sistema de gestión de base de datos y Nginx como servidor web, todo dentro de contenedores Docker para facilitar el despliegue y la gestión del entorno de desarrollo.

## Prerrequisitos

Antes de iniciar, asegúrate de tener instalado lo siguiente en tu sistema:

- Docker y Docker Compose
- dos2unix (Windows/Linux/Mac)
- expect (Linux/Mac)

## Configuración del Proyecto

### Para Usuarios de Windows

Es necesario utilizar WSL (Windows Subsystem for Linux) para ejecutar comandos Unix.

1. **Conversión de Scripts**: Si encuentras errores en el script de configuración, convierte los saltos de línea ejecutando en PowerShell:

    ```wsl dos2unix ./docker-setup.sh```

2. **Permisos de Ejecución**: Otorga permisos de ejecución al script antes de ejecutarlo:
    
    ```wsl chmod +x ./docker-setup.sh```

3. **Ejecución del Script de Configuración**: Para levantar el contenedor de Docker con todos los archivos necesarios, ejecuta:

    ```wsl ./docker-setup.sh```

### Para Usuarios de Linux/Mac

1. **Conversión de Scripts**: Convierte los saltos de línea del script `docker-setup.sh` si es necesario:

    ```dos2unix ./docker-setup.sh```

2. **Permisos de Ejecución**: Otorga permisos de ejecución al script antes de ejecutarlo:
    
    ```chmod +x ./docker-setup.sh```

3. **Ejecución del Script de Configuración**: Inicia el contenedor de Docker ejecutando:

    ```./docker-setup.sh```

El proceso puede tardar aproximadamente 10 minutos.

## Explicación del Script `docker-setup.sh`

Este script automatiza la configuración del proyecto:

- **Detención ante errores**: `set -e` detiene el script si ocurre un error.
- **Copiar archivos `.env`**: Copia `.env.example` a `.env` si no existen, en el directorio actual y en `app/`.
- **Construcción de imágenes Docker**: `docker-compose up --build -d` construye y levanta los contenedores en segundo plano.
- **Instalación de dependencias**: Instala dependencias de PHP con Composer y de NPM.
- **Configuración de permisos**: Otorga permisos de escritura a `storage` y `bootstrap`.
- **Configuración de Laravel**: Genera claves, ejecuta migraciones y crea enlaces simbólicos.
- **Instalación de Laravel Backpack y Passport**: Instala y configura paquetes necesarios para Laravel Backpack y Laravel Passport.

## Información Adicional

- **Nginx**: Configura Nginx según las necesidades del proyecto, incluyendo servidor, ubicaciones y seguridad.
- **MySQL**: Asegúrate de configurar adecuadamente las credenciales y conexiones en `.env`.
- **Laravel Backpack**: Facilita la gestión de la aplicación Laravel, mejorando la administración de usuarios, roles y permisos.

Asegúrate de revisar y ajustar los archivos de configuración según las necesidades de tu proyecto y entorno de desarrollo.
