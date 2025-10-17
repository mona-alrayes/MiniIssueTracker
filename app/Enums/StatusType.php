<?php 

namespace App\Enums;

enum StatusType: string 
{
    case Open = 'open';
    case Inprogress = 'in_progress';
    case Blocked = 'blocked';
    case Completed = 'completed';
}