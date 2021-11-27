<?php
ob_start();
session_start();
//movetoediting 4,5,7,8
require_once("config.php");
require_once("conn.php");
require_once("functions.php");
validateSession();
if(getUserType()==0){
if(isset($_POST["delete"])){
    $entries = "";
    $error = false;
  foreach ($_POST as $key => $index) {
    $value = $_POST[$key];
    if($value=="on"){
      //check that order is pending
      $key = str_replace("order", "", $key);
     $count = mysqli_num_rows(mysqli_query($conn, "select * from orders where Status =-1 and id=".$key));
     if($count>0){//okay
      if($entries!=""){
        $entries.=",";
      }
      $entries.=$key;
     }else{
      $error =true;
      break;
     }
    }
  }
  if($error){
     $_SESSION["status"]="warning*Some orders are not pending. cannot be deleted";

  }else{
    $query = "delete from orders where id in ($entries)";
     $deleted=@mysqli_query($conn, $query);
     if($deleted){
        $_SESSION["status"] = "success*The orders have been deleted";
        //sendAdminEmail($subject, $message)
        sendAdminEmail("Delete order(s)", "Hello admin. <br/> Orders have been deleted. Their is $entries");
//sendEmail($to, $subject, $message)
//@mysqli_query($conn, "insert into order_progression values ('', ".page.", $order, $oldStatus, $newStatus, NOW())");
//@mysqli_query($conn, "insert into writer_progression values ('', $writer, $oldLevel, $newLevel, NOW())");
 @mysqli_query($conn, "insert into change_logs values ('', ".page.", 'delete', ".$_SESSION["userID"].",'Delete pending orders ids in $entries', NOW())");

     }else{
        $_SESSION["status"] = "warning*The orders could not be deleted";
     }

     
  }

}//rejectorder disputeorder


if(isset($_POST["rejectorder"])){
    $entries = "";
    $error = false;
  foreach ($_POST as $key => $index) {
    $value = $_POST[$key];
    if($value=="on"){
      $key = str_replace("order", "", $key);
     $count = mysqli_num_rows(mysqli_query($conn, "select * from orders where id=".$key));
     if($count>0){//okay
      if($entries!=""){
        $entries.=",";
      }
      $entries.=$key;
     }else{
      $error =true;
      break;
     }
    }
  }
  if($error){
     $_SESSION["status"]="warning*Some order status could not allow moving to rejected";

  }else{
    $query = "update orders set Status = 7 where id in ($entries)";
    $moved = @mysqli_query($conn, $query);
     if($moved){
       $_SESSION["status"]= "success*The orders have been moved to rejected";
          sendAdminEmail("Orders moved to rejected", "orders in $entires have been moved to rejected");
      //sendEmail($to, $subject, $message)
      //@mysqli_query($conn, "insert into order_progression values ('', ".page.", $order, $oldStatus, $newStatus, NOW())");
      //@mysqli_query($conn, "insert into writer_progression values ('', $writer, $oldLevel, $newLevel, NOW())");
       @mysqli_query($conn, "insert into change_logs values ('', ".page.", 'modification', ".$_SESSION["userID"].",'orders $entries move to rejected', NOW())");

     }else{
     $_SESSION["status"]="warning*The orders could not be moved to rejected";
     }

    }
  }
if(isset($_POST["disputeorder"])){
    $entries = "";
    $error = false;
  foreach ($_POST as $key => $index) {
    $value = $_POST[$key];
    if($value=="on"){
      $key = str_replace("order", "", $key);
     $count = mysqli_num_rows(mysqli_query($conn, "select * from orders where id=".$key));
     if($count>0){//okay
      if($entries!=""){
        $entries.=",";
      }
      $entries.=$key;
     }else{
      $error =true;
      break;
     }
    }
  }
  if($error){
     $_SESSION["status"]="warning*Some order status could not allow moving to dispute";

  }else{
    $query = "update orders set Status = 8 where id in ($entries)";
    $moved = @mysqli_query($conn, $query);
     if($moved){
       $_SESSION["status"]= "success*The orders have been moved to disputed";
          sendAdminEmail("Orders moved to rejected", "orders in $entires have been moved to disputed");
      //sendEmail($to, $subject, $message)
      //@mysqli_query($conn, "insert into order_progression values ('', ".page.", $order, $oldStatus, $newStatus, NOW())");
      //@mysqli_query($conn, "insert into writer_progression values ('', $writer, $oldLevel, $newLevel, NOW())");
       @mysqli_query($conn, "insert into change_logs values ('', ".page.", 'modification', ".$_SESSION["userID"].",'orders $entries move to disputed', NOW())");

     }else{
     $_SESSION["status"]="warning*The orders could not be moved to disputed";
     }

    }
  }

if(isset($_POST["movetoavailable"])){
    $entries = "";
    $error = false;
  foreach ($_POST as $key => $index) {
    $value = $_POST[$key];
    if($value=="on"){
      $key = str_replace("order", "", $key);
     $count = mysqli_num_rows(mysqli_query($conn, "select * from orders where Status in (-1,1,2,3,4,5,7,8) and id=".$key));
     if($count>0){//okay
      if($entries!=""){
        $entries.=",";
      }
      $entries.=$key;
     }else{
      $error =true;
      break;
     }
    }
  }
  if($error){
     $_SESSION["status"]="warning*Some order status could not allow moving to available";

  }else{
    $query = "update orders set Status = 0, Assigned_to =0 where id in ($entries)";
    $moved = @mysqli_query($conn, $query);
     if($moved){
       $_SESSION["status"]= "success*The orders have been moved to available";
          sendAdminEmail("Orders moved to available", "orders in $entires have been moved to available");
      //sendEmail($to, $subject, $message)
      //@mysqli_query($conn, "insert into order_progression values ('', ".page.", $order, $oldStatus, $newStatus, NOW())");
      //@mysqli_query($conn, "insert into writer_progression values ('', $writer, $oldLevel, $newLevel, NOW())");
       @mysqli_query($conn, "insert into change_logs values ('', ".page.", 'modification', ".$_SESSION["userID"].",'orders $entries move to available', NOW())");

     }else{
     $_SESSION["status"]="warning*The orders could not be moved to available";
     }

    
  }

}
if(isset($_POST["movetorevision"])){
    $entries = "";
    $error = false;
  foreach ($_POST as $key => $index) {
    $value = $_POST[$key];
    if($value=="on"){
      $key = str_replace("order", "", $key);
     $count = mysqli_num_rows(mysqli_query($conn, "select * from orders where Status in (4,5,7,8) and id=".$key));
     if($count>0){//okay
      if($entries!=""){
        $entries.=",";
      }
      $entries.=$key;
     }else{
      $error =true;
      break;
     }
    }
  }
  if($error){
     $_SESSION["status"]="warning*Some order status could not allow moving to revision";

  }else{
    $query = "update orders set Status = 3 where id in ($entries)";
    $moved = @mysqli_query($conn, $query);
     if($moved){
       $_SESSION["status"]= "success*The orders have been moved to revision";
          sendAdminEmail("Orders moved to revision", "orders in $entires have been moved to Revision");
      //sendEmail($to, $subject, $message)
      //@mysqli_query($conn, "insert into order_progression values ('', ".page.", $order, $oldStatus, $newStatus, NOW())");
      //@mysqli_query($conn, "insert into writer_progression values ('', $writer, $oldLevel, $newLevel, NOW())");
       @mysqli_query($conn, "insert into change_logs values ('', ".page.", 'modification', ".$_SESSION["userID"].",'orders $entries move to available', NOW())");

     }else{
     $_SESSION["status"]="warning*The orders could not be moved to revision";
     }

    
  }

}
if(isset($_POST["movetoapproved"])){
    $entries = "";
    $error = false;
  foreach ($_POST as $key => $index) {
    $value = $_POST[$key];
    if($value=="on"){
      $key = str_replace("order", "", $key);
     $count = mysqli_num_rows(mysqli_query($conn, "select * from orders where Status in (3,4) and id=".$key));
     if($count>0){//okay
      if($entries!=""){
        $entries.=",";
      }
      $entries.=$key;

      $date = getdate();
          $checkApprovedSql = "select Id from approvedorders where Order_id = $key";
          $checkApprovedRes = mysqli_query($conn, $checkApprovedSql);
          $checkApprovedCount = mysqli_num_rows($checkApprovedRes);
          if($checkApprovedCount<1){
          $insertToApproved = "insert into approvedorders values ('',$key, ".$_SESSION["userID"].", NOW(), ".$date[0].")";
          mysqli_query($conn,$insertToApproved);
        }else{
          $updateApproved = "update approvedorders set Marked_by = ".$_SESSION["userID"].", Time =NOW(), timestamp= ".$date[0]." where Order_id =$key";
          mysqli_query($conn, $updateApproved);
        }

     }else{
      $error =true;
      break;
     }
    }
  }
  if($error){
     $_SESSION["status"]="warning*Some order status could not allow moving to completed";

  }else{
    $query = "update orders set Status = 5 where id in ($entries)";
     $approved = @mysqli_query($conn, $query);
     if($approved){
      $_SESSION["status"]="success*The orders have been moved to completed";
           sendAdminEmail("Orders moved to available", "orders in $entires have been moved to available");
      //sendEmail($to, $subject, $message)
      //@mysqli_query($conn, "insert into order_progression values ('', ".page.", $order, $oldStatus, $newStatus, NOW())");
      //@mysqli_query($conn, "insert into writer_progression values ('', $writer, $oldLevel, $newLevel, NOW())");
       @mysqli_query($conn, "insert into change_logs values ('', ".page.", 'modification', ".$_SESSION["userID"].",'orders $entries move to available', NOW())");

    }else{
      $_SESSION["status"]="warning*The orders could not be moved to completed";
    }

  }

}

}
?>
<!doctype html>

