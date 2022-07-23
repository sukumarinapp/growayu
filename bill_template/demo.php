<table style="width:400px" class="table" border="0" align="center" >
    <thead></thead>
    <tbody>
    <tr>
        <td colspan="6" style="text-align: center">
            <img src="logo/<?php echo $row1['logo']; ?>" width="50" height="50" />
        </td>
     </tr>
    <tr>
        <td colspan="6" style="text-align: center">
            <p style="color:black;font-weight: bold"><?php echo $row1['clinic_name']; ?></p>
            <p><?php echo  $row1['address_line1']; ?>&nbsp;
            <?php echo  $row1['address_line2']; ?></p>
            <p><?php echo  $row1['address_line3']; ?>&nbsp;
            <?php echo  $row1['pincode']; ?></p>
            <p><?php echo  $_SESSION['domain_name']; ?></p>
            <p><?php echo  $_SESSION['mobile']; ?></p>
        </td>
    </tr>
    <tr>
        <td align="left" colspan="3">Bill#: <?php echo $row2['billnum']; ?></td>
        <td style="text-align: right" align="right" colspan="3">Date: <?php echo date("d/m/Y",strtotime($row2['bill_date'])) ; ?></td>
    </tr>
    <tr>
        <td align="left" colspan="3">Patient Name: <?php echo $row2['full_name']; ?></td>
        <td style="text-align: right" align="right" colspan="3">OP No: <?php echo $row2['card_no'] ; ?></td>
    </tr>
    <tr style="font-weight: bold">
        <td>#</td>
        <td style="text-align: left">Item</td>
        <td style="text-align: right">Rate</td>
        <td style="text-align: right">Quantity</td>
        <td style="text-align: right">Amount</td>
        <td style="text-align: right">Discount</td>
    </tr>
    <?php
    $i = 1;
    while ($row3 = mysqli_fetch_assoc($result3)) {
    ?>
    <tr><td><?php echo $i; ?></td>
        <td style="text-align: left"><?php echo ucwords($row3['description']); ?></td>
        <td style="text-align: right"><?php echo $row3['price']; ?></td>
        <td style="text-align: right"><?php echo $row3['quantity']; ?></td>
        <td style="text-align: right"><?php echo $row3['amount']; ?></td>
        <td style="text-align: right"><?php echo $row3['discount']; ?></td>
    </tr>
    <?php
    $i++;
    }
    ?>
    <tr><td style="text-align: right;font-weight: bold" colspan="6">Total: <?php echo $row2['total']; ?></td></tr>
    <tr><td style="text-align: right;font-weight: bold" colspan="6">Discount: <?php echo $row2['discount_amount']; ?></td></tr>
    <tr><td style="text-align: right;font-weight: bold" colspan="6">Net Amount: <?php echo $row2['net_amount']; ?></td></tr>
    </tbody>
</table>
