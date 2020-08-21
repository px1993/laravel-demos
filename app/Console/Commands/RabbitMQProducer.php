<?php

namespace App\Console\Commands;

use Service\RabbitMQ\ProducerMQService;
use Illuminate\Console\Command;

class RabbitMQProducer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:RabbitMQProducer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'RabbitMQProducer';

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
        $producer = new ProducerMQService();
        $producer->run();
    }
}
