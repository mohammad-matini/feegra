<?php namespace Feegra;

use GetOpt\Command;
use GetOpt\GetOpt;
use GetOpt\Operand;

class InitCommand extends Command {

    private $configs;
    private $fapi;
    private $db;

    public function __construct(Array $configs, FAPI $fapi, DB $db) {
        parent::__construct('init', [$this, 'handle']);

        $this->configs = $configs;
        $this->fapi = $fapi;
        $this->db = $db;
    }

    public function handle(GetOpt $getOpt) {
        echo "Creating Database Tables" . PHP_EOL;
        $this->db->init_missing_tables();
        $this->db->enable_sqlite_foreign_key_constraints();
        echo "Adding Script To Crontab" . PHP_EOL;
        $this->init_cron_tab();
    }

    private function init_cron_tab() {
        $script_executable = __DIR__ . "/feegra.php process";
        exec("(crontab -l 2> '/dev/null' ; echo '* * * * * $script_executable') | awk '!x[$0]++' | crontab -");
    }
}