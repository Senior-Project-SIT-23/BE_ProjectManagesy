<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use App\Model\Entrance;
use App\Model\RoundName;
use App\Model\Program;


class EntranceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $entrances = (array)array(
            [
                'entrance_id' => '1',
                'entrance_name' => 'TCAS',
                'entrance_year' => '2563',
            ],
            [
                'entrance_id' => '2',
                'entrance_name' => 'โครงการ 2B-KMUTT',
                'entrance_year' => '2563',
            ]
        );
        $rounds = (array)array(
            [
                'round_id' => '1',
                'round_name' => 'TCAS1',
                'entrance_id' => '1'
            ],
            [
                'round_id' => '2',
                'round_name' => 'TCAS2',
                'entrance_id' => '1'
            ],
            [
                'round_id' => '3',
                'round_name' => 'TCAS3',
                'entrance_id' => '1'
            ],
            [
                'round_id' => '4',
                'round_name' => 'TCAS4',
                'entrance_id' => '1'
            ],
            [
                'round_id' => '5',
                'round_name' => 'TCAS5',
                'entrance_id' => '1'
            ],
            [
                'round_id' => '6',
                'round_name' => 'รอบที่1',
                'entrance_id' => '2'
            ],
        );
        $programs = (array)array(
            [
                'program_id' => '1',
                'program_name' => 'โครงการ Active Recruitment',
                'round_id' => '1'
            ],
            [
                'program_id' => '2',
                'program_name' => 'โครงการรับนักศึกษาความสามารถพิเศษและทุนเพชรพระจอมเกล้า',
                'round_id' => '1'
            ],
            [
                'program_id' => '3',
                'program_name' => 'โครงการคัดเลือกตรงประเภทเรียนดี',
                'round_id' => '1'
            ],
            [
                'program_id' => '4',
                'program_name' => 'โครงการ Active Recruitment',
                'round_id' => '2'
            ],
            [
                'program_id' => '5',
                'program_name' => 'โครงการคัดเลือกตรงเพื่อผลิตบุคคลากรด้านวิทยศาสตร์เทคโนโลยีและนวัตกรรม',
                'round_id' => '2'
            ],
            [
                'program_id' => '6',
                'program_name' => 'โครงการคัดเลือกตรงโดยใช้คะแนน GAT/PAT เพื่อกระจายโอกาสทางการศึกษา (รับทุกเขต* ยกเว้นเขต 1 และกรุงเทพฯ)',
                'round_id' => '2'
            ],
            [
                'program_id' => '7',
                'program_name' => 'โครงการคัดเลือกตรงโดยใช้คะแนน GAT/PAT เพื่อกระจายโอกาสทางการศึกษา (รับนักเรียนเขต 1,3,5,9 และกรุงเทพฯ)',
                'round_id' => '2'
            ],
            [
                'program_id' => '8',
                'program_name' => 'โครงการรับนักเรียนจากมูลนิธิ สอวน.',
                'round_id' => '2'
            ],
            [
                'program_id' => '9',
                'program_name' => 'โครงการคัดเลือกตรง มจธ. รักษาธรรม เพิ่มโอกาสทางการศึกษา',
                'round_id' => '2'
            ],
            [
                'program_id' => '10',
                'program_name' => 'โครงการรับนักศึกษาพิการ',
                'round_id' => '2'
            ],
            [
                'program_id' => '11',
                'program_name' => 'โครงการรับนักเรียนโครงการ วมว.',
                'round_id' => '2'
            ],
            [
                'program_id' => '12',
                'program_name' => 'รับตรวร่วมกัน',
                'round_id' => '3'
            ],
            [
                'program_id' => '13',
                'program_name' => 'แอดมิชชั่นกลาง',
                'round_id' => '4'
            ],
            [
                'program_id' => '14',
                'program_name' => 'รับตรงอิสระ',
                'round_id' => '5'
            ],
            [
                'program_id' => '15',
                'program_name' => '2B-KMUTT',
                'round_id' => '6'
            ],
        );

        foreach ($entrances as $value) {
            $temp_entrance = new Entrance;
            $temp_entrance->entrance_id = Arr::get($value, 'entrance_id');
            $temp_entrance->entrance_name = Arr::get($value, 'entrance_name');
            $temp_entrance->entrance_year = Arr::get($value, 'entrance_year');
            $temp_entrance->save();
        }
        foreach ($rounds as $value) {
            $temp_round = new RoundName;
            $temp_round->round_id = Arr::get($value, 'round_id');
            $temp_round->round_name = Arr::get($value, 'round_name');
            $temp_round->entrance_id = Arr::get($value, 'entrance_id');
            $temp_round->save();
        }
        foreach ($programs as $value) {
            $temp_program = new Program;
            $temp_program->program_id = Arr::get($value, 'program_id');
            $temp_program->program_name = Arr::get($value, 'program_name');
            $temp_program->round_id = Arr::get($value, 'round_id');
            $temp_program->save();
        }
    }
}
