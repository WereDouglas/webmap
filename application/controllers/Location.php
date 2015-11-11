<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Location extends CI_Controller {

    function __construct() {

        parent::__construct();
       // error_reporting(E_PARSE);
        $this->load->model('Md');
        $this->load->library('session');
        $this->load->library('encrypt');
        date_default_timezone_set("Africa/Nairobi");
        $this->load->library('helper');
       
    }

    public function index() {        
      
        $this->load->view('home');
    }
    
    public function map() {  
        
       $data['locations'] = array();     
      
        $all = array();
        $query1 = $this->Md->query("select * from user");

            //var_dump($query1);
            foreach ($query1 as $v) {
                $resv = new stdClass();
                   $resv->image = $v->image;                  
                   $resv->username = $v->username;
                    $query2 = $this->Md->query("select * from location where username ='".$v->username."' order by id desc LIMIT 1 ");
                    $results = $query2;     
                     $resv->lat = "";
                        $resv->long = "";
                          $resv->created =   "";  
                    foreach ($results as $res) {                                            
                        $resv->lat = $res->lat;
                        $resv->long = $res->long;
                          $resv->created = $res->created;                
                
                    }
                array_push($all, $resv);
               }
        $data['locations'] = $all;
        
        $this->load->view('view-all',$data);
    }
   
    public function save() {

      
      //  $this->load->helper(array('form', 'url'));
        /**
         * 
         *  params.put("username", "Doug");
        params.put("userid", "23");
        params.put("lat", lat);
        params.put("long", lng);
        //   Toast.makeText(getApplicationContext(), params.toString(), Toast.LENGTH_LONG).show();
       // client.post("http://dodoreapi.azurewebsites.net/api/Location",params ,new AsyncHttpResponseHandler() {
        client.post("http://192.168.1.129/webmap/index.php/location/save",params ,new AsyncHttpResponseHandler()
         * 
         * **/       
      
        $username = $this->input->post('username');            
        $lat = $this->input->post('lat');
        $long =$this->input->post('long');
          /**
        $username = "Doug";
        $userid = "23";       
        $lat = "0.3417913";
        $long ="32.5943488";
        
        **/
        $created = date('Y-m-d H:i:s');
        if ($username!=""){
         $locate = array('username' => $username, 'userid' => "", 'lat' => $lat, 'long' =>$long, 'created' => $created);
         $this->Md->save($locate, 'location');
         echo 'posted';
    }
    else{
        
        echo 'invalid user';
    }
      
    }    
   
    public function delete() {

        $id = $this->uri->segment(3);

        $query = $this->Md->delete($id, 'metar');

        if ($this->db->affected_rows() > 0) {
            $msg = '<span style="color:red">Information Deleted Fields</span>';
            $this->session->set_flashdata('msg', $msg);
            redirect('/metar', 'refresh');
        } else {
            $msg = '<span style="color:red">action failed</span>';
            $this->session->set_flashdata('msg', $msg);
            redirect('/metar', 'refresh');
        }
    }

    public function check($metar) {
        $this->load->helper(array('form', 'url'));

        $metar = ($metar == "") ? $this->input->post('name') : $metar;
        //check($value,$field,$table)
        $get_result = $this->Md->check($metar, 'name', 'metar');

        if (!$get_result)
            echo '<span style="color:#f00"> name already in use. </span>';
        else
            echo '<span style="color:#0c0"> name not in use</span>';
    }

    public function check_email() {
        $this->load->helper(array('form', 'url'));

        $email = $this->input->post('email');
        //check($value,$field,$table)
        $get_result = $this->Md->check($email, 'email', 'metar');

        if (!$get_result)
            echo '<span style="color:#f00">email already in use. </span>';
        else
            echo '<span style="color:#0c0">email not in use</span>';
    }

   

}