<html class="no-js" lang="">
<!--<![endif]-->

<head>
<?php require("chatscript.php"); ?>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title><?php echo siteName; ?></title>
<link rel="icon" type="image/png" href="assets/images/logo.png" />
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!--  Stylesheets -->
    
   

<!-- vendor css files -->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href=" https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css">
<link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
<link rel="stylesheet" href="assets/css/vendor/animate.css">
<link rel="stylesheet" href="assets/css/vendor/font-awesome.min.css">
<link rel="stylesheet" href="assets/js/vendor/animsition/css/animsition.min.css">
<link rel="stylesheet" href="assets/js/vendor/magnific-popup/magnific-popup.css">

<!-- project main css files -->
<link rel="stylesheet" href="assets/css/main.css">
<!--/ stylesheets -->
<!-- Modernizr  -->
<script src="assets/js/vendor/modernizr/modernizr-2.8.3-respond-1.4.2.min.js"></script>
<!--/ modernizr -->

</head>
<body id="yatri" class="appWrapper">
 

<!-- Application Content -->
<div id="wrap" class="animsition"> 
  
  <!--  HEADER Content  -->
  <?php
require("navigation.php");
  ?>

    <!--/ HEADER Content  --> 
  
  <!--  CONTROLS Content  -->
  <div id="controls"> 
    <!--SIDEBAR Content -->
    <?php
    $page=0;
    $category = $_GET["category"];
    if($category=="available"){
      $page = 1;
    }else if($category=="pending"){
      $page = 2;
    }else if($category=="confirmed"){
      $page = 4;
    }else if($category=="unconfirmed"){
      $page = 5;
    }else if($category=="revision"){
      $page = 6;
    }else if($category=="editing"){
      $page = 7;
    }
    else if($category=="completed"){
      $page = 8;
    }
    else if($category=="approved"){
      $page = 9;
    }
    else if($category=="rejected"){
      $page = 10;
    }
    else if($category=="disputed"){
      $page = 11;
    }else if($category=="assigned"){
      $page = 17;
    }
    else if($category=="current"){
      $page = 18;
    }
    else if($category == "progress"){
    $page =5;
    }
