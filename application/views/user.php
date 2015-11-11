<link rel="stylesheet" href="<?= base_url(); ?>assets/css/ace.min.css" />     
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/ace-skins.min.css" />       

<link rel="stylesheet" href="<?= base_url(); ?>assets/css/chosen.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/datepicker.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/bootstrap-timepicker.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/daterangepicker.css" />
<link rel="stylesheet" href="<?= base_url(); ?>assets/css/bootstrap.min.css" />
<link href="<?= base_url(); ?>css/mine.css" rel="stylesheet" />

<div class="row-fluid">
     

         <h4>User details to below: </h4>
          <div class="span3">
                                    <form id="user-form" name="user-form" enctype="multipart/form-data"  action='<?= base_url(); ?>index.php/user/save'  method="post">            

                                            <fieldset>
                                    <label>

                                                    <span >
                                                        <input type="text" name="username" id="username" class="span12" placeholder="Username" />
                                                      <span id="Loading_name" name ="Loading_name"><img src="<?= base_url(); ?>images/ajax-loader.gif" alt="loading.........." /></span>
                                                      
                                                    </span>
                                                </label>

                                                <label>
                                                    <span >
                                                        <input type="text" name="name" id="name" class="span12" placeholder="Name" />
                                                               <input type="text" name="contact" id="contact" class="span12" placeholder="Contact" />
                                                          
                                                    </span>
                                                </label>
                                                 <label>
                                                    <span >
                                                        Use 32x36 image size for better display
                                                  <input type="file" class="span12"  id="userfile" name="userfile">
                                        <img id="preview"  width=50px" height="50px" src="<?= base_url(); ?>images/placeholder.jpg" alt=" Your profile passport image" />
                                                 </span>
                                                </label>
    
                                               
                                                <div class="clearfix">
                                                    <button type="reset" class="width-10 pull-right btn btn-small btn-success" >
                                                        <i class="icon-refresh"></i>
                                                        Reset
                                                    </button>

                                                    <button class="width-10 btn btn-small"  >
                                                     Submit

                                                    </button>
                                                </div>
                                            </fieldset>
                                            </form>
    </div>
         <div class="span9" style="margin-left: 9px;">
              
            <div class="span12 container">
                
                         
                   
                <div class="row-fluid">
                                <table id="datatable" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>                                           
                                            <th>ID</th>
                                            
                                         <th></th>
                                            <th>Username</th>
                                            <th >Name</th>
                                            <th>Contact</th>                                            
                                            <th>Created</th> 
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
  <?php
                                        if (is_array($users) && count($users)) {
                                            foreach ($users as $loop) {
                                         ?>  

                                        <tr>
                                           
                                            <td>
                                                <a href="#"><?= $loop->id ?></a>
                                            </td>
                                             <td>
        <?php if ($loop->image == "") { ?>                                                    
                                                <img class="avatar" alt="image" height="20px" width="20px" src="<?= base_url(); ?>images/placeholder.jpg">

        <?php } else { ?>

                                                <img class="avatar" alt="image" height="20px" width="20px" src="<?php echo base_url(); ?>uploads/<?php echo $loop->image ?>">
        <?php } ?>	</td>
                                            <td><a  href="<?php echo base_url(). "index.php/user/location/". $loop->username; ?>" target="myframe"><?= $loop->username ?></a></td>
                                            <td > <a  href="<?php echo base_url(). "index.php/user/location/".$loop->username; ?>" target="myframe"><?= $loop->name ?></a></td>
                                            <td ><?= $loop->contact ?></td>

                                            <td >  <?= $loop->created ?> </td> 
                                          <td>                                                     
                                           <a href="<?php echo base_url() . "index.php/user/delete/" . $loop->id; ?>" title="Delete">
                                                            
                                                      Delete  </a>
                                            </td>

                                                 
                                        </tr>

                                        <?php }}?>

                                    </tbody>
                                </table>    
                            </div>
            </div><!--/span-->
         </div>

      

        <!--PAGE CONTENT ENDS-->
    </div><!--/.span-->
</div><!--/.row-fluid-->
</div><!--/.page-content-->


</div><!--/.main-content-->
</div><!--/.main-container-->

<script src='<?= base_url(); ?>js/jquery.min.js'></script>
<script src='<?= base_url(); ?>js/jquery.dataTables.min.js'></script>

<script src="<?= base_url(); ?>js/jquery.dataTables.js" type="text/javascript"></script>
<script type="text/javascript">
$(function () {
   $("#datatable").dataTable();

});
</script>

<script type="text/javascript">
    $('#Loading_name').hide();
    $("#username").blur (function (e) {

        var username = $("#username").val();

        if (username != null) {           // show loader 
         
            $('#Loading_name').show();
            $.post("<?php echo base_url() ?>index.php/user/checks", {
                username: username
            }, function (response) {
                //#emailInfo is a span which will show you message
                $('#Loading_name').hide();
                setTimeout(finishAjax('Loading_name', escape(response)), 400);
            });
        }
        function finishAjax(id, response) {
            $('#' + id).html(unescape(response));
            $('#' + id).fadeIn();
        }
    }

    );
</script>
<script type="text/javascript">

    $("#userfile").change(function () {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>