<?php

namespace App\Console\Commands;

use App\Jobs\CheckBirthdayReminders as JobsCheckBirthdayReminders;
use Illuminate\Console\Command;

class CheckBirthdayReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:check-birthday-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks birthday reminders and sends email notifications';

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
     * @return mixed
     */
    public function handle()
    {
        JobsCheckBirthdayReminders::dispatch();
    }
}
