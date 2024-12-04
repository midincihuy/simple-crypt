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
        parse_yaml() {
            local prefix=$2
            local s='[[:space:]]*' w='[a-zA-Z0-9_]*' fs=$(echo @|tr @ '\034')
            sed -ne "s|^\($s\)\($w\)$s:$s\"\(.*\)\"$s\$|\1$fs\2$fs\3|p" \
                    -e "s|^\($s\)\($w\)$s:$s\(.*\)$s\$|\1$fs\2$fs\3|p"  $1 |
            awk -F$fs '{
                indent = length($1)/2;
                vname[indent] = $2;
                for (i in vname) {if (i > indent) {delete vname[i]}}
                if (length($3) > 0) {
                    vn=""; for (i=0; i<indent; i++) {vn=(vn)(vname[i])("_")}
                    printf("%s%s%s=\"%s\"\n", "'$prefix'",vn, $2, $3);
                }
            }'
        }
        filename=$(basename "$0")
        eval $(parse_yaml ${filename%%.*}.yaml) # Fixed File Yaml

        dir=$setting_dir
        ext=$setting_extensions
        res_dir=$setting_result_dir

        IFS=';' read -r -a extension <<< "${setting_extensions}"
        LIST_EXTENSION=''
        if [ ${#extension[@]} -gt 1 ]; then
        LIST_EXTENSION='--include "'
        for x in "${extension[@]}"; do
            LIST_EXTENSION+=".*$x$|"
        done
        LIST_EXTENSION+='"'
        fi
        IFS=';' read -r -a recipient <<< "${setting_recipients}"
        LIST_RECIPIENT=''
        for r in "${recipient[@]}"; do
        LIST_RECIPIENT+="-r $r "
        done

        echo ${LIST_EXTENSION%?}

        # inotifywait -m $dir. -e create -e moved_to ${LIST_EXTENSION} |
        inotifywait -mr $dir. -e create -e moved_to ${LIST_EXTENSION%?} |
            while read -r directory action file; do
            if [ "$action" = "CREATE" ] || [ "$action" = "MOVED_TO" ] ; then
                echo $directory
                echo "file detected $file, start encrypting ..."
                gpg --output $res_dir${file}.gpg ${LIST_RECIPIENT} -se $directory$file 
                echo "Encrypted."
            fi
            done
        EOT;

        $written = \Storage::put($this->argument('filename').'.sh', $fileContents);
    }
}