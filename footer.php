<script src="assets/js/jquery-2.1.4.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<?php if(isset($datepicker3)): ?>
    <script src="assets/js/bootstrap-datepicker3.min.js"></script>
<?php else: ?>
    <script src="assets/js/bootstrap-datepicker.js"></script>
<?php endif ?>

<script src="assets/js/plugins/daterangepicker/moment.min.js"></script>
<script src="assets/js/plugins/daterangepicker/daterangepicker.js"></script>
<script src="assets/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="assets/js/metisMenu.min.js"></script>
<script src="assets/js/plugins/nicescroll/jquery.nicescroll.min.js"></script>
<script src="assets/js/plugins/dataTables/jquery.dataTables.js"></script>
<script src="assets/js/plugins/dataTables/dataTables.bootstrap.js"></script>
<script src="assets/js/bootstrap-timepicker.js"></script>


<script>
    // Initialize Loadie for Page Load

    $(document).ready(function() {
        var table = $('#dataTables-example').dataTable();
		table.fnFilter('<?php echo $search; ?>'); 
		
    });
</script>
<script src="assets/js/init.js"></script>
<script>
$('.Number').keypress(function (event) {
var keycode = event.which;
if (!(event.shiftKey == false && (keycode == 46 || keycode == 8 || keycode == 37 || keycode == 39 || (keycode >= 48 && keycode <= 57)))) {
event.preventDefault();
}
});
</script>

<script>
    setInterval(function() {
        $.ajax({
            url:"notification_count.php",
            type:"post",
            success:function(data)  {
                $('#notification_count').html(data);
            }
        });
    }, 1000 * 60 * 1);
</script>