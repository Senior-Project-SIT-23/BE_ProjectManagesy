<?php

namespace App\Repositories;


// use App\Model\Activity;
// use App\Model\ActivityFile;

use App\Model\Staff;
use Illuminate\Notifications\Action;
use User;

class LoginRepository implements LoginRepositoryInterface
{
    public function createStaff($data)
    {
        $staff = Staff::where('user_id', $data['user_id'])->first();
        if ($staff) {
            Staff::where('user_id', $data['user_id'])
                ->update([
                    'user_name_th' => $data['name_th'],
                    'user_name_en' => $data['name_en'],
                    'user_email' => $data['email'],
                ]);
        } else {
            $user = new Staff;
            $user->user_id = $data['user_id'];
            $user->user_name_th = $data['name_th'];
            $user->user_name_en = $data['name_en'];
            $user->user_email = $data['email'];
            $user->save();
        }
    }
}
