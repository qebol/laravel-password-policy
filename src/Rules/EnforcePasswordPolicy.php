<?php

namespace Qebol\Config\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class EnforcePasswordPolicy implements Rule
{
    private $errorMessage = '';
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $rules = [ $attribute => [
            'required',
            ne
        ]];

        $input = [$attribute => $value];

        $policy = DB::table('password_policy')->first();

        $passwordPolicy = Password::default();

        if ($policy) {
            if ($policy->min > 0) {
                $passwordPolicy = $passwordPolicy->min($policy->min);
            }

            if ($policy->mixed_caxe) {
                $passwordPolicy = $passwordPolicy->mixedCase();
            }

            if ($policy->letters) {
                $passwordPolicy = $passwordPolicy->letters();
            }

            if ($policy->numbers) {
                $passwordPolicy = $passwordPolicy->numbers();
            }

            if ($policy->symbols) {
                $passwordPolicy = $passwordPolicy->symbols();
            }

            if ($policy->uncompromised) {
                $passwordPolicy = $passwordPolicy->uncompromised();
            }

            if ($policy->dont_repeat_last_n_passwords) {
                $passwordPolicy = $passwordPolicy->uncompromised();
            }
        }


        $validator = Validator::make($input, [
            $attribute => [ 'required', new CheckPasswordRepetition($policy, $value), $passwordPolicy],
        ]);

        if ($validator->fails()) {
            $this->errorMessage = $validator->errors()->first();
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
        return $this->errorMessage;
    }
}
