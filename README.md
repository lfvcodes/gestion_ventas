<a href="https://skillicons.dev">
     <img src="https://skillicons.dev/icons?i=html,css,bootstrap,js,jquery,php,mysql&perline=15" />
   </a>

##

# Boom Solutions - Sistema de Gestión de Ventas / Sales Management System

![Logo Boom Solutions](assets/img/logo.png)

Este proyecto es una solución desarrollada como prueba técnica para la empresa **Boom Solutions**. Consiste en un sistema de gestión de ventas construido con **PHP** y **MySQL**.

This project is a solution developed as a technical test for **Boom Solutions**. It is a sales management system built with **PHP** and **MySQL**.

## Características principales / Main Features

- Gestión de usuarios, clientes, productos, categorías, vendedores y ventas.
- Reportes en PDF.
- Interfaz web moderna y responsiva.

- User, client, product, category, seller, and sales management.
- PDF reports.
- Modern and responsive web interface.

## Configuración y primeros pasos / Setup and Getting Started

1. **Clonar o copiar el proyecto** en su servidor local (por ejemplo, AppServ, XAMPP, WAMP, etc).
   **Clone or copy the project** to your local server (e.g., AppServ, XAMPP, WAMP, etc).
2. **Importar la base de datos**:
   - Ubique el archivo `bd/gestion_ventas.sql`.
   - Importe este archivo en su servidor MySQL (por ejemplo, usando phpMyAdmin).
     **Import the database:**
   - Locate the file `bd/gestion_ventas.sql`.
   - Import this file into your MySQL server (e.g., using phpMyAdmin).
3. **Configurar la conexión a la base de datos**:
   - Copie el archivo `.env.example` y renómbrelo a `.env`.
   - Edite el archivo `.env` y coloque los datos de conexión de su base de datos (`DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`).
     **Configure the database connection:**
   - Copy the `.env.example` file and rename it to `.env`.
   - Edit the `.env` file and set your database connection data (`DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`).
4. **Acceso al sistema**:
   - Usuario de prueba: `admin`
   - Contraseña: `admin*`
     **System access:**
   - Test user: `admin`
   - Password: `admin*`
5. **Abrir el sistema**:
   - Ingrese a `index.php` desde su navegador web.
     **Open the system:**
   - Go to `index.php` from your web browser.

---

> **Nota:** Ahora la configuración de la base de datos se realiza desde el archivo `.env`. No es necesario modificar archivos PHP.
> **Note:** Now the database configuration is done from the `.env` file. No need to edit PHP files.

---

© lfvcodes & Boom Solutions - Prueba Técnica PHP & MySQL
