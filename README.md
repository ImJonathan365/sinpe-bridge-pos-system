# SINPE Bridge POS System

Este proyecto es un sistema de Punto de Venta desarrollado con Laravel 12 y Filament 5. Su objetivo es registrar órdenes de compra y recibir confirmaciones automáticas de pago desde la SINPE Bridge API.

Este sistema forma parte de un proyecto académico del curso de **Ingeniería de Software**, cuyo propósito es simular una solución tecnológica que optimice el proceso de validación de pagos mediante SINPE Móvil, reduciendo fraudes, errores humanos y tiempos de espera en los comercios.

---

## Propósito del Sistema

El POS permite a los comercios:

* Registrar órdenes de venta.
* Consultar el estado de los pagos.
* Recibir confirmaciones automáticas desde la SINPE Bridge API.
* Gestionar transacciones de manera eficiente y segura.

Este sistema se integra con la **SINPE Bridge API**, la cual valida los comprobantes de pago y notifica al POS cuando una transacción ha sido aprobada.

---

## Requisitos

Antes de empezar, asegúrate de tener instalado lo siguiente:

* PHP 8.2 o superior
* Composer
* Laravel 12
* Node.js y npm
* Git

Opcional pero recomendado:

* Laravel Herd, XAMPP o Laragon

---

## Clonar el Proyecto

```bash
git clone https://github.com/ImJonathan365/sinpe-bridge-pos-system.git
cd sinpe-bridge-pos-system
code .
```

---

## Instalación y Ejecución en Linux

1. Instalar dependencias de PHP:

```bash
composer install
```

2. Instalar dependencias de Node.js:

```bash
npm install
```

3. Configurar variables de entorno:

```bash
cp .env.example .env
```

4. Generar la clave de la aplicación:

```bash
php artisan key:generate
```

5. Configurar la integración con la API en el archivo `.env`:

```env
SINPE_BRIDGE_API_URL=https://api.ejemplo.com
SINPE_BRIDGE_API_KEY=tu_api_key
SINPE_BRIDGE_API_SECRET=tu_api_secret
```

6. Compilar los recursos frontend:

```bash
npm run build
```

7. Crear un usuario administrador de Filament:

```bash
php artisan make:filament-user
```

8. Iniciar el servidor de desarrollo:

```bash
php artisan serve
```

---

## Instalación y Ejecución en Windows

1. Instalar dependencias:

```powershell
composer install
npm install
```

2. Configurar el entorno:

```powershell
copy .env.example .env
php artisan key:generate
```

3. Configurar la integración con la SINPE Bridge API en el archivo `.env`.

4. Compilar los recursos:

```powershell
npm run build
```

5. Crear el usuario administrador:

```powershell
php artisan make:filament-user
```

6. Iniciar la aplicación:

```powershell
php artisan serve
```

---

## Probar que Funciona

Con el servidor en ejecución, abre en tu navegador:

* Aplicación:
  `http://127.0.0.1:8000`

* Panel de administración (Filament):
  `http://127.0.0.1:8000/admin`

---

## Integración con SINPE Bridge API

El sistema POS recibe confirmaciones de pago desde la API mediante un endpoint REST.

---

## Funcionalidades Principales

* Registro de órdenes de pago.
* Consulta del estado de las órdenes.
* Integración con la SINPE Bridge API.
* Confirmación automática de pagos.
* Gestión de órdenes pendientes, pagadas y vencidas.
* Panel administrativo con Filament.

---

## Dependencias Principales

El proyecto utiliza, entre otras:

* `laravel/framework`
* `filament/filament`
* `livewire/livewire`
* `spatie/laravel-permission` (opcional)

Todas las dependencias se encuentran definidas en `composer.json`.

---

## Flujo Simplificado del Sistema

1. El cajero registra una orden en el POS.
2. El cliente realiza el pago mediante SINPE Móvil.
3. La SINPE Bridge API valida el comprobante.
4. La API notifica al POS.
5. El POS marca la orden como **Pagada**.
6. El cajero entrega el producto al cliente.

---

## Estados de las Órdenes

| Estado       | Descripción                       |
| ------------ | --------------------------------- |
| Pending      | Orden creada y en espera de pago. |
| Paid         | Pago confirmado correctamente.    |
| Expired      | Orden vencida.                    |
| Rejected     | Pago inválido.                    |
| Under Review | En revisión manual.               |

---

## Proyecto Académico

Desarrollado para el curso:

**Ingeniería de Software (IF-7100)**
**Universidad de Costa Rica**
Carrera de Informática Empresarial

---

## Licencia

Este proyecto es de carácter académico y se desarrolla únicamente con fines educativos.
