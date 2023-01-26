<?php

namespace Qebol\Config\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CheckPasswordRepetition implements Rule
{
    private $passwordPolicy;
    private $new_password;
    private $error_message = '';
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($policy, $password)
    {
        $this->passwordPolicy = $policy;
        $this->new_password = $password;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $allowed_repetitions = $this->passwordPolicy ? $this->passwordPolicy->dont_repeat_last_n_passwords : 1;

        $new_hash = Hash::make($this->new_password);

        $last_n_passwords = DB::table('password_changes')
            ->where('user_id', request()->user()->id)
            ->latest()
            ->limit($allowed_repetitions)
            ->pluck('hash');

        if ($last_n_passwords->has($new_hash)) {
            $this->error_message = "New password must not be among the last $allowed_repetitions password changes";
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->error_message;
    }
}
