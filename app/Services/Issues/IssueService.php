<?php

namespace App\Services\Issues;

class IssueService {

    public function CreateIssue(array $data){

        return DB::transctions(function () use ($data , $creator){
            
        });

    }
}