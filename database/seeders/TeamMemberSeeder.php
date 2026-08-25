<?php

namespace Database\Seeders;

use App\Models\TeamMember;
use Illuminate\Database\Seeder;

class TeamMemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            [
                'name'       => 'M Tauseeq Nasir ACIAT',
                'role'       => 'FOUNDER & ARCHITECTURAL TECHNOLOGIST',
                'bio'        => 'Founder of ArckiDraw Ltd, Muhammad Tauseeq Nasir is an Architectural Technologist with 9+ years of experience specialising in UK planning, building regulations, and residential projects. He has extensive experience working with architects, developers, and homeowners, delivering accurate and high-quality technical drawings.',
                'sort_order' => 1,
                'is_active'  => true,
            ],
            [
                'name'       => 'Muhammad Anwar Mirza',
                'role'       => 'CO-FOUNDER & ARCHITECT',
                'bio'        => 'Muhammad Anwar is a graduate of the University of Gujrat and a Co-founder of Arckidraw. He has extensive experience in preparing Planning and Building Regulations drawings for UK-based residential projects. As one of the founding members of Arckidraw, Anwar has played an important role in developing technical quality.',
                'sort_order' => 2,
                'is_active'  => true,
            ],
            [
                'name'       => 'Iqra Shehzadi',
                'role'       => 'JUNIOR ARCHITECT',
                'bio'        => 'Iqra joined ArckiDraw Ltd in 2020 after completing her Bachelor of Architecture (B.Arch). She specialises in planning drawings and has developed strong experience in preparing detailed drawings for residential extensions and loft conversions. Working closely under the guidance of senior architects.',
                'sort_order' => 3,
                'is_active'  => true,
            ],
            [
                'name'       => 'M Ali',
                'role'       => 'SENIOR CGI ARTIST',
                'bio'        => 'M. Ali is a highly experienced CGI Artist with more than 15 years of experience in creating high-end, photorealistic renders and animations for both residential and commercial projects. He has strong expertise in architectural visualisation and interior design, with extensive experience across UK and UAE projects.',
                'sort_order' => 4,
                'is_active'  => true,
            ],
            [
                'name'       => 'Ubaid Mirza',
                'role'       => 'TRAINEE ARCHITECTURAL DRAFTSMAN',
                'bio'        => 'After completing college, Ubaid joined ArckiDraw as a Trainee Architectural Draftsman. He primarily works with AutoCAD and supports the team in preparing accurate existing-condition drawings based on survey notes and site measurements. Ubaid is developing his technical drafting skills.',
                'sort_order' => 5,
                'is_active'  => true,
            ],
        ];

        foreach ($members as $data) {
            TeamMember::firstOrCreate(
                ['name' => $data['name']],
                $data
            );
        }
    }
}
