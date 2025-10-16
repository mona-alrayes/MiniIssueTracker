<?php 

namespace App\Enums;

enum PriorityType: string 
{
    case HIGHEST = 'highest';
    case HIGH = 'high';
    case MEDIUM = 'medium';
    case LOW = 'low';
    case LOWEST = 'lowest';
}