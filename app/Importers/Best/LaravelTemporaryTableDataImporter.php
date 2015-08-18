<?php namespace App\Importers\Best;

use DB;
use Schema;
use Topor\Best\LaravelDataImporter;

abstract class LaravelTemporaryTableDataImporter extends LaravelDataImporter {

	function __construct($use_temp = true) {
		$this->using_temp = $use_temp;
		if ($this->using_temp) {
			$temp_table = DB::insert(DB::raw("CREATE TABLE IF NOT EXISTS $this->temp_table LIKE $this->table;"));
			$truncate_table = DB::insert(DB::raw("TRUNCATE TABLE $this->temp_table;"));

			if (!$temp_table || !$truncate_table) {
				throw new Exception("Can not create or truncate $this->temp_table temporary table", 1);
			}
		}
	}

	public function swapTempAndMainTables() {
		if ($this->using_temp && Schema::hasTable($this->table) && Schema::hasTable($this->temp_table)) {
			Schema::dropIfExists($this->table . '_old');
			Schema::rename($this->table, $this->table . '_old');
			Schema::rename($this->temp_table, $this->table);
		}
	}

	function getTableName() {
		if ($this->using_temp) {
			return $this->temp_table;
		} else {
			return $this->table;
		}
	}
}
