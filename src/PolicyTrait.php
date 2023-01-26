<?php

namespace Qebol\Config;

use Illuminate\Support\Facades\DB;

trait PolicyTrait
{
    public function setPasswordAttribute($value)
    {
        if ($this->password_expired) {
            $this->save([
                'policy_status' => 'E',
                'password_expired' => false
            ]);
        }

        DB::table('password_changes')->insert([
           'user_id' => $this->id,
           'hash' => $value
        ]);
    }
}