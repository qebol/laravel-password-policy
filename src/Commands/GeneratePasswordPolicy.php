<?php

namespace Qebol\Config\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GeneratePasswordPolicy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'password-policy:config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Config / updates a password policy';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $min = intval($this->ask('Set minimum password length', 8));

        if ($min < 8 || $min > 20) {
            $this->error('Restrict password minimum to a value between 8 - 20 characters long');
            return 0;
        }

        $max = intval($this->ask('Set maximum password length', 20));
        if ($max < 8 || $max > 30) {
            $this->error('Restrict password maximum to a value between 8 - 20 characters longs');
            return 0;
        }

        if ($min > $max) {
            $this->error('Minimum value must not exceed Maximum value');
            return 0;
        }

        $mixture = strtolower($this->choice('Must a password have a mixture of Uppercase and lowercase letters?', ['yes', 'no'], 'no'));
        if (in_array($mixture, ['yes', 'no'])) {
            $this->error('Please enter a yes or no!');
            return 0;
        }

        $uncompromised = strtolower($this->choice('Check password against compromised databases?', ['yes', 'no'], 'yes'));
        if (in_array($uncompromised, ['yes', 'no'])) {
            $this->error('Please enter a yes or no!');
            return 0;
        }

        $symbols = strtolower($this->choice('Password must have a symbol?', ['yes', 'no'], 'no'));
        if (in_array($symbols, ['yes', 'no'])) {
            $this->error('Please enter a yes or no!');
            return 0;
        }


        $numbers = strtolower($this->choice('Password must have a number?', ['yes', 'no'], 'no'));
        if (in_array($numbers, ['yes', 'no'])) {
            $this->error('Please enter a yes or no!');
            return 0;
        }

        $letters = strtolower($this->choice('Password must have a letter?', ['yes', 'no'], 'no'));
        if (in_array($letters, ['yes', 'no'])) {
            $this->error('Please enter a yes or no!');
            return 0;
        }

        $reuse = intval($this->ask('How many last password should the user not reuse?', 1));
        if ($reuse < 0) {
            $this->error('A positive number is required');
            return 0;
        }

        $expiry = intval($this->ask('How many days should a password expire?', 30));
        if ($expiry < 0) {
            $this->error('A positive number is required');
            return 0;
        }

        $policy = DB::table('password_policy')->first();

        $values = [
          'min' => $min,
          'max' => $max,
          'mixed_case' => $mixture == 'yes',
          'uncompromised' => $uncompromised == 'yes',
          'symbols' => $symbols == 'yes',
          'numbers' => $numbers == 'yes',
          'letters' => $letters == 'yes',
          'dont_repeat_last_n_passwords' => $reuse,
          'password_expires_n_days' => $expiry
        ];

        if (is_null($policy)) {
            DB::table('password_policy')->insert($values);
        } else {
           $policy->update($values);
        }

        return Command::SUCCESS;
    }
}
