<?

class DB {

	public $_db = null;

	public static function connect($dsn) {
		$inst = new DB();
		$inst->_db = @pg_connect($dsn);
		if ($inst->_db) {
			return $inst;
		}
		return FALSE;
	}

	function escapeValue($value) {
		return str_replace("'", "''", $value);
	}

	function bindVariables($query, $parms) {
		$numParms = count($parms);
		$numPlaceholders = substr_count($query, '?');
		if ($numParms != $numPlaceholders) {
			// this is a hack. we should be consistent about either throwing exceptions, or returning null values
			throw new Exception("Parameter count doesn't match: Expected ${numPlaceholders}, provided ${numParms}");
		}
		if ($numParms == 0) {
			return $query;
		}
		$pos = 0;
		foreach ($parms as $parm) {
			if (is_array($parm)) {
				$escaped = array();
				foreach ($parm as $item) {
					$escaped[] = "'" . $this->escapeValue($item) . "'";
				}
				$val = join(", ", $escaped); 
			}
			else {
				$val = "'" . $this->escapeValue($parm) . "'";
			}
			$pos = strpos($query, '?', $last_pos);
			$query = substr_replace($query, $val, $pos, 1);
			$last_pos = $pos + strlen($val);
		}
		return $query;
	}

	public function query($query, $parms=null) {
		if (!$this->_db) {
			return null;
		}
		#print($this->bindVariables($query, $parms));
		$rid = pg_exec($this->_db, $this->bindVariables($query, $parms));
		if (!$rid) {
			return $rid;
		}
		$numrows = @pg_numrows($rid);
		$result = array();
		for ($i=0; $i<$numrows; $i++) {
			$row = pg_fetch_array($rid, $i, PGSQL_ASSOC);
			$result[] = $row;
		}
		@pg_freeresult($rid);
		return $result;
	}

	public function insert($query, $parms=null) {
		return $this->query($query, $parms);
	}

	// Returns a map where one of each result row's named columns 
	// is used as key, mapped to an array to all rows that contain
	// this key.
	// This is a great way to load a data set where tables have an n:m
	// relationship with only two queries.
	public function getMap($keyColumn, $query, $parms=null) {
		if (!$this->_db) {
			return null;
		}
		#print($this->bindVariables($query, $parms));
		$rid = pg_exec($this->_db, $this->bindVariables($query, $parms));
		if (!$rid) {
			return $rid;
		}
		$numrows = @pg_numrows($rid);
		$result = array();
		for ($i=0; $i<$numrows; $i++) {
			$row = pg_fetch_array($rid, $i, PGSQL_ASSOC);
			if (count($row) > 0) {
				$key = $row[$keyColumn];
				if (!in_array($key, $result)) {
					$result[$key] = array();
				}
				$result[$key][] = $row;
			}
		}
		@pg_freeresult($rid);
		return $result;
	}
	
	// Just so I don't have to remember the actual name.
	function getHash($keyColumn, $query, $parms=null) {
		return $this->getMap($keyColumn, $query, $parms);
	}

	// Returns a string: first element of first row
	public function getOne($query, $parms=null) {
		if (!$this->_db) {
			return null;
		}
		#print($this->bindVariables($query, $parms));
		$rid = pg_exec($this->_db, $this->bindVariables($query, $parms));
		if (!$rid) {
			return $rid;
		}
		$row = pg_fetch_row($rid);
		$result = null;
		if (count($row) > 0) {
			$result = $row[0];
		}
		@pg_freeresult($rid);
		return $result;
	}

	// Returns an array: first element of every row
	public function getList($query, $parms=null) {
		if (!$this->_db) {
			return null;
		}
		#print($this->bindVariables($query, $parms));
		$rid = pg_exec($this->_db, $this->bindVariables($query, $parms));
		if (!$rid) {
			return $rid;
		}
		$numrows = @pg_numrows($rid);
		$result = array();
		for ($i=0; $i<$numrows; $i++) {
			$row = pg_fetch_row($rid, $i);
			if (count($row) > 0) {
				$result[] = $row[0];
			}
		}
		@pg_freeresult($rid);
		return $result;
	}

	public function getError() {
		return pg_errormessage($this->_db);
	}

	public function close() {
		if (!$this->_db) {
			return null;
		}
		return pg_close($this->_db);
	}
}

?>
