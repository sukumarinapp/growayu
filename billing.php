<?php
session_start();
$page = "bill";
$module = "billing";
include "timeout.php";
include "config.php";
if (!isset($_SESSION['clinic_id'])) header("location: index.php");
$clinic_id = $_SESSION['clinic_id'];
$patient_id = isset($_GET['patient_id']) ? $_GET['patient_id'] : 0 ;

$sql = "SELECT mobile,full_name,card_no,nationality from patients where clinic_id=$clinic_id and id=$patient_id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
$patient_name = $row['full_name'];
$card_no = $row['card_no'];
$mobile = $row['mobile'];
$nationality = $row['nationality'];



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
<div id="wrapper no-print">
<?php include "menu.php"; ?>
<div id="page-wrapper" class="fixed-navbar ">
<div class="container-fluid bg-gray">
<div class="row" style="margin:0;">
<div class="col-md-12">
    <div class="login-panel panel panel-default">
        <div class="panel-heading" style="text-transform: capitalize">
            <b style="color: steelblue;text-transform: capitalize">Billing</b>
        </div>
        <form name="frm">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Patient Name</label>
                    <input value="<?php echo $patient_name; ?>" type="text" class="form-control">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label">OP No</label>
                    <input readonly value="<?php echo $card_no; ?>" type="text" class="form-control">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label">Mobile</label>
                    <input readonly value="<?php echo $mobile; ?>" type="text" class="form-control">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label">Doctor</label>
                    <select tabindex="1" id="doctor_id" name="doctor" class="form-control" required="required">
                        <option value="0">Select</option>
                        <?php
                        $sql = "select * from doctors where clinic_id=$clinic_id order by full_name";
                        $result = mysqli_query($conn, $sql);
                        while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <option
                                value="<?php echo $row['id']; ?>"><?php echo ucwords($row['full_name']); ?>-<?php echo $row['experience']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label class="control-label">Bill Date</label>
                    <input value="<?php echo date("Y-m-d"); ?>" type="date" name="bill_date" class="form-control" id="bill_date">
                </div>
            </div>
        </form>
        <form class="form-inline" role="form" method="post">
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="hidden" autofocus id="product_id"
                                    class="form-control" name="product_id" />
                            <input style="margin:0px auto;width:220px;" autofocus id="product_name"
                                    class="form-control" name="product_name" />                                
                            <input readonly oninput="calculate_amount()" required="required" type="text"
                                   maxlength="6" size="4"
                                   name="rate" id="rate_id" class="form-control Number"
                                   placeholder="Rate">
                            <input oninput="calculate_amount()" onkeydown="add_row_keydown(event)"
                                   required="required" type="text"
                                   maxlength="2" pattern="\d*"
                                   name="quantity" id="quantity" class="form-control Number"
                                   placeholder="Quantity">
                            <input readonly required="required" type="text"
                                   pattern="\d*" maxlength="2"
                                   name="amount" id="amount" class="form-control Number"
                                   placeholder="Amount">
                            <input required="required" type="text"
                                   maxlength="2" size="8"
                                   name="discount" id="discount" class="form-control Number"
                                   placeholder="Discount %">
                            <input onclick="add_row()" required="required" class="btn btn-success"
                                   type="button"
                                   name="add" value="Add"/>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="panel-body">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered" id="tab_logic">
                    <thead>
                    <tr style="background-color: #81888c;color:white">
                        <td class="text-center">
                            S.No
                        </td>
                        <td style='text-align: left'>
                            Description
                        </td>
                        <td class="text-right">
                            Rate
                        </td>
                        <td class="text-right">
                            Quantity
                        </td>
                        <td class="text-right">
                            Amount
                        </td>
                        <td class="text-right">
                            Discount
                        </td>
                        <td width="50px" class="text-center">
                            Remove
                        </td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr id='addr0'></tr>
                    </tbody>
                </table>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7">&nbsp;</div>
        <div class="col-md-2">
            <label class="control-label">Total</label>
        </div>
        <div class="col-md-3 pull-right">
            <input style="text-align: right" readonly type="text"
                   name="total_amount" id="total_amount" class="form-control">
        </div>
    </div>
    <div class="row">
        <div class="col-md-7">&nbsp;</div>
        <div class="col-md-2">
            <label class="control-label">Item Discount</label>
        </div>
        <div class="col-md-3 pull-right">
            <input style="text-align: right" readonly type="text" name="discount_amount" id="discount_amount"
                   class="form-control">
        </div>
    </div>
    <div class="row">
        <div class="col-md-7">&nbsp;</div>
        <div class="col-md-2">
            <label class="control-label">Bill Discount</label>
        </div>
        <div class="col-md-3 pull-right">
            <input pattern="\d*" onselectstart="return false" onpaste="return false;" onCopy="return false" onCut="return false" onDrag="return false" onDrop="return false"
                oninput="calculate_discount()" onkeyup="calculate_discount()" onchange="calculate_discount()" style="text-align: right" type="text" maxlength="4" name="bill_discount" id="bill_discount"
                   class="form-control Number">
        </div>
    </div>
    <div class="row">
        <div class="col-md-7">&nbsp;</div>
        <div class="col-md-2">
            <label class="control-label">Net Total</label>
        </div>
        <div class="col-md-3 pull-right">
            <input style="text-align: right" readonly type="text"
                   name="net_amount" id="net_amount" class="form-control">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 text-center">
            <input onclick="submit_data()" id="save_button" required="required"
                   class="btn btn-success text-center"
                   type="button"
                   name="save" value="Save"/>

        </div>
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
                <button type="button" class="close no-print"
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
                <button type="button" class="btn btn-success no-print" data-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
