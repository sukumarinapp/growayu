<nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0;">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle pull-left margin left" data-toggle="collapse"
                data-target=".sidebar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <h3 style="color:#ff1a1a;font-weight: bold"><img width="120" height="30" src="logo/<?php echo
            $_SESSION['logo']; ?>?<?php echo rand(); ?>"  >
            <?php //echo $_SESSION['clinic_name']; ?></h3>
    </div>
    <?php
    $clinic_id=$_SESSION['clinic_id'];
    $date_notification = date("Y-m-d");
    $notification_sql = "SELECT * from online_appointments where date>='$date_notification' 
    and date<='$date_notification' and clinic_id=$clinic_id and status='Pending'";
    $notification_result = mysqli_query($conn, $notification_sql) or die(mysqli_error($conn));
    $notification_count = mysqli_num_rows($notification_result);
    ?>
    <ul class="nav navbar-top-links navbar-right hidden-xs">
        <?php if($_SESSION['clinic_id']!=-1 and in_array("appointment",$_SESSION['modules'])){ ?>
        <li class="dropdown">
            <a class="dropdown-toggle" href="online.php">
                <i class="fa fa-envelope fa-fw"></i>
                <span id="notification_count" class="badge badge-notification badge-warning animated fadeIn"><?php echo $notification_count; ?></span>
            </a>
        </li>
        <?php } ?>
        <li class="dropdown">
            <a class="dropdown-toggle user" data-toggle="dropdown" href="#">
                <?php echo ucwords($_SESSION['clinic_name']); ?>
                <?php if($_SESSION['logo']!=""){ ?>
                <img width="50" height="50" src="logo/<?php echo $_SESSION['logo']; ?>?<?php echo rand(); ?>"  class="img-responsive img-circle user-img">
                <?php }else{ ?>
                    <img src="assets/images/user.jpg" alt="" data-src="assets/images/user.jpg"
                         data-src-retina="assets/images/user.jpg" class="img-responsive img-circle user-img">
                <?php } ?>
                <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-user animated fadeInUp">
                <li class="user-information">
                    <div class="media">
                        <a class="pull-left" href="#">
                            <?php if($_SESSION['logo']!=""){ ?>
                                <img class="media-object user-profile-image img-circle"
                                     src="logo/<?php echo $_SESSION['logo']; ?>?<?php echo rand(); ?>">
                            <?php }else{ ?>
                                <img class="media-object user-profile-image img-circle"
                                     src="assets/images/user.jpg">
                            <?php } ?>
                        </a>
                        <div class="media-body">
                            <h4 class="media-heading"> <?php echo $_SESSION['clinic_name']; ?> </h4>
                            <hr style="margin:8px auto">
                        </div>
                    </div>
                </li>
                <?php if($_SESSION['clinic_id']!=-1){ ?>
                <?php if($_SESSION['user_name']=="admin"){ ?>
                <li class="divider"></li>
                <li><a href="settings.php"><i class="fa fa-hospital-o fa-fw"></i>Settings</a></li>
                <?php } ?>
                <li class="divider"></li>
                <li><a href="password.php"><i class="fa fa-lock fa-fw"></i>Change Password</a></li>
                <?php } ?>
                <li class="divider"></li>
                <li><a href="logout.php" class="text-danger"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                </li>
            </ul>
        </li>
    </ul>
</nav>

<nav id="menu" class="navbar-default navbar-fixed-side hidden-xs" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="side-menu">
            <?php if($_SESSION['clinic_id']==-1){ ?>
                <li <?php if($page=="customers") echo "class='active'"; ?> ><a href="customers.php"><i class="fa fa-user fa-fw"></i>Customers</a></li>
            <?php }else{ ?>

                <?php if(in_array("appointment",$_SESSION['modules'])){ ?>
                    <li <?php if($page=="appointment") echo "class='active'"; ?> ><a href="online.php"><i class="fa
                    fa-calendar-plus-o fa-fw"></i>Appointments</a></li>
                <?php } ?>

                <li <?php if($page=="patient") echo "class='active'"; ?> ><a href="patients.php"><i class="fa fa-user fa-fw"></i>Patients</a></li>
                <li <?php if($page=="bill") echo "class='active'"; ?> ><a href="billing.php"><i class="fa fa-user fa-fw"></i>Billing</a></li>

                <?php if(in_array("billing",$_SESSION['modules'])){ ?>
                    <li <?php if($page=="billing") echo "class='active'"; ?> ><a href="bill_details.php"><i class="fa fa-rupee fa-fw"></i>Print Bill</a></li> 
                <?php } ?>               
               
                <?php if(in_array("user",$_SESSION['modules'])){ ?>
                    <li <?php if($page=="services") echo "class='active'"; ?> ><a href="services.php"><i class="fa
                    fa-medkit fa-fw"></i>Services</a></li>
                <?php } ?>

                <li <?php if($page=="users") echo "class='active'"; ?> ><a href="doctors.php"><i class="fa
                fa-stethoscope fa-fw"></i>Doctors</a></li>
                <li <?php if($page=="roster") echo "class='active'"; ?>><a href="roster.php"><i class="fa
                fa-calendar fa-fw"></i>Doctor Timings</a></li>
                <li <?php if($page=="leave") echo "class='active'"; ?>><a href="leave.php"><i class="fa
                fa-coffee fa-fw"></i>Leave</a></li>             
                <?php if(in_array("holiday",$_SESSION['modules'])){ ?>
                <li <?php if($page=="holiday") echo "class='active'"; ?>><a href="holiday.php"><i class="fa
                fa-smile-o fa-fw"></i>Holiday</a></li>
                <?php } ?>
                <?php if(in_array("user",$_SESSION['modules'])){ ?>
                    <li <?php if($page=="user") echo "class='active'"; ?> ><a href="user.php"><i class="fa
                    fa-user fa-fw"></i>Users</a></li>
                <?php } ?>
                <?php if(in_array("broadcast",$_SESSION['modules'])){ ?>
                <li <?php if($page=="broadcast") echo "class='active'"; ?> ><a href="broadcast.php"><i class="fa fa-mobile fa-fw"></i>Broadcast</a></li>
                <?php } ?>
            <?php } ?>
        </ul>
    </div>
</nav>
