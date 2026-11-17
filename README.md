# SEITELCOM – Backend del Sistema de Facturación y Pagos

## Descripción del proyecto

SEITELCOM es un sistema web desarrollado para facilitar la gestión de facturas y pagos de una empresa de telecomunicaciones que ofrece servicios de internet y televisión.

El proyecto busca organizar la información de los clientes, los servicios contratados, las facturas generadas y los pagos realizados, permitiendo llevar un mejor control de los procesos administrativos.

Este repositorio contiene el backend del sistema, desarrollado en PHP y conectado a una base de datos MySQL. Su función principal es procesar las solicitudes realizadas desde el frontend y proporcionar la información necesaria para el funcionamiento de la plataforma.

## Tecnologías utilizadas

- **PHP:** desarrollo de la lógica del backend.
- **MySQL:** almacenamiento y administración de datos.
- **XAMPP:** entorno local para ejecutar Apache y MySQL.
- **phpMyAdmin:** administración de la base de datos.
- **JSON:** intercambio de información entre el backend y el frontend.
- **Git y GitHub:** control de versiones y publicación del proyecto.

## Estructura del proyecto

El backend está organizado en tres carpetas principales:

- **Controladores:** reciben las solicitudes del frontend y ejecutan las operaciones correspondientes.
- **Modelos:** contienen las consultas y operaciones realizadas sobre la base de datos.
- **Database:** contiene el archivo SQL utilizado para reconstruir la base de datos del proyecto.

## Funcionalidades principales

El backend permite realizar las siguientes operaciones:

1. Consultar información de los clientes registrados.
2. Consultar los servicios contratados por cada cliente.
3. Consultar, registrar y actualizar facturas.
4. Anular facturas cuando corresponda.
5. Consultar los pagos registrados.
6. Consultar el historial de pagos.
7. Consultar información de las plataformas de pago.
8. Gestionar información relacionada con usuarios y notificaciones.

## Base de datos

El sistema utiliza una base de datos relacional denominada `seitelcom_facturacion`, compuesta por nueve tablas:

- `cliente`
- `cliente_servicio`
- `factura`
- `historial_pagos`
- `notificacion`
- `pago`
- `plataforma_pago`
- `servicio`
- `usuario`

Estas tablas permiten relacionar los clientes con sus servicios, facturas y pagos, facilitando el almacenamiento y la consulta de información.

El archivo de exportación SQL se encuentra en:

`Database/seitelcom_facturacion.sql`

## Instalación y ejecución

Para ejecutar el backend de manera local, se deben realizar los siguientes pasos:

1. Instalar XAMPP en el computador.
2. Iniciar los servicios Apache y MySQL desde el panel de XAMPP.
3. Descargar o clonar este repositorio.
4. Ubicar la carpeta `Backend` dentro de `C:\xampp\htdocs\SEITELCOM`.
5. Ingresar a phpMyAdmin desde `http://localhost/phpmyadmin`.
6. Crear una base de datos llamada `seitelcom_facturacion`.
7. Importar el archivo `Database/seitelcom_facturacion.sql`.
8. Revisar la configuración de conexión en `Modelos/conexion.php`.
9. Iniciar el frontend de Angular para interactuar con el sistema.

## Ejemplo de consulta

Con Apache y MySQL funcionando, es posible consultar las facturas desde la siguiente dirección:

`http://localhost/SEITELCOM/Backend/Controladores/facturas.php?control=consulta`

Esta solicitud devuelve información de las facturas registradas en formato JSON.

## Relación con el frontend

El backend se comunica con una aplicación desarrollada en Angular, que presenta la información mediante una interfaz web adaptable a computadores y dispositivos móviles.

El frontend permite visualizar los módulos administrativos de clientes, facturas, pagos, reportes, notificaciones, usuarios y configuración.

Repositorio del frontend:

https://github.com/cuervon929-coder/Frontend_SEITELCOM

## Objetivo académico

Este proyecto fue desarrollado como parte del proceso de formación en desarrollo de software del SENA, aplicando conocimientos relacionados con programación web, bases de datos relacionales, arquitectura de software, diseño de interfaces y control de versiones.

SEITELCOM representa una propuesta tecnológica orientada a mejorar la organización y el seguimiento de los procesos de facturación y pagos de una empresa de telecomunicaciones.
