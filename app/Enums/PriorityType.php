<?php 

namespace App\Enums;

enum PriorityType: string 
{
    case Highest = 'highest';
    case High = 'high';
    case Medium = 'medium';
    case Low = 'low';
    case Lowest = 'lowest';
}