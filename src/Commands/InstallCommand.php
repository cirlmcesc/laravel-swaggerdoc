<?php

namespace Cirlmcesc\LaravelSwaggerdoc\Commands;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swaggerdoc:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run the commands necessary to prepare Geevalidate for use';

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
        $this->call('vendor:publish', [
            "--tag" => "swaggerdoc-config",
            "--force",
        ]);

        $this->call('vendor:publish', [
            "--tag" => "swaggerdoc-resources",
            "--force",
        ]);
    }
}
