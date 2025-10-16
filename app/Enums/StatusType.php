<?php 

namespace App\Enums;

enum StatusType: string 
{
    case OPEN = 'open';
    case INPROGRESS = 'in_progress';
    case BLOCKED = 'blocked';
    case COMPLETED = 'completed';
}