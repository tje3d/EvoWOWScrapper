<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Facades\App\ResponseParser;

class Achievement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrap:achievements';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get acheivement name + icon';

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
        $client  = new \GuzzleHttp\Client;
        $ids     = explode("\n", \File::get(database_path('dbc/achievements.txt')));
        $success = [];
        $bar     = $this->output->createProgressBar(count($ids));

        foreach ($ids as $id) {
            try {
                $result = (string) $client->get("https://wotlk.evowow.com/?achievement={$id}&power=true")
                    ->getBody();
                $bar->advance();
            } catch (\Throwable $e) {
                $this->error("Failed to retrieve $id");
                continue;
            }

            $success[$id] = ResponseParser::achievement($result);
        }

        $bar->finish();

        $this->info("Done");

        \Storage::put('success.txt', json_encode($success));
    }
}
