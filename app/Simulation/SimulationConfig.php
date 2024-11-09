<?php

namespace App\Simulation;

class SimulationConfig
{
    /**
     * Probability (%) of system failure during simulation
     */
    public const FAILURE_PROBABILITY = 15;

    /**
     * Common failure scenarios with error codes
     */
    public const FAILURE_SCENARIOS = [
        'E001' => [
            'Sensor communication failure',
            'Temperature sensor disconnected',
            'Primary sensor malfunction',
        ],
        'E002' => [
            'Power supply interruption',
            'Voltage regulation failure',
            'Power circuit malfunction',
        ],
        'E003' => [
            'System calibration error',
            'Configuration mismatch',
            'Initialization failure',
        ],
        'E004' => [
            'Memory allocation error',
            'Buffer overflow detected',
            'Stack corruption',
        ],
        'E005' => [
            'Network connectivity lost',
            'Communication timeout',
            'Protocol mismatch',
        ],
    ];

    /**
     * Available sensor configurations
     */
    public const SENSOR_TYPES = [
        'temperature' => [
            'unit' => '°C',
            'min' => -10,
            'max' => 120,
            'names' => [
                'Core Temperature Sensor',
                'Ambient Temperature Sensor',
                'Cooling System Temperature',
                'Motor Temperature Sensor',
            ],
        ],
        'pressure' => [
            'unit' => 'bar',
            'min' => 0,
            'max' => 10,
            'names' => [
                'System Pressure Sensor',
                'Hydraulic Pressure Sensor',
                'Coolant Pressure Sensor',
                'Oil Pressure Sensor',
            ],
        ],
        'rotation' => [
            'unit' => 'rpm',
            'min' => 0,
            'max' => 5000,
            'names' => [
                'Main Motor RPM',
                'Fan Speed Sensor',
                'Turbine Speed Sensor',
                'Pump Rotation Sensor',
            ],
        ],
        'power' => [
            'unit' => 'kW',
            'min' => 0,
            'max' => 500,
            'names' => [
                'Power Consumption Sensor',
                'Output Power Sensor',
                'Energy Usage Monitor',
                'Power Load Sensor',
            ],
        ],
        'utilization' => [
            'unit' => '%',
            'min' => 0,
            'max' => 100,
            'names' => [
                'CPU Usage Sensor',
                'Memory Usage Sensor',
                'Capacity Usage Sensor',
                'Efficiency Monitor',
            ],
        ],
    ];
}
