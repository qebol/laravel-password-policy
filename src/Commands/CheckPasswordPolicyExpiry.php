<?php

namespace Qebol\Config\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CheckPasswordPolicyExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'password-expiry:checker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks expired passwords and locks user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $password_policy = DB::table('password_policy')->first();
        $expiry_days = $password_policy->password_expires_n_days;

        $today = now();


        User::oldest()->chunk(100, function ($users) use ($expiry_days, $today) {
            foreach ($users as $user) {
                $last_password_change = DB::table('password_changes')->where('user_id', $user->id)->first();

                $last_password_date = is_null($last_password_change) ? $user->created_at : $last_password_change->created_at;

                // check validity
                $date = Carbon::parse($last_password_date);

                $diff = $date->diffInDays($today);

                if ($diff > $expiry_days) {
                    $user->update([
                        'password_expired' => true,
                        'policy_status' => 'L'
                    ]);
                }

            }
        });
        return Command::SUCCESS;
    }
}
