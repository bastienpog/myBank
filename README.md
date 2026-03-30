# Project Name

> ⚠️ This README is a work in progress and will be updated as the project evolves.

## Stack

| Layer    | Technology                          |
|----------|-------------------------------------|
| Frontend | React 18 + Vite + TypeScript        |
| Backend  | PHP 8.3 + Symfony + FrankenPHP      |
| Database | MySQL 8.4                           |

## Prerequisites

- [Docker](https://www.docker.com/) >= 26
- [Docker Compose](https://docs.docker.com/compose/) >= 2.22

## Getting started

```bash
# 1. Clone the repository
git clone <repo-url>
cd <project-folder>

# 2. Set up environment variables
cp .env.example .env
# Edit .env and fill in the required secrets

# 3. Start the development environment
docker compose up --build

# 4. Open the app
#   Frontend  → http://localhost:5173
#   Backend   → http://localhost:8080
```

## Project structure

```
.
├── compose.yaml          # Docker Compose (dev)
├── compose.prod.yaml     # Docker Compose (prod overrides)
├── .env.example          # Environment variables template
├── frontend/             # React + Vite + TypeScript
│   ├── Dockerfile
│   └── src/
└── backend/              # Symfony + Doctrine
    ├── Dockerfile
    ├── docker/
    │   ├── entrypoint.sh
    │   └── php/
    └── src/
```

## Useful commands

```bash
# Run a Symfony console command
docker compose exec backend php bin/console <command>

# Run Composer
docker compose exec backend composer <command>

# Open a MySQL shell
docker compose exec db mysql -u app -p

# Tail logs
docker compose logs -f
```

## Environment variables

Copy `.env.example` to `.env` and fill in the values before starting the project.
See `.env.example` for the full list of required variables.
