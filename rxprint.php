<?php
session_start();
$module = "prescription";

include "timeout.php";
include "config.php";
$visit_id = $_GET['visit_id'];
$clinic_id = $_GET['clinic_id'];

$sql1 = "select a.*,b.visit_date from patients a,visit b where a.id=b.patient_id and b.visit_id=$visit_id";
$result1 = mysqli_query($conn, $sql1);
$row1 = mysqli_fetch_assoc($result1);

$sql = "select a.* from rx_item a where a.clinic_id=$clinic_id and a.visit_id=$visit_id";
$result = mysqli_query($conn, $sql);

?>

<table border="0" width="500px" align="center">
    <tbody>
    <tr>
        <td colspan="2">
            <table border="0" width="400" align="center" class="table table-hover table-bordered">
                <tr align="center">
                    <td colspan="2">
                        <!--                            <img src="assets/images/logo.png"><br>-->
                        <h2 style="color:black;font-weight: bold"><?php echo $_SESSION['clinic_name']; ?></h2>
                        <?php echo $_SESSION['address_line1']; ?><br>
                        <?php echo $_SESSION['address_line2']; ?><br>
                        <?php echo $_SESSION['address_line3']; ?><br>
                    </td>
                    </td></tr>
            </table>
    <tr>
        <td colspan="2">
            <table border="0" width="400" align="center" class="table table-hover table-bordered">
                <tr align="left">
                    <td colspan="3">Name: <?php echo $row1['full_name']; ?></td>
                    <td colspan="2" align="right">Date: <?php echo date("d/m/Y",strtotime($row1['visit_date'])); ?></td>
                </tr>
                <tr align="center">
                    <td colspan="5" style="font-weight: bold;">Prescription</td>
                </tr>
                <tr align="center">
                    <td>S.No</td>
                    <td>Description</td>
                    <td>Dosage</td>
                    <td>Duration</td>
                    <td>Intake</td>
                </tr>
                <?php
                $i = 1;
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <tr><td><?php echo $i; ?></td>
                        <td style="text-align: left"><?php echo ucwords($row['medicine_name']); ?></td>
                        <td style="text-align: right"><?php echo $row['dosage']; ?></td>
                        <td style="text-align: right"><?php echo $row['duration']; ?></td>
                        <td style="text-align: right"><?php echo $row['in_take']; ?></td>
                    </tr>
                    <?php
                    $i++;
                }
                ?>
            </table>
    </tbody>

</table>

<script>
    function print_bill() {
        window.print();
    }
</script>
<?php include "footer.php"; ?>
</body>
</html>