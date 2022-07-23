<?php
session_start();
$page = "billing";
$module = "billing";
include "timeout.php";
include "config.php";
$clinic_id = $_SESSION['clinic_id'];
$from_date = date("Y-m-d");
$to_date = date("Y-m-d");

if (isset($_POST['submit'])) {
    $from_date = $_POST['from_date'];
    $to_date = $_POST['to_date'];
}

$id = isset($_GET['id']) ? $_GET['id'] : 0 ;
$mode = isset($_GET['mode']) ? $_GET['mode'] : "add" ;

if($mode == "delete"){
    if(in_array("delete_bill", $_SESSION['modules'])){
        $stmt = $conn->prepare("DELETE from billing where id=?  and clinic_id=?");
        $stmt->bind_param("ss", $id,$clinic_id);
        $stmt->execute();
        $stmt = $conn->prepare("DELETE from bill_items where bill_id=?  and clinic_id=?");
        $stmt->bind_param("ss", $id,$clinic_id);
        $stmt->execute();
    }
    header("location: bill_details.php");
}

?>
<!DOCTYPE html>
<html lang="en" class="no-js">

<meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>
    <?php include "header.php"; ?>
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #section-to-print, #section-to-print * {
                visibility: visible;
            }
            #section-to-print {
                position: absolute;
                left: 0;
                top: 0;
            }
            .no-print, .no-print *
            {
                display: none !important;
            }
        }
    </style>
</head>
<body>
<div id="wrapper">
    <?php include "menu.php"; ?>
    <div id="page-wrapper" class="fixed-navbar ">
        <div class="container-fluid bg-gray">
            <div class="row" style="margin:0;">
                <div class="col-md-12">
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading">
                            <b style="color: steelblue;text-transform: capitalize">Bill Details</b>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <form class="form-inline" role="form" method="post">
                                        <div class="form-group">
                                            <label>From Date</label>
                                            <input tabindex="1"
                                                   value="<?php echo $from_date; ?>" type="date"
                                                   maxlength="10" class="form-control"  name="from_date"/>
                                            <label>To Date</label>
                                            <input tabindex="1"
                                                   value="<?php echo $to_date; ?>" type="date"
                                                   maxlength="10" class="form-control" name="to_date"/>
                                            <input required="required" class="btn btn-success" type="submit"
                                                   name="submit" value="Show"/>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-bordered" id="dataTables-example">
                                        <thead>
                                        <tr style="background-color: #81888c;color:white">
                                            <th>
                                                #
                                            </th>
                                            <th>
                                                Bill #
                                            </th>
                                            <th>
                                                Bill Date
                                            </th>
                                            <th>
                                                Patient Name
                                            </th>
                                            <th>
                                                Card No
                                            </th>
                                            <th>
                                                Mobile
                                            </th>
                                            <th style="text-align: right">
                                                Amount
                                            </th>
                                            <th style="text-align: center" width="100">
                                                Actions
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $sql = "SELECT a.*,b.mobile,b.full_name,b.card_no from billing a,patients b where a.clinic_id=$clinic_id
                                        and a.patient_id=b.id";
                                        $sql = $sql." and bill_date>='$from_date' and bill_date<='$to_date' order by billnum desc";
                                        $result = mysqli_query($conn, $sql);
                                        $i = 1;
                                        $total = 0;
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $total += $row['net_amount'];
                                            ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo $row['billnum']; ?></td>
                                                <td><?php echo date("d/m/Y",strtotime($row['bill_date'])); ?></td>
                                                <td><?php echo $row['full_name']; ?></td>
                                                <td><?php echo $row['card_no']; ?></td>
                                                <td><?php echo $row['mobile']; ?></td>
                                                <td style="text-align: right"><?php echo $row['net_amount']; ?></td>
                                                <td style="text-align: center" width="100">
                                                    <a onclick="show_bill(<?php echo $row['id']; ?>)" class="btn btn-info fa fa-print" title="Print" href="#"></a>
                                                <?php 
                                                if(in_array("delete_bill", $_SESSION['modules'])){
                                                ?>
                                                    <a title="Delete Bill" class="btn btn-danger btn-info fa fa-trash-o"
                                                       onclick="delete_confirm(<?php echo $row['id']; ?>)"  href="#"></a>
                                                <?php
                                                }
                                                ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $i++;
                                        }
                                        ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td style="text-align: right;font-weight;bold" colspan="6">Total</td>
                                            <td style="text-align: right;font-weight;bold"><?php echo $total; ?></td>
                                            <td></td>
                                        </tr>
                                        </tfoot>
                                    </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close"
                            data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12 text-center" id="section-to-print">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input onclick="window.print()" class="btn btn-success"
                           type="submit"
                           name="proceed" value="Print"/>
                    <button type="button" class="btn btn-success" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>
<script>
    function show_bill(id){
        var clinic_id = "<?php echo $clinic_id; ?>";
        var url = "bill.php?id="+id+"&clinic_id="+clinic_id;
        window.open(url);
        /*$.ajax({
            url: "bill.php",
            type: "get",
            data: {id: id,clinic_id: clinic_id},
            success: function (html) {
                $('#section-to-print').html(html);
                $('#myModal').modal('show');
            }
        });*/
    }

    function delete_confirm(id){
        if(confirm("This bill will be deleted. Please confirm.")){
            window.location.href="bill_details.php?mode=delete&id="+id;
        }
    }
</script>
</body>
</html>