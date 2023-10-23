<?php

namespace Binafy\LaravelUserMonitoring\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PruneUserMonitoringRecords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'laravel-user-monitoring:prune-records';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prune user monitoring records';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        foreach ([
            'action',
            'authentication',
            'visit',
        ] as $monitor) {
            $this->pruneRecords($monitor);
        }

        return Command::SUCCESS;
    }

    private function getDeleteDays(string $monitor): int
    {
        if (null === $deleteDays = config('user-monitoring.' . $monitor .'_monitoring.delete_days')) {
            $deleteDays = (int) config('user-monitoring.delete_days');
        }

        return $deleteDays;
    }

    private function pruneRecords(
        string $monitor
    ): void {
        $this->output->writeln(sprintf('Begin pruning %s records', $monitor));

        $deleteDays = $this->getDeleteDays($monitor);

        if (0 === $deleteDays) {
            $this->comment(sprintf(
                'Delete days set to 0, skipping pruning of %s records',
                $monitor
            ));

            return;
        }

        $this->output->writeln(sprintf(
            'Pruning %s days of %s records',
            $deleteDays,
            $monitor
        ));

        $date = now()->subDays($deleteDays)->startOfDay();
        $table = config('user-monitoring.' . $monitor . '_monitoring.table');

        $nbDeleted = DB::table($table)
            ->where('created_at', '<', $date->format('Y-m-d H:i:s'))
            ->delete();

        $this->info(sprintf(
            '%d %s records have been pruned!',
            $nbDeleted,
            $monitor
        ));
    }
}
