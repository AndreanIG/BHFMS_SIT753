<?php

namespace App\Console\Commands;

use App\Models\TenantBoarding;
use Illuminate\Console\Command;

class UpdateRentStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rent:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Status Updater for Rent Status that wants to checkout';

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
     * @return int
     */
    public function handle()
    {
        $rows = TenantBoarding::where('tenant_status', '==', 'approved')->get();

        foreach ($rows as $row) {
            if ($row->end_date < now()) {
                $row->update(['tenant_status' => 'checkout']);
            }
        }
    }
}
