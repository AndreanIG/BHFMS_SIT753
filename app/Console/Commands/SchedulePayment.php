<?php

namespace App\Console\Commands;

use App\Models\RentTransaction;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SchedulePayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:scheduler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scheduler to auto add new repeat payment';

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
        $currDate =  Carbon::now()->timezone('Asia/Jakarta');
        $previousDate = $currDate->startOfDay()->subDay();
        $nextMonthDate = clone $previousDate;
        $nextMonthDate->addMonth();
        $yesterdayTransactions = RentTransaction::where('payment_date',$previousDate->toDateString())
        ->where('repeat_payment',1)
        ->get();
        foreach ($yesterdayTransactions as $transaction) {
            RentTransaction::create([
                'tenant_boarding_id'=> $transaction->tenant_boarding_id,
                'transaction_type_id' => $transaction->transaction_type_id,
                'invoice_id' => $this->generateInvoice($nextMonthDate->toDateString()),
                'amount' => (floor($transaction->amount / 1000) * 1000)+  rand(0,1000),
                'payment_date' => $nextMonthDate,
                'repeat_payment'=> true,
            ]);
        }
    }
}
