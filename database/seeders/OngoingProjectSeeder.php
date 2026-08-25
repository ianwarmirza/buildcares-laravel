<?php

namespace Database\Seeders;

use App\Models\OngoingProject;
use Illuminate\Database\Seeder;

class OngoingProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'site_address' => '42 High Street, Oxford, UK',
                'proposal' => 'Double Storey Rear Extension & Loft Conversion',
                'status' => 'In Progress',
                'sort_order' => 1,
            ],
            [
                'site_address' => '15 Station Road, Reading, UK',
                'proposal' => 'Single Storey Wrap-Around Kitchen Extension',
                'status' => 'Planning Submission',
                'sort_order' => 2,
            ],
            [
                'site_address' => '88 Park Lane, Sutton, London, UK',
                'proposal' => 'Dormer Loft Conversion & Internal Alterations',
                'status' => 'Building Control Review',
                'sort_order' => 3,
            ],
            [
                'site_address' => '24 Church Street, Cambridge, UK',
                'proposal' => 'Garage Conversion to Home Office & Gym Annex',
                'status' => 'In Progress',
                'sort_order' => 4,
            ],
            [
                'site_address' => '7 Victoria Road, Slough, UK',
                'proposal' => 'Hip-to-Gable Roof Extension & Skylights',
                'status' => 'Drawing Finalisation',
                'sort_order' => 5,
            ],
            [
                'site_address' => '102 Green Lane, Croydon, London, UK',
                'proposal' => 'New Build 4-Bedroom Detached House CAD Package',
                'status' => 'Planning Ready',
                'sort_order' => 6,
            ],
            [
                'site_address' => '31 Mill Road, St Albans, UK',
                'proposal' => 'Outbuilding Garden Studio & Permitted Development Set',
                'status' => 'In Progress',
                'sort_order' => 7,
            ],
            [
                'site_address' => '59 Kingsway, Brighton, UK',
                'proposal' => 'Structural Internal Wall Removal & Open Plan Living',
                'status' => 'Building Control Approval',
                'sort_order' => 8,
            ],
            [
                'site_address' => '18 Windsor Avenue, Maidenhead, UK',
                'proposal' => 'First Floor Side Extension & Porch Alterations',
                'status' => 'In Progress',
                'sort_order' => 9,
            ],
            [
                'site_address' => '94 Manor Road, Richmond, London, UK',
                'proposal' => 'Basement Conversion & Structural General Notes',
                'status' => 'Drafting Phase',
                'sort_order' => 10,
            ],
        ];

        foreach ($projects as $proj) {
            OngoingProject::firstOrCreate(
                ['site_address' => $proj['site_address']],
                $proj
            );
        }
    }
}
