<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmployeeClientVisit;
use App\Models\User;
use App\Models\Client;

class TestVisitJustification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:visit-justification {employeeId?} {clientId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test incomplete visit for justification testing';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $employeeId = $this->argument('employeeId');
        $clientId = $this->argument('clientId');

        // If no employee ID provided, get the first employee
        if (!$employeeId) {
            $employee = User::where('role', 'employee')->first();
            if (!$employee) {
                $this->error('No employee found in the database.');
                return 1;
            }
            $employeeId = $employee->id;
        }

        // If no client ID provided, get the first client
        if (!$clientId) {
            $client = Client::first();
            if (!$client) {
                $this->error('No client found in the database.');
                return 1;
            }
            $clientId = $client->id;
        }

        // Create a test incomplete visit
        $visit = EmployeeClientVisit::create([
            'employee_id' => $employeeId,
            'client_id' => $clientId,
            'day_of_week' => 'sunday',
            'year' => date('Y'),
            'week_number' => date('W'),
            'status' => 'unactive', // Mark as incomplete
        ]);

        $this->info("Test incomplete visit created successfully!");
        $this->info("Visit ID: " . $visit->id);
        $this->info("Employee ID: " . $visit->employee_id);
        $this->info("Client ID: " . $visit->client_id);
        $this->info("Status: " . $visit->status);

        return 0;
    }
}