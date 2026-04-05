# IP Address Management System

## Project Setup Guide

Follow the steps below to run the project locally using Docker.

---

## Prerequisites

Make sure you have the following installed:

- Docker
- Docker Compose
- Git

---

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/fredwen91/ip-address-management-system.git
cd ip-address-management-system
```

---

### 2. Build and start containers

```bash
docker-compose up -d --build
```

---

### 3. Run database migrations and seeders

#### Auth Service (with seed data)

```bash
docker-compose exec auth-service php artisan migrate --seed
```

#### IP Management Service

```bash
docker-compose exec ip-management-service php artisan migrate
```

---

## Access the Application

Open your browser and go to:

```
http://localhost:5173/
```

---

## Services Overview

- **Frontend** – Runs on Vite (Port: 5173)
- **Gateway Service** – API Gateway
- **Auth Service** – Handles authentication and users
- **IP Management Service** – Manages IP address data

---

## Common Commands

### Restart containers

```bash
docker-compose restart
```

### Stop containers

```bash
docker-compose down
```

### Rebuild containers

```bash
docker-compose up -d --build
```

### Access container shell

```bash
docker-compose exec <service-name> bash
```

---

## Notes

- Ensure ports `5173` and `8000` are not in use.
- If you encounter issues, try rebuilding the containers.
- Database migrations must be run after containers are up.

---

## Author

- Wenfred Edradan

---