</div>
<?php include "footer.php"; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>

<script>
var i = 0;
function add_row() {
    var amount = $('#amount').val().trim();
    if (amount != "") {
        var num = 0;
        for (var j = 0; j < i; j++) {
            if ($("#addr" + (j)).html() != undefined) {
                num++;
            }
        }
        var item_id = $("#product_id").val();
        var item_name = $("#product_name").val();
        var item_rate = $('#rate_id').val();
        var item_quantity = $('#quantity').val();
        var discount = 0;
        if($('#discount').val().trim()!=""){
            discount = parseFloat($('#discount').val());
        }
        discount = amount * discount / 100;
        $('#addr' + i).html("<td style='text-align: center' class='serial_num'><span class='sl_no'>" + (num + 1) + "</span></td>"
        + "<td style='text-align: left'><input value='" + item_id + "' name='item_id[]' type='hidden'>"
        + "<input value='" + item_name + "' name='item_name[]' type='hidden'>"
        + "<input value='" + item_rate + "' name='item_rate[]' type='hidden'>"
        + "<input value='" + item_quantity + "' name='item_quantity[]' type='hidden'>"
        + "<input value='" + amount + "' name='item_amount[]' type='hidden'>"
        + "<input value='" + discount + "' name='discount[]' type='hidden'>"
        + item_name + "</td>"
        + "<td style='text-align: right'>" + item_rate + "</td>"
        + "<td style='text-align: right'>" + item_quantity + "</td>"
        + "<td style='text-align: right'>" + amount + "</td>"
        + "<td style='text-align: right'>" + discount + "</td>"
        + "<td width='50px' style='text-align: center' valign='middle'><a title='Remove' class='btn btn-info btn-danger fa fa-remove' href='#' onclick='delete_row(" + i + ")'></a></td>");
        $('#tab_logic').append('<tr id="addr' + (i + 1) + '"></tr>');
        i++;
        $("#product_name").val('');
        $('#rate_id').val("");
        $('#quantity').val("");
        $('#amount').val("");
        $('#discount').val("");
        $("#product_name").focus();

        var total_amount = 0.0;
        var net_amount = 0.0;
        var item_amount = $('input[name="item_amount[]"]');
        var dis = $('input[name="discount[]"]');
        var discount_amount = 0.0;
        for (var j = 0; j < i; j++) {
            itm_amt = 0;
            if (item_amount.eq(j).val() != undefined) {
                var discount = 0.0;
                if (dis.eq(j).val() != undefined) {
                    discount = parseFloat(dis.eq(j).val());
                }
                var itm_amt = parseFloat(item_amount.eq(j).val())
                net_amount = net_amount + itm_amt;
                if (discount != 0) {
                    itm_amt = itm_amt - discount;
                    discount_amount = discount_amount + discount;
                }
                total_amount = total_amount + itm_amt;
            }
        }
        $('#total_amount').val(net_amount);
        $('#discount_amount').val(discount_amount);
        $('#net_amount').val(total_amount);
    }
}

function delete_row(row) {
    $("#addr" + (row)).remove();
    var num = 1;
    for (var j = 0; j < i; j++) {
        //console.log($("#addr"+(j)).html());
        if ($("#addr" + (j)).html() != undefined) {
            $('#addr' + j + ' .sl_no').html(num);
            num++;
        }
    }

    var total_amount = 0.0;
    var net_amount = 0.0;
    var item_amount = $('input[name="item_amount[]"]');
    var dis = $('input[name="discount[]"]');
    var discount_amount = 0.0;
    for (var j = 0; j < i; j++) {
        itm_amt = 0;
        if (item_amount.eq(j).val() != undefined) {
            //console.log(discount);
            var discount = 0;
            if (dis.eq(j).val() != undefined) {
                discount = parseFloat(dis.eq(j).val());
            }
            var itm_amt = parseFloat(item_amount.eq(j).val())
            net_amount = net_amount + itm_amt;
            if (discount != 0) {
                itm_amt = itm_amt - discount;
                discount_amount = discount_amount + discount;
            }
            total_amount = total_amount + itm_amt;
        }
    }
    $('#total_amount').val(net_amount);
    $('#discount_amount').val(discount_amount);
    $('#net_amount').val(total_amount);
}

function add_row_keydown(event) {
    if (event.which == 13 || event.keyCode == 13) {
        //add_row();
    }
}

