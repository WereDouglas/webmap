<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Api extends REST_Controller {

    function __construct() {

        parent::__construct();
       
       // $this->load->model('Md');
        
    }

    public function tasks_get() {
          
        if (!$this->get('id')) {
         //  $tasks = $this->Md->show('daily'); // return all record
             $tasks = $this->Md->get_all(); // return all record
        } else {
            $tasks = $this->Md->get_task($this->get('id')); // return a record based on id
           // $tasks = $this->Md->get('id', $this->get('id'), 'daily');
            }

        if ($tasks) {
            $this->response($tasks, 200); // return success code if record exist
        } else {
            $this->response([], 404); // return empty
        }
    }
     

    public function tasks_post() {
        if (!$this->post('actual')) {
            $this->response(array('error' => 'Missing post data: title'), 400);
        } else {
            $data = array(
                'actual' => $this->post('actual'),
                 'min' => $this->post('min'),
                 'max' => $this->post('max')
            );
        }
       // $this->db->insert('task', $data);
         $this->Md->save($data, 'daily');
        if ($this->db->insert_id() > 0) {
            $message = array('id' => $this->db->insert_id(), 'actual' => $this->post('actual'));
            $this->response($message, 200);
        }
    }

    public function tasks_put() {
        if (!$this->put('actual')) {
            $this->response(array('error' => 'Actual rainfall is required'), 400);
        }

        $data = array(
            'actual' => $this->put('actual'),
            'max' => $this->put('max'),
            'min' => $this->put('min')
        );
      //  $this->task_model->update_task($this->put('id'), $data);
          $this->Md->update($this->put('id'),$data, 'daily');
        $message = array('success' => $this->put('actual') . ' Updated!');
        $this->response($message, 200);
    }

    public function tasks_delete($id = NULL) {
        if ($id == NULL) {
            $message = array('error' => 'Missing delete data: id');
            $this->response($message, 400);
        } else {
           $this->Md->delete($id,'daily');
            $message = array('id' => $id, 'message' => 'DELETED!');
            $this->response($message, 200);
        }
    }

}
