<table border="1" class='table'>
    <tbody>
    <tr style="background-color: #81888c;color:white">
        <td style="font-weight: bold" colspan="2">DENTAL CHARTING</td>
    </tr>
    <tr>
        <td colspan="2">
            <input type="hidden" name="chart_data" id="chart_data" value="<?php echo $value['chart_data']; ?>" />
            <svg id="chart" preserveAspectRatio="xMidYMid meet" viewbox="0 0 5820 740"></svg>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center">
            <input onsubmit="return submit_data()" id="save_chart" class="btn btn-info text-center"
                   type="submit" name="save_chart" value="Save Chart"/>
            <input id="clear_chart" class="btn btn-info text-center"
                   type="submit" name="clear_chart" value="Clear Chart"/>
        </td>
    </tr>
    <tr style="background-color: #81888c;color:white">
        <td style="font-weight: bold" colspan="2">GENERAL</td>
    </tr>
    <tr>
        <td width="25%" style="font-weight: bold"><label class="control-label">Chief Dental Complaint</label></td>
        <td><input value="<?php echo $value['param_1']; ?>" type="text" maxlength="100" name="param_1" class="form-control" /></td>
    </tr>
    <tr>
        <td style="font-weight: bold"><label class="control-label">Blood Pressure</label></td>
        <td><input value="<?php echo $value['param_2']; ?>" type="text" maxlength="20" name="param_2" class="form-control" /></td>
    </tr>
    <tr>
        <td style="font-weight: bold"><label class="control-label">Pulse</label></td>
        <td><input value="<?php echo $value['param_3']; ?>" type="text" maxlength="20" name="param_3" class="form-control" /></td>
    </tr>
    <tr>
        <td style="font-weight: bold"><label class="control-label">Oral Habits</label></td>
        <td><textarea rows="1" maxlength="400" name="param_5" class="form-control" /><?php echo $value['param_5']; ?></textarea></td>
    </tr>
    <tr>
        <td style="font-weight: bold"><label class="control-label">Existing Illness/Current Drugs</label></td>
        <td><textarea rows="1" maxlength="400" name="param_6" class="form-control" /><?php echo $value['param_6']; ?></textarea></td>
    </tr>
    <tr>
        <td style="font-weight: bold"><label class="control-label">Allergies</label></td>
        <td><textarea rows="1" maxlength="400" name="param_7" class="form-control" /><?php echo $value['param_7']; ?></textarea></td>
    </tr>
    <tr>
        <td style="font-weight: bold"><label class="control-label">Oral Hygiene</label></td>
        <td>
            <div class="input-group">
            <input <?php if($value['param_8']=="Excellent") echo " checked "; ?> type="radio" value="Excellent" name="param_8" />Excellent
            <input <?php if($value['param_8']=="Good") echo " checked "; ?> type="radio" value="Good" name="param_8" />Good
            <input <?php if($value['param_8']=="Fair") echo " checked "; ?> type="radio" value="Fair" name="param_8" />Fair
            <input <?php if($value['param_8']=="Poor") echo " checked "; ?> type="radio" value="Poor" name="param_8"  />Poor
            </div>
        </td>
    </tr>
    <tr>
        <td style="font-weight: bold"><label class="control-label">Calculus</label></td>
        <td>
            <div class="input-group">
                <input <?php if($value['param_9']=="None") echo " checked "; ?> type="radio" value="None" name="param_9" />None
                <input <?php if($value['param_9']=="Little") echo " checked "; ?> type="radio" value="Little" name="param_9" />Little
                <input <?php if($value['param_9']=="Moderate") echo " checked "; ?> type="radio" value="Moderate" name="param_9" />Moderate
                <input <?php if($value['param_9']=="Heavy") echo " checked "; ?> type="radio" value="Heavy" name="param_9"  />Heavy
            </div>
        </td>
    </tr>
    <tr>
        <td style="font-weight: bold"><label class="control-label">Plaque</label></td>
        <td>
            <div class="input-group">
                <input <?php if($value['param_10']=="None") echo " checked "; ?> type="radio" value="None" name="param_10" />None
                <input <?php if($value['param_10']=="Little") echo " checked "; ?> type="radio" value="Little" name="param_10" />Little
                <input <?php if($value['param_10']=="Moderate") echo " checked "; ?> type="radio" value="Moderate" name="param_10" />Moderate
                <input <?php if($value['param_10']=="Heavy") echo " checked "; ?> type="radio" value="Heavy" name="param_10"  />Heavy
            </div>
        </td>
    </tr>
    <tr>
        <td style="font-weight: bold"><label class="control-label">Gingival Bleeding</label></td>
        <td>
            <div class="input-group">
                <input <?php if($value['param_11']=="Localized") echo " checked "; ?> type="radio" value="Localized" name="param_11" />Localized
                <input <?php if($value['param_11']=="General") echo " checked "; ?> type="radio" value="General" name="param_11" />General
                <input <?php if($value['param_11']=="None") echo " checked "; ?> type="radio" value="None" name="param_11" />None
            </div>
        </td>
    </tr>
    <tr>
        <td style="font-weight: bold"><label class="control-label">Period Exam</label></td>
        <td>
            <div class="input-group">
                <input <?php if($value['param_4']=="Yes") echo " checked "; ?> type="radio" value="Yes" name="param_4" />Yes
                <input <?php if($value['param_4']=="No") echo " checked "; ?> type="radio" value="No" name="param_4" />No
            </div>
        </td>
    </tr>
    <tr style="background-color: #81888c;color:white">
        <td style="font-weight: bold" colspan="2">ORAL, SOFT TISSUE EXAMINATION</td>
    </tr>
    <tr style="font-weight: bold;background-color: lightgray">
        <td>Area</td>
        <td>Description of any problem</td>
    </tr>
    <tr>
        <td style="font-weight: bold"><label class="control-label">Pharynx</label></td>
        <td><input value="<?php echo $value['param_12']; ?>" type="text" maxlength="100" name="param_12" class="form-control" /></td>
    </tr>
    <tr>
        <td style="font-weight: bold"><label class="control-label">Tonsils</label></td>
        <td><input value="<?php echo $value['param_13']; ?>" type="text" maxlength="100" name="param_13" class="form-control" /></td>
    </tr>
    <tr>
        <td style="font-weight: bold"><label class="control-label">Soft Palate</label></td>
        <td><input value="<?php echo $value['param_14']; ?>" type="text" maxlength="100" name="param_14" class="form-control" /></td>
    </tr>
    <tr>
        <td style="font-weight: bold"><label class="control-label">Hard Palate</label></td>
        <td><input value="<?php echo $value['param_15']; ?>" type="text" maxlength="100" name="param_15" class="form-control" /></td>
    </tr>
    <tr>
        <td style="font-weight: bold"><label class="control-label">Tongue</label></td>
        <td><input value="<?php echo $value['param_16']; ?>" type="text" maxlength="100" name="param_16" class="form-control" /></td>
    </tr>
    <tr>
        <td style="font-weight: bold"><label class="control-label">Floor of Mouth</label></td>
        <td><input value="<?php echo $value['param_17']; ?>" type="text" maxlength="100" name="param_17" class="form-control" /></td>
    </tr>
    <tr>
        <td style="font-weight: bold"><label class="control-label">Buccal Mucosa</label></td>
        <td><input value="<?php echo $value['param_18']; ?>" type="text" maxlength="100" name="param_18" class="form-control" /></td>
    </tr>
    <tr>
        <td style="font-weight: bold"><label class="control-label">Lips Skin</label></td>
        <td><input value="<?php echo $value['param_19']; ?>" type="text" maxlength="100" name="param_19" class="form-control" /></td>
    </tr>
    <tr>
        <td style="font-weight: bold"><label class="control-label">Lymph Nodes</label></td>
        <td><input value="<?php echo $value['param_20']; ?>" type="text" maxlength="100" name="param_20" class="form-control" /></td>
    </tr>
    <tr>
        <td style="font-weight: bold"><label class="control-label">Occlusion</label></td>
        <td><input value="<?php echo $value['param_21']; ?>" type="text" maxlength="100" name="param_21" class="form-control" /></td>
    </tr>
    </tbody>
</table>