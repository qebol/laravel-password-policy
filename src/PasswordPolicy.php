<?php

namespace Qebol\Config;

use Illuminate\Support\Facades\DB;

trait PasswordPolicy
{
    public function setPasswordAttribute($value)
    {
        if ($this->password_expired) {
            $this->update([
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