function submit_data() {
    var doctor_id = $('#doctor_id').val();
    var patient_id = "<?php echo $patient_id; ?>";
    var total_amount = parseFloat($('#total_amount').val());
    var discount_amount = parseFloat($('#discount_amount').val());
    var bill_discount = parseFloat($('#bill_discount').val());
    discount_amount = discount_amount + bill_discount;
    var net_amount = parseFloat($('#net_amount').val());
    if (net_amount <= 0) {
        alert("Net Total should be greater than zero");
        return;
    }
    $("#save_button").prop("disabled", true);
    var item_id = $('input[name="item_id[]"]');
    var item_quantity = $('input[name="item_quantity[]"]');
    var item_amount = $('input[name="item_amount[]"]');
    var discount = $('input[name="discount[]"]');
    var bill_date = $('#bill_date').val();
    var sales = new Array();
    for (var j = 0; j < i; j++) {
        var item_amount2 = ~~item_amount.eq(j).val();
        if(item_amount2!=0) {
            var dis2=0;
            if(discount.eq(j).val()!=undefined){
                dis2=discount.eq(j).val();
            }
            var record = {
                'item_id': item_id.eq(j).val(),
                'item_quantity': item_quantity.eq(j).val(),
                'item_amount': item_amount2,
                'discount': dis2
            };
            sales.push(record);
        }
    }

    discount_amount = discount_amount || 0;

    var sales_data = JSON.stringify(sales);
    $.ajax({
        type: 'POST',
        url: 'save_sales.php',
        data: {
            patient_id: patient_id,
            doctor_id: doctor_id,
            bill_date: bill_date,
            sales: sales_data,
            amount: total_amount,
            net_amount: net_amount,
            discount_amount: discount_amount
        },
        success: function (response) {
            var bill = JSON.parse(response);
            //console.log(bill);

            for (var j = 0; j < i; j++) {
                $("#addr"+(j)).html('');
            }
            i = 0;
            $("#total_amount").val("");
            $("#discount_amount").val("");
            $("#bill_discount").val("");
            $("#net_amount").val("");
            alert("Bill saved successfully.\nBill no: "+bill.billnum);
            //show_bill(bill.bill_id);
            $("#save_button").prop("disabled", false);
            //window.open('bill.php?id='+bill.bill_id, '_blank');
            window.location.href = 'bill_details.php';
        },
        error : function(error){
            console.log(error);
        }
    });
}

$("input[name='quantity']").keydown(function () {
    var item_quantity = $('#quantity').val();
    if (item_quantity != "") {
        //add_row();
        //$("input[name='add']").focus();
    }
});

$("select[name='product_id']").keydown(function () {
    var product_id = $('#product_id').val();
    if (product_id != "") {
        $("input[name='quantity']").focus();
    }
});

function calculate_discount() {
    var total_amount = ~~parseFloat($('#total_amount').val());
    if(total_amount=="NaN") total_amount=0;
    var discount_amount = ~~parseFloat($('#discount_amount').val());
    if(discount_amount=="NaN") discount_amount=0;
    var bill_discount = ~~parseFloat($('#bill_discount').val());
    if(bill_discount=="NaN") bill_discount=0;
    discount_amount = discount_amount + bill_discount;
    console.log(discount_amount);
    var net_amount = total_amount - discount_amount;
    if(net_amount>0){
        $('#net_amount').val(net_amount);
    }else{
        $('#net_amount').val("0");
    }
}

function calculate_amount() {
    var rate = $('#rate_id').val();
    var quantity = $('#quantity').val();
    if (quantity != "" && rate != "") {
        var amount = rate * quantity;
        $('#amount').val(amount);
    } else {
        $('#amount').val("");
    }
}

function load_rate() {
    var id = $('#product_id').val();
    $.ajax({
        url: "load_rate.php",
        type: "post",
        data: {id: id},
        success: function (data) {
            $('#rate_id').val(data);
            $('#amount').val(data);
            $('#quantity').val("1");
            $('#quantity').focus();
        }
    });
}

function show_bill(id){
    var clinic_id = "<?php echo $clinic_id; ?>";
    $.ajax({
        url: "bill.php",
        type: "get",
        data: {id: id,clinic_id: clinic_id},
        success: function (html) {
            $('#section-to-print').html(html);
            $('#myModal').modal('show');
        }
    });
}

$('#myModal').on('hidden.bs.modal', function () {
    window.location.href = "bill_details.php";
})

$(document).ready(function () {
    var nationality = "<?php echo $nationality; ?>";
    $('#product_name').typeahead({
        source: function (query, process) {
            query = query + "~" + nationality;
            $.ajax({
                url: "get_service.php",
                data: 'query=' + query,
                dataType: "json",
                type: "POST",
                success: function (data) {
                    return process(data);
                }
            });
        },
        displayText: function (item) {
            if(item.code){
                return item.code +" "+item.description;
            }else{
                return item.description;    
            }
            
        },
        afterSelect: function (item, element) {
            $("#product_id").val(item.id); 
            $("#doctor_id").val(item.doctor_id);              
            load_rate();              
        }
    });
});

</script>

</body>
</html>