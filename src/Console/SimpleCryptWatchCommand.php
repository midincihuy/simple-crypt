<?php
namespace Hamidin\SimpleCrypt\Console;

use Illuminate\Console\Command;

class SimpleCryptWatchCommand extends Command
{

    /**
     * The name and signature of the console command
     * 
     * @var string
    */

    protected $signature = "simplecrypt:watch {filename}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simple Crypt Command Watch';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $fileContents = <<< 'EOT'
        #!/bin/sh;
        EOT;

        $written = \Storage::put($this->argument('filename').'.sh', $fileContents);
    }
}