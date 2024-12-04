<?php
namespace Hamidin\SimpleCrypt\Console;

use Illuminate\Console\Command;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class SimpleCryptCreateYmlCommand extends Command
{

    /**
     * The name and signature of the console command
     * 
     * @var string
    */

    protected $signature = "simplecrypt:create-yml 
        {filename : The Filename} 
        {--dir= : The directory to scan} 
        {--extensions= : List of Extensions separated by semicolon} 
        {--result_dir= : The Result Directory of Encrypted Files} 
        {--recipients= : List of Recipients separated by semicolon}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simple Crypt Command Create YML';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Get Data from Parameter
        $filename = $this->argument('filename').".yaml";
        $array = [
            'setting' => 
            [
                'dir' => $this->option('dir'),
                'extensions' => $this->option('extensions'),
                'result_dir' => $this->option('result_dir'),
                'recipients' => $this->option('recipients'),
            ],
        ];
        
        $yaml = Yaml::dump($array,2,2);
        Storage::put($filename, $yaml);
    }
}