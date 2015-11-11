<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    function __construct() {

        parent::__construct();
        error_reporting(E_PARSE);
        $this->load->model('Md');
        $this->load->library('session');
        $this->load->library('encrypt');
        $this->load->library('helper');
    }

    public function index() {
        $query = $this->Md->show('role');
        if ($query) {
            $data['roles'] = $query;
        } else {
            $data['roles'] = array();
        }
        $query = $this->Md->show('station');
        if ($query) {
            $data['stations'] = $query;
        } else {
            $data['stations'] = array();
        }
        $this->load->view('login', $data);
    }

    public function start() {
        
           $query = $this->Md->show('daily');
        //  var_dump($query);
        if ($query) {
            $data['daily'] = $query;
        } else {
            $data['daily'] = array();
        }
        
         $query = $this->Md->query('select wind_direction,wind_speed,station_pressure_hpa,day from metar where Month(day)="'.date('m').'" AND YEAR(day)="'.  date('Y').'" AND station ="'.$this -> session -> userdata('code').'" ');
       
        if ($query) {
            $data['dir'] = $query;
        } else {
            $data['dir'] = array();
        }
      //  var_dump($query);
       
        $query = $this->Md->show('metar');
        if ($query) {
            $data['metars'] = $query;
        } else {
            $data['metars'] = array();
        }
        $query = $this->Md->show('rain');
        if ($query) {
            $data['rains'] = $query;
        } else {
            $data['rains'] = array();
        }
        $query = $this->Md->show('synoptic');
        if ($query) {
            $data['synoptics'] = $query;
        } else {
            $data['synoptics'] = array();
        }
        
        $this->load->view('view-start',$data);
    }

    public function logout() {
         if ($this->session->userdata('username') != null) {

        $this->session->sess_destroy();
        $log = array('user' => $this->session->userdata('username'), 'userid' => $this->session->userdata('id'), 'action' => 'logout', 'details' => $this->session->userdata('stationname') . ' has logged out ', 'date' => date('Y-m-d H:i:s'), 'ip' => $this->input->ip_address(), 'url' => 'www.');
        $this->Md->save($log, 'logs');
        $this->load->view('login');
         }
         else{
             
              $this->load->view('login');
             
         }
    }

    public function login() {

        if ($this->session->userdata('username') != null) {

            $this->load->view('home');
            return;
        }

        $this->load->helper(array('form', 'url'));
        $email = $this->input->post('email');
        $password_now = $this->input->post('password');


        $key = $email;


        $get_result = $this->Md->check($email, 'email', 'user');
        if (!$get_result) {
            //$this->session->set_flashdata('msg', 'Welcome'.$email);
            //get($field,$value,$table)
            $result = $this->Md->get('email', $email, 'user');
            // var_dump($result);
            foreach ($result as $res) {
                $key = $email;
                $password = $this->encrypt->decode($res->password, $key);

                if ($password_now == $password) {

                    $newdata = array(
                        'id' => $res->id,
                        'username' => $res->name,
                        'email' => $res->email,
                        'contact' => $res->contact,
                        'status' => $res->active,
                        'station' => $res->station,
                        'role' => $res->role,
                        'logged_in' => TRUE
                    );

                    $this->session->set_userdata($newdata);

                    $infos = $this->Md->get('name', $res->role, 'role');


                    $actions = "";

                    foreach ($infos as $info) {
                        $actiondata = array('actions' => $info->actions, 'views' => $info->views);
                    }
                    $this->session->set_userdata($actiondata);

                    $stations = $this->Md->get('name', $res->station, 'station');

                    foreach ($stations as $stat) {
                        $stationdata = array('number' => $stat->number, 'stationname' => $stat->name, 'city' => $stat->city, 'region' => $stat->region, 'code' => $stat->code);
                    }
                    $this->session->set_userdata($stationdata);
                    $log = array('user' => $this->session->userdata('username'), 'userid' => $this->session->userdata('id'), 'action' => 'login', 'details' => $this->session->userdata('username') . ' has logged in ', 'date' => date('Y-m-d H:i:s'), 'ip' => $this->input->ip_address(), 'url' => 'www.');
                    $this->Md->save($log, 'logs');
                    $this->load->view('home');
                } else {
                    $this->session->set_flashdata('msg', 'Invalid user');
                    redirect('/', 'refresh');
                }
            }
        } else {
            $this->session->set_flashdata('msg', 'Invalid user');
            redirect('/', 'refresh');
        }
    }

    public function user() {
        $query = $this->Md->show('role');
        //  var_dump($query);
        if ($query) {
            $data['roles'] = $query;
        } else {
            $data['roles'] = array();
        }

        $this->load->view('user', $data);
    }

    public function station() {
        $this->load->view('station');
    }

    public function element() {
        $this->load->view('element');
    }

    public function instrument() {
        $this->load->view('instrument');
    }

    public function archive() {
        $this->load->view('archive');
    }

    public function schedule() {
        $query = $this->Md->show('instrument');
        if ($query) {
            $data['instruments'] = $query;
        } else {
            $data['instruments'] = array();
        }

        $this->load->view('schedule', $data);
    }

    public function reports() {


        $query = $this->Md->show('role');
        if ($query) {
            $data['roles'] = $query;
        } else {
            $data['roles'] = array();
        }
        $query = $this->Md->show('station');
        if ($query) {
            $data['stations'] = $query;
        } else {
            $data['stations'] = array();
        }
        // get($field,$value,$table)
        // $query = $this->Md->query("SELECT * FROM  daily RIGHT JOIN metar ON daily.date = metar.day  "); 
        $query = $this->Md->query("SELECT * FROM daily");

        if ($query) {
            $data['daily'] = $query;
        } else {
            $data['daily'] = array();
        }
        $datetime = "09" . "00Z";
        $datetimes = "15" . "00Z";
        $all = array();
        $query1 = $this->Md->query("select *  from daily");
        foreach ($query1 as $v) {
            $resv = new stdClass();
            $resv->date = $v->date;
            $resv->max = $v->max;
            $resv->min = $v->min;
            $resv->rain = $v->actual;
            $resv->actual2 = $v->maxi;

            $resv->rain = $v->rain;
            $resv->thunder = $v->thunder;
            $resv->fog = $v->fog;
            $resv->haze = $v->haze;
            $resv->storm = $v->storm;
            $resv->quake = $v->quake;
            $resv->height = $v->height;
            $resv->duration = $v->duration;
            $resv->sunshine = $v->sunshine;
            $resv->radiationtype = $v->radiationtype;
            $resv->radiation = $v->radiation;
            $resv->evaptype1 = $v->evaptype1;
            $resv->evap = $v->evap;
            $resv->evaptype2 = $v->evaptype2;
            $resv->evap = $v->evap;
            $query2 = $this->Md->query("select * from metar WHERE day='" . $v->date . "'");
            $results = $query2;
            //  var_dump($results);
            foreach ($results as $res) {

                if ((strpos($res->datetime, $datetime)) && $res->day == $v->date) {

                    $resv->air9 = $res->air_temperature;
                    $resv->wet9 = $res->wet_bulb;
                    $resv->dew9 = $res->dew_temperature;
                    $resv->humid9 = $res->humidity;
                    $resv->wind9 = $res->wind_direction;
                    $resv->speed9 = $res->wind_speed;
                    $resv->visibility9 = $res->visibility;
                    $resv->hpa9 = $res->station_pressure_hpa;
                }
                if ((strpos($res->datetime, $datetimes)) && $res->day == $v->date) {

                    $resv->air15 = $res->air_temperature;
                    $resv->wet15 = $res->wet_bulb;
                    $resv->dew15 = $res->dew_temperature;
                    $resv->humid15 = $res->humidity;
                    $resv->wind15 = $res->wind_direction;
                    $resv->speed15 = $res->wind_speed;
                    $resv->visibility15 = $res->visibility;
                    $resv->hpa15 = $res->station_pressure_hpa;
                }
            }
            array_push($all, $resv);
        }
        //    var_dump($all);


        $data['monthly'] = $all;



        $this->load->view('reports', $data);
    }

    public function form() {
        $this->load->view('form');
    }

    public function form4() {
        $this->load->view('form4');
    }

    public function dekadal() {
        $this->load->view('dekadal');
    }

    public function form5() {
        $this->load->view('form5');
    }

    public function summary() {
        $this->load->view('summary');
    }

    public function metar() {
        $this->load->view('metar');
    }

    public function rainfall() {
        $this->load->view('rainfall');
    }

    public function climatological() {
        $this->load->view('climatological');
    }

    public function card() {

        $this->load->helper(array('form', 'url'));

        $stations = $this->input->post('station');
        $months = $this->input->post('month');
        $years = $this->input->post('year');

        $dailys = $this->Md->query("select * from daily WHERE MONTH(date)='" . $months . "' AND station='" . $stations . "' AND YEAR(date)='" . $years . "' ");
        // var_dump($daily);
        if ($dailys) {

            echo ' <div class="rainfallcard" >';
             //var_dump($dailys);
            if (is_array($dailys) && count($dailys)) {
                foreach ($dailys as $loop) {
                    
                    $month = date('m', strtotime($loop->date));
                    $day = date('d', strtotime($loop->date));
                  
                    if ($months ==""){
                        
                        $current_month = date('m');  
                    }
                    else {
                         $current_month = $months;
                        
                    }
                    
                  

                    if ($loop->actual < 1) {

                        if ($loop->actual < 0.1) {
                            $rain = 'TR';
                        } else {

                            $rain = 'NIL';
                        }
                    } else {
                        $rain = $loop->actual;
                    }

                    if ($month == $current_month + 1) {
                        $first = $rain;
                    }

                    if ($month == $current_month) {
                        if ($loop->actual > $max) {
                            $max = $loop->actual;
                            $maxdate = $loop->date;
                        }
                        if ($loop->actual > 1) {
                            $rains = $rains + 1;
                        }
                        $sum = $sum + $loop->actual;


                        if ($day == '1') {
                            $one = $rain;
                        }
                        if ($day == '2') {
                            $two = $rain;
                        }
                        if ($day == '3') {
                            $three = $rain;
                        }
                        if ($day == '4') {
                            $four = $rain;
                        }
                        if ($day == '5') {
                            $five = $rain;
                        }
                        if ($day == '6') {
                            $six = $rain;
                        }
                        if ($day == '7') {
                            $seven = $rain;
                        }
                        if ($day == '8') {
                            $eight = $rain;
                        }
                        if ($day == '9') {
                            $nine = $rain;
                        }
                        if ($day == '10') {
                            $ten = $rain;
                        }
                        if ($day == '11') {
                            $eleven = $rain;
                        }
                        if ($day == '12') {
                            $twelve = $rain;
                        }
                        if ($day == '13') {
                            $thirt = $rain;
                        }
                        if ($day == '14') {
                            $fourt = $rain;
                        }
                        if ($day == '15') {
                            $fith = $rain;
                        }
                        if ($day == '16') {
                            $sixth = $rain;
                        }
                        if ($day == '17') {
                            $seventh = $rain;
                        }
                        if ($day == '18') {
                            $eighth = $rain;
                        }
                        if ($day == '19') {
                            $nineth = $rain;
                        }
                        if ($day == '20') {
                            $twenty = $rain;
                        }
                        if ($day == '21') {
                            $twentyone = $rain;
                        }
                        if ($day == '22') {
                            $twentytwo = $rain;
                        }
                        if ($day == '23') {
                            $twentythree = $rain;
                        }
                        if ($day == '24') {
                            $twentyfour = $rain;
                        }
                        if ($day == '25') {
                            $twentyfive = $rain;
                        }
                        if ($day == '26') {
                            $twentysix = $rain;
                        }
                        if ($day == '27') {
                            $twentyseven = $rain;
                        }
                        if ($day == '28') {
                            $twentyeight = $rain;
                        }
                        if ($day == '29') {
                            $twentynine = $rain;
                        }
                        if ($day == '30') {
                            $thirty = $rain;
                        }
                        if ($day == '31') {
                            $thirtyone = $rain;
                        }
                    }
                }
            }

            echo '    <table id="card" >
                                                                <tr style="height:1px;">

                                                                    <td  colspan="10">

                                                                    </td>


                                                                </tr>

                                                                <tr>
                                                                    <td colspan="2">
                                                                        <strong>REGION</strong>
                                                                    </td>
                                                                    <td colspan="3">' .
            $this->session->userdata('region')
            . ' </td>
                                                                    <td colspan="2">
                                                                        <strong>  DISTRICT</strong>
                                                                    </td>
                                                                    <td colspan="3">' .
            $this->session->userdata('city') .
            '        </td>

                                                                </tr>
                                                                <tr>
                                                                    <td colspan="10">
                                                                        <h5> <strong>  STATION NAME:</strong>' . $this->session->userdata('name') . '</h5>
                                                                    </td>


                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2">
                                                                        <strong> NUMBER:</strong>
                                                                    </td>
                                                                    <td colspan="3">
' . $this->session->userdata('number') . '  </td>
                                                                    <td colspan="2">
                                                                        <strong>  MONTH</strong>
                                                                    </td>
                                                                    <td colspan="3">
' . date('Y-M') . '
                                                                    </td>

                                                                </tr>

                                                                <tr>
                                                                    <td>
                                                                        <strong> 2</strong>
                                                                    </td>
                                                                    <td >
                                                                        <strong> 3</strong>
                                                                    </td>
                                                                    <td>
                                                                        <strong>   4
                                                                    </td>
                                                                    <td>
                                                                        <strong>  5</strong>
                                                                    </td>
                                                                    <td>
                                                                        <strong>  6</strong>
                                                                    </td>
                                                                    <td>
                                                                        <strong>   7</strong>
                                                                    </td>
                                                                    <td>
                                                                        <strong>  8</strong>
                                                                    </td>
                                                                    <td>
                                                                        <strong>  9</strong>
                                                                    </td>
                                                                    <td>
                                                                        <strong>   10</strong>
                                                                    </td>
                                                                    <td>
                                                                        <strong>  11</strong>
                                                                    </td>
                                                                </tr>
                                                                <tr>

                                                                    <td>
                                                                        ' . $two . '
                                                                    </td>
                                                                    <td>
                                                                       ' . $three . '
                                                                    </td>
                                                                    <td>
                                                                       ' . $four . '
                                                                    </td>
                                                                    <td>
                                                                       ' . $five . '
                                                                    </td>
                                                                    <td>
                                                                        ' . $six . '
                                                                    </td>
                                                                    <td>
                                                                       ' . $seven . '
                                                                    </td>
                                                                    <td>
                                                                        ' . $eight . '
                                                                    </td>
                                                                    <td>
                                                                         ' . $nine . '
                                                                    </td>
                                                                    <td>
                                                                       ' . $ten . '
                                                                    </td>
                                                                    <td >
 ' . $eleven . '
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td >
                                                                        <strong>    12</strong>
                                                                    </td>
                                                                    <td>
                                                                        <strong>  13</strong>
                                                                    </td>
                                                                    <td>
                                                                        <strong>   14</strong>
                                                                    </td>
                                                                    <td>
                                                                        <strong>   15</strong>
                                                                    </td>
                                                                    <td>
                                                                        <strong>   16</strong>
                                                                    </td>
                                                                    <td>
                                                                        <strong>  17</strong>
                                                                    </td> 
                                                                    <td>
                                                                        <strong>  18</strong>
                                                                    </td> 
                                                                    <td>
                                                                        <strong>  19</strong>
                                                                    </td>  
                                                                    <td>
                                                                        <strong> 20</strong>
                                                                    </td> 
                                                                    <td>
                                                                        <strong>  21</strong>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td >
                                                                        ' . $twelve . '
                                                                    </td>
                                                                    <td>
                                                                         ' . $thirt . '
                                                                    </td>
                                                                    <td>
                                                                        ' . $fourt . '
                                                                    </td>
                                                                    <td>
                                                                        ' . $fith . '
                                                                    </td>
                                                                    <td>
                                                                         ' . $sixth . '
                                                                    </td>
                                                                    <td>
                                                                         ' . $seventh . '
                                                                    </td>
                                                                    <td>
                                                                        ' . $eighth . '
                                                                    </td>
                                                                    <td>
                                                                        ' . $nineth . '
                                                                    </td>
                                                                    <td>
                                                                        ' . $twenty . '
                                                                    </td>
                                                                    <td>
                                                                   ' . $twentyone . '
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td >
                                                                        <strong>   22</strong>
                                                                    </td>
                                                                    <td>
                                                                        <strong> 23</strong>
                                                                    </td>
                                                                    <td>
                                                                        <strong>  24</strong>
                                                                    </td>  
                                                                    <td>
                                                                        <strong>  25</strong>
                                                                    </td> 
                                                                    <td>
                                                                        <strong>  26</strong>
                                                                    </td>
                                                                    <td>
                                                                        <strong>  27</strong>
                                                                    </td> 
                                                                    <td>
                                                                        <strong> 28</strong>
                                                                    </td> 
                                                                    <td>
                                                                        <strong>  29</strong>
                                                                    </td>  
                                                                    <td>
                                                                        <strong> 30</strong>
                                                                    </td>
                                                                    <td>
                                                                        <strong> 31</strong>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td >
                                                                    ' . $twentytwo . '
                                                                    </td>
                                                                    <td>
                                                                        ' . $twentythree . '
                                                                    </td>
                                                                    <td>
                                                                         ' . $twentyfour . '
                                                                    </td>
                                                                    <td>
                                                                        ' . $twentyfive . '
                                                                    </td>
                                                                    <td>
                                                                        ' . $twentysix . '
                                                                    </td>
                                                                    <td>
                                                                       ' . $twentyseven . '
                                                                    </td>
                                                                    <td>
                                                                         ' . $twentyeight . '
                                                                    </td>
                                                                    <td>
                                                                         ' . $twentynine . '
                                                                    </td>
                                                                    <td>
                                                                         ' . $thirty . '
                                                                    </td>
                                                                    <td>
 ' . $thirtyone . '
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td >

                                                                    </td>
                                                                    <td colspan="3">
                                                                        <strong>   1<sup>st</sup> of following month </strong>
                                                                    </td>

                                                                    <td colspan="2">
                                                                        <strong>     Total </strong>
                                                                    </td>
                                                                    <td colspan="2">
                                                                        <strong> Days </strong>
                                                                    </td>                      
                                                                    <td colspan="2">

                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td >

                                                                    </td>
                                                                    <td colspan="3">
 ' . $first . '
                                                                    </td>

                                                                    <td colspan="2">
                                                                       ' . $sum . '
                                                                    </td>
                                                                    <td colspan="2">
 ' . $rains . '
                                                                    </td>                      
                                                                    <td colspan="2">

                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="8">
                                                                        ........................................................................................................................................................................
                                                                    </td>
                                                                    <td colspan="2" >
                                                                        <strong>     Observer</strong>
                                                                    </td>

                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2">
                                                                        <strong>   AVERAGE</strong>
                                                                    </td>
                                                                    <td colspan="2" >
                                                                        <strong>   YEARS</strong>
                                                                    </td>
                                                                    <td colspan="2">
                                                                        <strong>   MAX.FALL</strong>
                                                                    </td>
                                                                    <td colspan="2">
                                                                        <strong>  DATE</strong>
                                                                    </td>
                                                                    <td colspan="2">

                                                                    </td>

                                                                </tr>
                                                                <tr>
                                                                    <td colspan="2">
 ' . number_format($sum / $rains, 2) . '
                                                                    </td>
                                                                    <td colspan="2" >

                                                                    </td>
                                                                    <td colspan="2">
                                                                         ' . $max . '
                                                                    </td>
                                                                    <td colspan="2">
 ' . $maxdate . '
                                                                    </td>
                                                                    <td colspan="2">

                                                                    </td>

                                                                </tr>
                                                            </table>


                                                        </div>
                                         
 ';
        } else {

            echo $month . $year . ' no values for ' . $station;
        }
    }

}
