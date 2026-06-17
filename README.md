# Real Estate Tasks

A task and contact management tool for real estate agents, built with Laravel and Filament.

Each agent gets their own workspace to manage contacts and track the tasks tied to them, with a dashboard that surfaces key stats and outstanding work at a glance. An admin panel lets administrators manage agent accounts. The app ships with role-based panels (admin and agent), a Dockerized local environment, and an automated build-and-deploy pipeline.

- **Live site:** [real-estate-tasks.zayed-hassan.com](https://real-estate-tasks.zayed-hassan.com)
- **Release notes:** [docs/RELEASE_NOTE.md](docs/RELEASE_NOTE.md)

## Tech Stack

- PHP 8.4, Laravel 13
- Filament 5 (Livewire 4, Alpine.js, Tailwind CSS)
- MySQL 8, Redis
- Docker / Docker Compose

## Running Locally (Docker)

The project ships with a full Docker Compose stack (app, nginx, MySQL, Redis, Vite, Mailpit). You only need Docker installed.

1. Clone the repository:

   ```bash
   git clone https://github.com/z4yed/real-estate-tasks.git
   cd real-estate-tasks
   ```

2. Create your environment file:

   ```bash
   cp .env.example .env
   ```

   For the Docker stack, set the database host to the `mysql` service in `.env`:

   ```env
   DB_HOST=mysql
   DB_DATABASE=real_estate_tasks
   DB_USERNAME=real_estate_tasks
   DB_PASSWORD=password
   ```

3. Build and start the containers:

   ```bash
   docker compose up -d --build
   ```

4. Install dependencies and generate the app key:

   ```bash
   docker compose exec app composer install
   docker compose exec app php artisan key:generate
   ```

5. Run the migrations and seed the demo data:

   ```bash
   docker compose exec app php artisan migrate:fresh --seed --force
   ```

The app is now available at [http://localhost:8080](http://localhost:8080) (override the port with `APP_PORT` in `.env`).

### Panels & Demo Accounts

- Admin panel: [http://localhost:8080/admin](http://localhost:8080/admin)
- Agent panel: [http://localhost:8080/agent](http://localhost:8080/agent)

The seeder creates the following accounts (password is `password` for all):

| Role  | Email                | Panel |
| ----- | -------------------- | ----- |
| Admin | admin@example.com    | admin |
| Agent | agent1@example.com   | agent |
| Agent | agent2@example.com   | agent |

## Deployment

Deployment is automated via GitHub Actions: merging a pull request into `main` builds a Docker image, publishes it to the registry, and rolls it out to the server over SSH. See the full pipeline history at [github.com/z4yed/real-estate-tasks/actions](https://github.com/z4yed/real-estate-tasks/actions).

## Author

Developed by [Zayed Hassan](https://zayed-hassan.com).
