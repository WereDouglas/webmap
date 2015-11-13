<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <title>mapMe</title>
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
                    <h4> All member locations</h4>
                  
                </div> <!-- /.heading-section -->
            </div> <!-- /.row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="googlemap-wrapper">
                        <div id="map_canvas_locations" class="map-canvas_locations"></div>
                    </div> <!-- /.googlemap-wrapper -->
                </div> <!-- /.col-md-12 -->
            </div> <!-- /.row -->

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

        <?php
        if (is_array($locations) && count($locations)) {
            //   var_dump($locations);

            $lat = '0.3417913';
            $lng = '32.5943488';
        }
        
        ?> 


        <script type="text/javascript">

            var map;
            function initialize() {
                var mapOptions = {
                    center: new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $lng; ?>),
                    zoom: 14

                };
                map = new google.maps.Map(document.getElementById("map_canvas_locations"), mapOptions);

<?php
if (is_array($locations) && count($locations)) {
    foreach ($locations as $loop) {
        if ($loop->image==""){
            $image = base_url(). "images/placeholder.jpg";            
        }
        else{
             $image = base_url()."uploads/".$loop->image;
            
        }
        if($loop->lat!=""&&$loop->lng!=""){
        ?>


                        // To add the marker to the map, use the 'map' property
                        var myLatlng = new google.maps.LatLng(<?= $loop->lat; ?>,<?= $loop->lng; ?>);

                      
                        var image = '<?= $image; ?>';

                        var data = "<?= $loop->username; ?>";
                        var infowindow = new google.maps.InfoWindow({
                            content: data
                        });

                        var marker = new google.maps.Marker({
                            position: myLatlng,
                            map: map,
                            title: '<?= $loop->username; ?>',
                            icon: image
                        });
                        google.maps.event.addListener(marker, 'click', function () {
                            infowindow.open(map, marker);
                        });
<?php }}
} ?>
         }
            var myLatlng = new google.maps.LatLng(<?php echo $lat; ?>,<?php echo $lng; ?>);
            var image = '<?= base_url(); ?>images/walking.png';

            var marker = new google.maps.Marker({
                position: myLatlng,
                map: map,
                title: "",
                icon: image
            });

            // 0.363189, 32.598064
            google.maps.event.addDomListener(window, 'load', initialize);
            
            
            setTimeout(function(){
   window.location.reload(1);
}, 150000);
        </script>      





    </body>
</html>