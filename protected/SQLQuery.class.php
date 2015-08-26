<?php
class SQLQuery {
	protected $_dbHandle;
	protected $_result;
	
	/** Connects to database **/
	
	function connect($address, $account, $pwd, $name) {
		$this->_dbHandle = @mysqli_connect($address, $account, $pwd, $name);
		if (mysqli_connect_errno()) {
			return 0;
		} else {
			return 1;
		}
	}
	
	/** Disconnects from database **/
	
	function disconnect() {
		if (@mysqli_close($this->_dbHandle)) {
			return 1;
		}  else {
			return 0;
		}
	}
	
	function findAll() {
		$query = 'select * from `'.$this->_table.'`';
		return $this->query($query);
	}
	
	function find($id) {
		$query = 'select * from `'.$this->_table.'` where `id` = \''.$this->_dbHandle->real_escape_string($id).'\'';
		return $this->query($query, 1);
	}
	
	/** Custom SQL Query **/
	
	function query($query, $singleResult = 0) {
		$this->_result = mysqli_query($this->_dbHandle, $query);
		$result = [];
		$table = [];
		$field = [];
		$tempResults = [];
		$numOfFields = $this->_dbHandle->field_count;
		if (preg_match("/select/i",$query)) {
			for ($i = 0; $i < $numOfFields; ++$i) {
				$info = $this->_result->fetch_field_direct($i);
				array_push($table, $info->table);
				array_push($field, $info->name);
			}
			while ($row = mysqli_fetch_row($this->_result)) {
				for ($i = 0;$i < $numOfFields; ++$i) {
					$tempResults[$table[$i]][$field[$i]] = $row[$i];
				}
				if ($singleResult == 1) {
					mysqli_free_result($this->_result);
					return $tempResults;
				}
				array_push($result,$tempResults);
			}	
			mysqli_free_result($this->_result);
			return($result);
		}
	}
	
	/** Get number of rows **/
	function getNumRows() {
		return mysqli_num_rows($this->_result);
	}
	
	/** Free resources allocated by a query **/
	function freeResult() {
		mysqli_free_result($this->_result);
	}
	
	/** Get error string **/
	function getError() {
		return mysqli_error($this->_dbHandle);
	}
	
}