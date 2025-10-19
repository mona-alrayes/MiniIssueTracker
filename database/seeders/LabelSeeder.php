<?php

namespace Database\Seeders;

use App\Models\Label;
use Illuminate\Database\Seeder;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $labels = [
            ['name' => 'bug', 'color' => '#d73a4a'],
            ['name' => 'feature', 'color' => '#0075ca'],
            ['name' => 'enhancement', 'color' => '#a2eeef'],
            ['name' => 'documentation', 'color' => '#0075ca'],
            ['name' => 'urgent', 'color' => '#d93f0b'],
            ['name' => 'help wanted', 'color' => '#008672'],
            ['name' => 'good first issue', 'color' => '#7057ff'],
            ['name' => 'duplicate', 'color' => '#cfd3d7'],
            ['name' => 'wontfix', 'color' => '#ffffff'],
            ['name' => 'security', 'color' => '#ee0701'],
            ['name' => 'performance', 'color' => '#fbca04'],
            ['name' => 'ui/ux', 'color' => '#c5def5'],
            ['name' => 'backend', 'color' => '#5319e7'],
            ['name' => 'frontend', 'color' => '#1d76db'],
            ['name' => 'database', 'color' => '#006b75'],
        ];

        foreach ($labels as $label) {
            Label::create($label);
        }
    }
}