require("sidebar.php");
    ?>
    <!--/ SIDEBAR Content --> 
    
    <!--RIGHTBAR Content -->
   <?php
require("rightbar.php");
   ?>
    <!--/ RIGHTBAR Content --> 
  </div>
  <!--/ CONTROLS Content --> 
  
  <!-- CONTENT  -->
  <section id="content">
    <div class="page page-gallery">
      <div class="row">
        <div class="col-md-12">
          <div class="pageheader">
         
            
            <div class="page-bar  br-5">
              <ul class="page-breadcrumb">
                <li><a href="dashboard"><i class="fa fa-home"></i> <?php echo siteName; ?></a></li>
                <li><a class="active" href="orders">Order</a></li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <section class="boxs">
        <div class="panel panel-default">
      <div class="panel-heading"> <i class="fa fa-list"></i> <?php echo  strtoupper($_GET["category"]); ?> ORDERS</div>
      <div class="row" style="overflow: auto;">
             
              <div class="panel-body" style="overflow: auto;">
              <p></p>
             
              <div class="col-lg-12 col-md-12">
              <?php
              if(isset($_SESSION["status"])){
                $info = explode("*", $_SESSION["status"]);
                echo "<div class='alert alert-".$info[0]."'>".$info[1]."</div>";
                unset($_SESSION["status"]);
              }

              ?>

             <form method ="POST" action="orders?category=<?php echo $_GET["category"]; ?>">
             <table class="table table-bordered table-striped dataTable table-custom" id="mytable">
             <thead>
               <tr>
               <th></th>
                 <th>#</th>
                 <th>Order Id</th>
                <th>Subject Area</th>
                 <th>Topic</th>                 
                 <th>Academic Level</th>
                 <th>Due Date</th>
                 <th>Number Of Pages</th>
                 <th>Price</th>
               <?php  if(getUserType()==0){ echo "<th>Website</th><th>Client</th>";}?>
                 <?php  if(getUserType()==2 && $_GET["category"]=="pending"){ echo "<th>Pay Now</th>";}?>

               </tr>
             </thead>
