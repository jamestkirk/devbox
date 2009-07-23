<?php defined('SYSPATH') or die('No direct script access.');
 
/*
This is a work in progress.
A helper model that all models should extend.
All models should extend this.
Example:
class Nav_Model extends Dbobject_Model {
	public function __construct()
	{
		parent::__construct('nav', 'id');
	}
}

*/

class Dbobject_Model extends Model {
	public $table = '';
	public $primary_key = 'id';
	public $fields = array();

	public function __construct($table = '', $primary_key = '', $fields = '')
	{
		parent::__construct();
		$this->table = $table;
		$this->primary_key = $primary_key;
		$this->fields = $fields;
	}

	public function insert($insert = array())
	{
		return $this->db->insert($this->table, $insert);
	}

	public function update($update = array(), $id = 0)
	{
		return $this->db->update($this->table, $update, array($this->primary_key => $id));
	}

	public function count($where = array())
	{
		return $this->db->where($where)->count_records($this->table);
	}

	public function get($where = array(), $orderby = array(), $items_per_page = null, $offset = null)
	{
		if ($this->fields)
			$this->db->select($this->fields);

		if (is_numeric($where))
			return $this->db->from($this->table)->where($this->primary_key, $where)->get()->current();

		$records = array();
		$this->db->where($where);
		$this->db->from($this->table);

		if ($orderby == 'RAND()')
			$this->db->orderby(null, 'RAND()');
		elseif ($orderby)
			$this->db->orderby($orderby);

		if ($items_per_page)
			$this->db->limit($items_per_page, $offset);

		return $this->db->get();
	}
	
	public function delete($id = 0)
	{
		$where = is_array($id) ? $id : array($this->primary_key => $id);
		return $this->db->delete($this->table, $where);
	}

	public function insert_id()
	{
		return $this->db->insert_id();
	}
	
	public function query($method, $value = array(), $orderby = array(), $items_per_page = null, $offset = null)
	{
		if (is_array($method))
		{
			foreach ($method as $k => $v)
				$this->db->$v($value[$k]);
		}
		else
			$this->db->$method($value);

		$this->db->from($this->table);

		if ($orderby == 'RAND()')
			$this->db->orderby(null, 'RAND()');
		elseif ($orderby)
			$this->db->orderby($orderby);

		if ($items_per_page)
			$this->db->limit($items_per_page, $offset);
			
		return $this->db->get();
	}

}

?>