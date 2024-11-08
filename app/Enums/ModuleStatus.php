<?php

namespace App\Enums;

enum ModuleStatus: string
{
    case OPERATIONAL = 'operational';
    case MALFUNCTION = 'malfunction';
    case DEACTIVATED = 'deactivated';
}