<?php
// get user type
      $type= getUserType();
      //get category
      $orderStatus =0;
      if(!isset($_GET["category"])){
        header("Location:orders?category=available");
      }
       $category = trim($_GET["category"]);
          switch($category){
        case "available":
        $orderStatus =0;
        break;
         case "confirmed":
          $orderStatus =1;
         break;
        case "current":
          $orderStatus =1;
         break;
        case "unconfirmed":
          $orderStatus =2;
         break;
         case "assigned":
           $orderStatus =2;
          break;
         case "revision":
          $orderStatus =3;
         break;
        case "editing":
          $orderStatus = 4;
         break;
        case "completed":
          $orderStatus = 5;
         break;
        case "approved":
          $orderStatus =6;
         break;
         case "progress":
         $orderStatus = "1 or A.Status = 2 or A.Status=0 or A.Status=28 ";
         break;
        case "rejected":
          $orderStatus =7;
         break;
         case "disputed":
          $orderStatus =8;
         break;
         case "pending":
          $orderStatus =-1;
         break;
        Default:  
        $orderStatus =2;
        break;

      }
     
      if($type==1 && $orderStatus!=0){
        $orderStatus.= " )and A.Assigned_to =".$_SESSION["userID"];
      }else if($type==2){
        $orderStatus.= " )and A.Client =".$_SESSION["userID"];
      }else{
      $orderStatus.= " )";
      }
  $orderStatus.= " order by Added_on DESC";
          $sql = "select A.*, B.Description as alevels, C.Description as myUrgency, D.Description as mySubject, E.Page_name as website from orders as A LEFT JOIN setups_academic_levels as B ON A.Academic_level = B.Code LEFT JOIN setups_urgency as C ON A.Academic_level = C.Code LEFT JOIN setups_subject_areas as D ON A.Subject_area = D.Code LEFT JOIN pages as E ON A.Page = E.Id where (A.Status = $orderStatus"; 
      
          $res = @mysqli_query($conn, $sql);
          $count=0;
          while($allRes = @mysqli_fetch_assoc($res)){
            echo "<tr><td><input type='checkbox' name='order".$allRes["Id"]."'></td><td>".++$count."</td><td><a href='order?id=".$allRes["Id"]."'>".$allRes["Id"]."</a></td><td>".$allRes["mySubject"]."</td><td><a href='order?id=".$allRes["Id"]."'>".$allRes["Topic"]." ";
            $poSQl ="select * from personal_orders where orderId =".$allRes["Id"];
            $poRes = @mysqli_query($conn, $poSQl);
            if(@mysqli_num_rows($poRes)>0){
            echo "<span class='badge bg-lightred' title='Personal Order'>PO</span>";
          }
          $firstOrderRes = @mysqli_query($conn, "select * from orders where Client = ".$allRes["Client"]);
         
         while($first = mysqli_fetch_assoc($firstOrderRes)){
          if($first["Id"] ==$allRes["Id"]){
             echo "<span class='badge ' style='color:white; background-color:green;' title ='First Order' > FO </span>";
          }
          break;
          }
           $cAddedOn=strtotime($allRes["Added_on"]);
           $cDeadline = strtotime($allRes["Writer_deadline"]);
           $diff= ($cDeadline-$cAddedOn)/3600;
           if($diff<24){
             echo "<span class='badge ' style='color:white; background-color:red;' title ='Critical Order' >CO</span>";
           }

            echo "</a></td><td>".$allRes["alevels"]."</td><td>";
            if(getUserType()!=2){
            $orderDead = strtotime($allRes["Writer_deadline"]);
                        $currentTimeArray = getdate();
                        $currentTime = $currentTimeArray[0];
                        $difference = $orderDead - $currentTime ;
                        $remain = 0;
                        if ($difference <1){
                        $difference = $difference *-1;
                        echo "<label style='color:red;'>";
                        echo "elapsed -";
                        }else{
                         echo "<label style='color:green;'>";
                        }
                      
                        echo floor($difference /86400)." Days "; 
                        $remain = $difference % 86400;
                       
                       
                        echo floor($remain /3600) . " Hours ";
                        $remain = $remain %3600;
                       
                        
                        echo floor($remain /60) . " Minutes";
                        //$remian = $remain %3600;
            }else{
            $orderDead = strtotime($allRes["Order_deadline"]);
                        $currentTimeArray = getdate();
                        $currentTime = $currentTimeArray[0];
                        $difference = $orderDead - $currentTime ;
                        $remain = 0;
                        if ($difference <1){
                         echo "<label style='color:red;'>";
                        echo "elapsed -";
                        $difference = $difference *-1;
                        }else{
                         echo "<label style='color:green;'>";
                        }
                      
                        echo floor($difference /86400) ." Days "; 
                        $remain = $difference % 86400;
                       
                       
                        echo floor($remain /3600) . " Hours ";
                        $remain = $remain %3600;
                       
                        
                        echo floor($remain /60) . " Minutes";
                        //$remian = $remain %3600;
            }
            
            echo "</label></td><td>".$allRes["Number_of_pages"]."</td></td><td>";
           if($type==0 || $type==2){
            echo  $allRes["Order_price"];

           } else if($type==1){
            echo getWriterprice($_SESSION["userID"], $allRes["Number_of_pages"]);
           // $allRes["Amount_to_writer"];
           }
           
           if(getUserType()==0){ echo "</td></td><td>".$allRes["website"]."</td></td><td><a href='profile?id=".$allRes["Client"]."'>".getUserNameFromId($allRes["Client"])."</a></td></tr>";}
          
           if(getUserType()==2 && $_GET["category"]=="pending"){ echo "<td><a href='confirm?id=".$allRes["Id"]."'><label class='btn btn-success'>Pay</label></a></td></tr>";}
}
?>

             </table>
             <?php
             if(getUserType()==0){ 
             echo '<input type="submit" name ="rejectorder" class="btn btn-danger" value ="Reject Order" style="margin-right:10px;">';
             echo '<input type="submit" name ="disputeorder" class="btn btn-danger" value ="Dispute Order" style="margin-right:10px;">';
             if($orderStatus == -1){
              echo '<input type="submit" name ="delete" class="btn btn-danger" value ="Delete" style="margin-right:10px;">';
             }
             if($orderStatus!=6 && $orderStatus!=0){
              echo '<input type="submit" name ="movetoavailable" class="btn btn-danger" value ="Move To Available" style="margin-right:10px;">';
             }
                if($orderStatus==4||$orderStatus==5||$orderStatus==7||$orderStatus==8){
              echo '<input type="submit" name ="movetorevision" class="btn btn-warning" value ="Move To Revision" style="margin-right:10px;">';
             }
              if($orderStatus==3||$orderStatus==4){
              echo '<input type="submit" name ="movetoapproved" class="btn btn-success" value ="Move To Completed" style="margin-right:10px;">';
             }
			
}
             ?>
             </form>
             </div>

              </div>
              </div>
              </div>
          </section>
        </div>
      </div>
    </div>
  </section>
  <!--/ CONTENT --> 
