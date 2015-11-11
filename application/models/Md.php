<?php

class Md extends CI_Model {

    function __construct() {
        parent::__construct();
        	
    }

    function save($roles = NULL, $table) {
        $this->db->insert($table, $roles);
        return $this->db->insert_id();
    }

    function check($value, $field, $table) {

        $query = $this->db->query('SELECT * FROM ' . $table . ' where ' . $field . '="' . $value . '"');

        if ($query->num_rows() > 0)
            return false;
        else
            return true;
    }

    function check_sql($string) {

        $query = $this->db->query($string);

        if ($query->num_rows() > 0)
            return false;
        else
            return true;
    }

    function show($table) {

        $query = $this->db->query("SELECT * FROM $table order by id desc " );
        $result = $query->result();
        return $result;
    }

    function delete($id, $table) {

        $sql = "DELETE FROM $table WHERE id =? ";
        $this->db->query($sql, array($id));
        return $this->db->affected_rows();
    }

    function get($field, $value, $table) {
        $this->db->select('*');
        $this->db->from($table);
        $this->db->where($field, $value);
        $this->db->order_by("id", "desc");
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    //$this->MD->update($id,$role, 'role');
    function update($id, $data, $table) {
        $this->db->where('id', $id);
        $this->db->update($table, $data);
    }

    public function remove($id, $table, $column) {
        $file = $this->file($id, $table);
        unlink('./uploads/' . $file->$column);
        return $this->db->affected_rows();
    }

    public function file($file_id, $table) {
        return $this->db->select()
                        ->from($table)
                        ->where('id', $file_id)
                        ->get()
                        ->row();
    }

    function query($string) {

        $query = $this->db->query($string);
        $result = $query->result();
        return $result;
    }

    function query_single($string) {

        //$query = $this->db->query($string);
        //$result = $query->result();
        //return $result;
        return $this->db->query($string)->row()->id;
    }

    function get_where_data($table, $where) {
        $query = $this->db->get_where($table, $where);
        return $query->result_array();
    }

    function tables() {

        return $this->db->list_tables();
    }

    function fields($table) {

        return $this->db->list_fields($table);
    }
    public function get_all()
	{
		$query = $this->db->get('daily');
		return $query->result();
	}
	
	public function get_task($id)
	{
		$this->db->where('id',$id);
		$query = $this->db->get('daily');
		return $query->result();
	}

}

?>