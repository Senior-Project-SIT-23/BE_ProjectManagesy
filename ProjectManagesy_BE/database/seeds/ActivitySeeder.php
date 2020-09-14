<?php

use App\Model\Activity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $activity = (array)array(
            [
                'activity_id' => '1',
                'activity_name' => 'Wip Camp10',
                'activity_year' => '2018',
                'activity_major' => 'IT',
            ],
            [
                'activity_id' => '2',
                'activity_name' => 'Wip Camp11',
                'activity_year' => '2019',
                'activity_major' => 'IT',
            ]
        );

        foreach ($activity as $values) {
            $temp_activity = new Activity();
            $temp_activity->activity_id = Arr::get($values,'activity_id');
            $temp_activity->activity_name = Arr::get($values,'activity_name');
            $temp_activity->activity_year = Arr::get($values,'activity_year');
            $temp_activity->activity_major = Arr::get($values,'activity_major');
            $temp_activity->save();
        }
    }
}
