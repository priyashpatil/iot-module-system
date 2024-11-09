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

The simulation behavior is controlled by the `SimulationConfig` class. Key configuration parameters include:

- **Failure Probability**: 15% chance of module failure during simulation
- **Sensor Types**: Configurable sensor types including:
  - Temperature (-10°C to 120°C)
  - Pressure (0-10 bar)
  - Rotation (0-5000 rpm)
  - Power (0-500 kW)
  - Utilization (0-100%)

- **Failure Scenarios**: Pre-defined error scenarios categorized by error codes:
  - E001: Sensor communication failures
  - E002: Power-related failures
  - E003: System calibration errors
  - E004: Memory/buffer errors
  - E005: Network connectivity issues

To modify simulation parameters, update the constants in `app/Simulation/SimulationConfig.php`.

To run the simulator:

```bash
# Simulate all modules with 5 second interval (default)
php artisan modules:simulate

# Simulate specific modules
php artisan modules:simulate --modules=1 --modules=2

# Change simulation interval (in seconds)
php artisan modules:simulate --interval=10
```

The simulator will generate sensor readings and potential failures that are processed by the system's job queues. Press Ctrl+C to stop the simulation.
