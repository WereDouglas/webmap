<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
      

        <link rel="stylesheet" href="<?= base_url(); ?>css/bootstrap.min.css">
        <link rel="stylesheet" href="<?= base_url(); ?>css/font-awesome.css">
        <link rel="stylesheet" href="<?= base_url(); ?>css/animate.css">
        <link rel="stylesheet" href="<?= base_url(); ?>css/templatemo_misc.css">
        <link rel="stylesheet" href="<?= base_url(); ?>css/templatemo_style.css">

        <script src="<?= base_url(); ?>js/vendor/modernizr-2.6.1-respond-1.1.0.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an outdated browser. <a href="http://browsehappy.com/">Upgrade your browser today</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to better experience this site.</p>
        <![endif]-->




        <div class="row-fluid">
            <div class="row">
                <div class="heading-section col-md-12 text-center">
                    <h4>Tracking <?php echo ' '.$username.' '; ?> </h4>
                    <input type="hidden" id="username" name="username" value="<?=$username?>"/>
                </div> <!-- /.heading-section -->
            </div> <!-- /.row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="googlemap-wrapper">
                        <div id="map_canvas_locations" class="map-canvas_locations"></div>
                    </div> <!-- /.googlemap-wrapper -->
                </div> <!-- /.col-md-12 -->
            </div> <!-- /.row -->
             
     <table class="jobs table table-striped table-bordered bootstrap-datatable datatable" id="datatable">
                                    <thead>
                                        <tr>  
                                            <th></th>
                                            <th>Latitude</th>
                                            <th>Longitude</th>
                                             <th>Distance(m)</th>
                                              <th>Distance(Km)</th>
                                            <th>Created on:</th>
                                           
                                        </tr>
                                    </thead>   
                                    <tbody>

                                        <?php
                                        if (is_array($locations) && count($locations)) {
                                              foreach ($locations as $loop) {  
                                                ?>  
                                                <tr >    
                                                     <td><?=$loop->id?> </td>
                                                    <td><?=$loop->lat?> </td>
                                                     <td><?=$loop->lng?> </td>
                                                        <td><?=$loop->distance?> </td>
                                                         <td><?php echo (($loop->distance)/1000);?> </td>
                                                           <td><?=$loop->created?> </td>
                                                      
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>




                                    </tbody>
                                </table>     
        </div> <!-- /.container -->


        <div id="footer">

        </div> <!-- /#footer -->

<!--<script type="text/javascript" src="js/jquery.min.js"></script> -->
        <script src="<?= base_url(); ?>js/vendor/jquery-1.11.0.min.js"></script>
        <script>window.jQuery || document.write('<script src="<?= base_url(); ?>js/vendor/jquery-1.11.0.min.js"><\/script>')</script>
        <script src="<?= base_url(); ?>js/bootstrap.js"></script>
        <script src="<?= base_url(); ?>js/plugins.js"></script>
        <script src="<?= base_url(); ?>js/main.js"></script>
        <script type="text/javascript"
                src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBSr836clPDtPAsp3iW0aE3rhcnKhsuPdE">
        </script>

        <!-- Google Map 
        <script src="http://maps.google.com/maps/api/js?sensor=true"></script>  
        <script src="<?= base_url(); ?>js/vendor/jquery.gmap3.min.js"></script> -->

        <?php if(is_array($locations) && count($locations) ) {
						           // var_dump($locations);
            $lat = $locations[count($locations)-1]->lat;
           $lng= $locations[count($locations)-1]->lng;
		// $lat = '0.3417913';
        echo   $created= $locations[count($locations)-1]->created;			
            }
	?>
       
    
        
       
    <script type="text/javascript">     
	
	var map;
        
   function initialize(){
    var mapOptions = {
        center: new google.maps.LatLng(<?php echo $lat;?>,<?php echo $lng;?>),
        zoom: 14  
       
    };
    map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions); 
	
     var myLatlng = new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $lng; ?>);
	 var image = '<?= base_url(); ?>images/walking.png'; 
	 
		var marker = new google.maps.Marker({
			position: myLatlng ,
			map: map,
			title:"last <?php echo $created; ?>",
			 icon: image
		});                
	 
	   // 0.363189, 32.598064
      google.maps.event.addDomListener(window, 'load', initialize);
      
      }
    </script>



    <script type="text/javascript">     
	
    //The list of points to be connected
var map = null;
var infowindow = new google.maps.InfoWindow();
var bounds = new google.maps.LatLngBounds();
//The list of points to be connected
var markers = [

<?php if(is_array($locations) && count($locations) ) {
	foreach($locations as $loop){
	?>
    {
        "title": '<?php echo $loop -> created; ?>',
        "lat": '<?php echo $loop -> lat; ?>',
        "lng": '<?php echo $loop -> lng; ?>',
        "description":' <?php echo $loop ->username ; ?>'
    },
	
	<?php } } ?>
    
];


function initialize() {
    var mapOptions = {
        center: new google.maps.LatLng(<?php echo $lat;?>,<?php echo $lng;?>),
        zoom: 14 ,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    
} 
 google.maps.event.addDomListener(window,'load',initialize);
    </script>
	<script>
              
	   var form_data = { username: $('#username').val() };
				$.ajax({
					 type : 'POST',
					 url : "<?php echo base_url()?>index.php/user/movement" ,
                                          dataType: 'json',
                                           data: form_data,
					 success : function(data) {
                                            // console.log(data)
    var mapOptions = {
        center: new google.maps.LatLng(<?php echo $lat;?>,<?php echo $lng;?>),
        zoom: 14 ,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
 markers = JSON.parse(JSON.stringify(data));
						//console.log(markers[0].lat)
var path = new google.maps.MVCArray();
    var service = new google.maps.DirectionsService();

    var infoWindow = new google.maps.InfoWindow();
    map = new google.maps.Map(document.getElementById("map_canvas_locations"), mapOptions);
	map.setCenter(new google.maps.LatLng(<?php echo $lat;?>,<?php echo $lng;?>));
    var poly = new google.maps.Polyline({
        map: map,
        strokeColor: '#990000'
    });
    var lat_lng = new Array();

    for (var i = 0; i < markers.length; i++) {
        if ((i + 1) < markers.length) {
            var src = new google.maps.LatLng(parseFloat(markers[i].lat), 
                                             parseFloat(markers[i].lng));
            var des = new google.maps.LatLng(parseFloat(markers[i+1].lat), 
                                             parseFloat(markers[i+1].lng));
            service.route({
                origin: src,
                destination: des,
                travelMode: google.maps.DirectionsTravelMode.DRIVING
            }, function (result, status) {
                if (status == google.maps.DirectionsStatus.OK) {
                    for (var i = 0, len = result.routes[0].overview_path.length; i < len; i++) {
                        path.push(result.routes[0].overview_path[i]);
                    }
                    poly.setPath(path);
                   // map.fitBounds(bounds);
                }
            });
        }
    }
  var myLatlng = new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $lng; ?>);
	 var image = '<?= base_url(); ?>images/walking.png'; 
	 
		var marker = new google.maps.Marker({
			position: myLatlng ,
			map: map,
			title:"last <?php echo $created; ?>",
			 icon: image
		});   

					  //}
					  }
					  });
     </script>
    </body>
</html>