</div>
<!--/ Application Content --> 

<!--  Vendor JavaScripts --> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> 
<script type="text/javascript">window.jQuery || document.write('<script src="assets/js/vendor/jquery/jquery-1.11.2.min.js"></script>
<script src="assets/js/vendor/bootstrap/bootstrap.min.js"></script> 
<script src="assets/js/vendor/jRespond/jRespond.min.js"></script> 
<script src="assets/js/vendor/sparkline/jquery.sparkline.min.js"></script> 
<script src="assets/js/vendor/slimscroll/jquery.slimscroll.min.js"></script> 
<script src="assets/js/vendor/animsition/js/jquery.animsition.min.js"></script> 
<script src="assets/js/vendor/screenfull/screenfull.min.js"></script> 
<script src="assets/js/vendor/magnific-popup/jquery.magnific-popup.min.js"></script> 
<script src="assets/js/vendor/mixitup/jquery.mixitup.min.js"></script> 
<!--/ vendor javascripts --> 
<script src=" https://cdn.datatables.net/1.10.13/js/jquery.dataTables.min.js"></script> 
<script src="https://cdn.datatables.net/1.10.13/js/dataTables.bootstrap.min.js"></script> 

 

<!-- Custom JavaScripts  --> 
<script src="assets/js/main.js"></script> 
<!--/ custom javascripts --> 

<!--  Page Specific Scripts  --> 
<script type="text/javascript">
        $(window).load(function(){
   $('#mytable').DataTable();
            $('.mix-grid').mixItUp();
            $('.mix-controls .select-all input').change(function(){
                if($(this).is(":checked")) {
                    $('.gallery').find('.mix').addClass('selected');
                    enableGalleryTools(true);
                } else {
                    $('.gallery').find('.mix').removeClass('selected');
                    enableGalleryTools(false);
                }
            });

            $('.mix .img-select').click(function(){
                var mix = $(this).parents('.mix');
                if(mix.hasClass('selected')) {
                    mix.removeClass('selected');
                    enableGalleryTools(false);
                } else {
                    mix.addClass('selected');
                    enableGalleryTools(true);
                }
            });

            var enableGalleryTools = function(enable) {

                if (enable) {

                    $('.mix-controls li.mix-control').removeClass('disabled');

                } else {

                    var selected = false;

                    $('.gallery .mix').each(function(){
                        if($(this).hasClass('selected')) {
                            selected = true;
                        }
                    });

                    if(!selected) {
                        $('.mix-controls li.mix-control').addClass('disabled');
                    }
                }
            }

        });
        
    </script> 
<!--/ Page Specific Scripts --> 


</body>
</html>
