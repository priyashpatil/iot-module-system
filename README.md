# IOT Module System

This document provides an overview and implementation details of the IoT Module System.

## Local Development

### Requirements

- PHP 8.3
- Composer 2.7
- Node 21
- MySQL 8.0

### Getting Started

1. Clone the repository
2. Install dependencies with `composer install` and `npm install`
3. Copy `.env.example` to `.env` and update the database credentials
4. Run `php artisan key:generate` to generate an application key
5. Run `php artisan migrate --seed` to create the database schema and seed it with initial data
6. Run `composer run dev` to start server, worker, pail and vite to build assets
7. Optionally to run the module simulator, run `php artisan modules:simulate`

### Module Simulation

The system includes a module simulator for testing and development purposes. To run the simulator:

```bash
# Simulate all modules with 5 second interval (default)
php artisan modules:simulate

# Simulate specific modules
php artisan modules:simulate --modules=1 --modules=2

# Change simulation interval (in seconds)
php artisan modules:simulate --interval=10
```

The simulator will generate sensor readings and potential failures that are processed by the system's job queues. Press Ctrl+C to stop the simulation.
