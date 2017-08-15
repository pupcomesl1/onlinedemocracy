<?php

namespace App\Console\Commands;

use App\Badge;
use App\User;
use Illuminate\Console\Command;

class ProcessBadges extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process-badges';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nightly task to process badges';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $badgeTypes = getBadges();
        $users = User::all();
        $badges = Badge::all();
        $bar = $this->output->createProgressBar(count($badgeTypes));
        foreach ($badgeTypes as $key => $badge) {
            $func = @$badge['scheduledTask'];
            $once = @$badge['once'];
            if ($func != null) {
                foreach ($users as $user) {
                    if ($once && $badges->where('user_id', $user->id)->where('type', $key)->isNotEmpty()) {
                        continue;
                    }
                    $func($user->id);
                }
            }
            $bar->advance();
        }
        $bar->finish();
    }
}
