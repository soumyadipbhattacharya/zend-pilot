<?php
use Object\Tool AS Tool;
use Object\Report AS Report;
require_once('Object/Tool.php');
require_once('Object/Controller.php');

// Assume emp id for now
$session = Tool::session();

if (isset($session['login']) && $session['login'] != '') {
    $empID = $session['login'];
} else {
    header("Location: https://".$_SERVER['HTTP_HOST']);
    exit();
}

$from_date = '';
$to_date = '';
$records = null;

if (!empty($_POST['to_do']) && $_POST['to_do'] == 'search') {
    $from_date = '';
    $to_date = '';

        if ($_POST['start_date']) {
            $from_date = $_POST['start_date'];	
            $from = explode("-",$_POST['start_date']);

            // Format '2016-10-18 00:00:00';
            $fromdate = $from[2]."-".$from[1]."-".$from[0]." 00:00:00";
            
        }
        
        if ($_POST['end_date']) {

            $to_date = $_POST['end_date'];		
            $to = explode("-",$_POST['end_date']);

            // Format '2016-10-18 00:00:00';
            $todate = $to[2]."-".$to[1]."-".$to[0]." 23:59:59";
        }

        $output = 'screen';

        if(!empty($_POST['download']) && $_POST['download'] == 'true') {
            $output = 'file';
        }
        
        $report = new Report();
        $records = $report->getRecords($fromdate,$todate, $output);
}
?>

<?php include('common/header_new.php'); ?>
<script src="js/formSubmit.js"></script> 
<style>
    .skin-purple .wrapper {background-color:#FFFFFF;}
</style>

 <script>
 $(document).ready(function(){ 
    $( ".datepicker" ).datepicker({
        dateFormat: 'd-m-yy',
        maxDate: '<?php echo date("d-m-Y") ?>',
    });	 
  }); 
</script>
</head>
<?php include('common/inner_header_new.php'); ?>

    <section class="content">
        <div class="row">
        <div class="col-xs-12"><!-- /add Form bellow -->
            <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Report page </h3>
            </div><!-- /.box-header -->
            <!-- form start -->
            <form name="revenue_report" method="post" action="" id="sbmt_frm" onSubmit()>
            <table>
                <tr>				
                    <td width="30%">
                        <div class="box-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Start Date</label>
                            <input type="text" class="form-control datepicker" name="start_date" id="start_date" required value="<?php echo $from_date;?>">
                        </div>
                        </div><!-- /.box-body -->							
                    </td>
                    <td width="30%">
                        <div class="box-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">End  Date</label>
                            <input type="text" class="form-control datepicker" name="end_date" id="end_date" required value="<?php echo $to_date;?>">
                        </div>
                        </div><!-- /.box-body -->							
                    </td>
                    <td width="30%">
                        <div >
                            <input type="hidden" name="to_do" id="to_do" value="search">
                            <input type="submit" class="btn btn-primary"  name="Submit" value="Generate" />
                            <input type="checkbox" name='download' value='true'> Download
                        </div><!-- /.box-body -->							
                    </td>						
                </tr>
            </table>				
            </form>
            </div>
        </div><!-- /.col -->
        </div>
		<div class="row">
        <?php if(isset($_POST['to_do'])) :	?>
            <div class="col-md-12 wrapper"><!-- /add Form bellow -->
                <div class="list-group margin-top">
	  <div class="list-group-item" style="overflow-y: scroll;">
                       <table width="100%" class="table">
			<thead>
				<tr>
                                    <th class="data_header_row text-center">&nbsp;</th>
                                    <th class="data_header_row text-center"><strong>Folder</strong></th>
                                    <th class="data_header_row text-center"><strong>File</strong></th>
                                    <th class="data_header_row text-center"><strong>ID</strong></th>
                                    
                                    <th class="data_header_row"></th>
                                    <th class="data_header_row text-center" colspan="2"><strong>Person</strong></th>
                                    
                                    <th class="data_header_row text-center"><strong>Entry</strong></th>
                                    <th class="data_header_row text-center"><strong>Page</strong></th>
                                    <th class="data_header_row text-center" colspan="2"><strong>Father</strong></th>

                                    <th class="data_header_row text-center" colspan="2"><strong>Mother</strong></th>

                                    <th class="data_header_row text-center" colspan="2"><strong>Spouse</strong></th>

                                    <th class="data_header_row text-center" colspan="2"><strong>Witness 1</strong></th>

                                    <th class="data_header_row text-center" colspan="2"><strong>Witness 2</strong></th>
									
                                    <th class="data_header_row text-center" colspan="4"><strong>Address</strong></th>
									
									  <th class="data_header_row text-center" colspan="2"><strong>Witness 3</strong></th>
              </tr>
                                <tr>
                                    <th class="data_header_row text-center">#</th>
                                    <th class="data_header_row text-center"><strong>Name</strong></th>
                                    <th class="data_header_row text-center"><strong>Name</strong></th>
                                    <th class="data_header_row text-center"><strong>Employee</strong></th>
                                    
                                    <th class="data_header_row text-center"><strong>Date</strong></th>
                                    <th class="data_header_row text-center"><strong>Surname</strong></th>
                                    <th class="data_header_row text-center"><strong>Name</strong></th>
                                    <th class="data_header_row text-center"><strong>Type</strong></th>
                                    <th class="data_header_row text-center"><strong>Number</strong></th>
                                    <th class="data_header_row text-center"><strong>Surname</strong></th>
                                    <th class="data_header_row text-center"><strong>Name</strong></th>
                                    <th class="data_header_row text-center"><strong>Surname</strong></th>
                                    <th class="data_header_row text-center"><strong>Name</strong></th>
                                    <th class="data_header_row text-center"><strong>Surname</strong></th>
                                    <th class="data_header_row text-center"><strong>Name</strong></th>
                                    <th class="data_header_row text-center"><strong>Surname</strong></th>
                                    <th class="data_header_row text-center"><strong>Name</strong></th>
                                    <th class="data_header_row text-center"><strong>Surname</strong></th>
                                    <th class="data_header_row text-center"><strong>Name</strong></th>
                                   
                                    <th class="data_header_row text-center"><strong>City</strong></th>
                                    <th class="data_header_row text-center"><strong>County</strong></th>
                                    <th class="data_header_row text-center"><strong>State</strong></th>
                                    <th class="data_header_row text-center"><strong>Country</strong></th>
									
									 <th class="data_header_row text-center"><strong>Surname</strong></th>
                                    <th class="data_header_row text-center"><strong>Name</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            if ($_POST['to_do'] == 'search' && count($records) > 0) :	
                                $sl=1;
                                foreach ($records as $main_record) :	
                                    $task = $main_record->task;
                                    foreach ($task as $taskItem) :
                                        $detail = $taskItem->detail['operator'];
										if(!empty($detail)){
                                        foreach($detail as $entryObject) :
                                            $entry = $entryObject->lists();			
                            ?>
                            <tr>
                                <th class="data_row text-center"><?php echo $sl;?></th>
                                <th class="data_row text-center"><?php echo (strlen($main_record->folder_name)>10)? substr($main_record->folder_name,0,10):$main_record->folder_name;?></th>
                                <th class="data_row text-center"><?php echo $main_record->file_name;?></th>
                                <th class="data_row text-center"><?php echo $taskItem->empid;?></th>
                                <?php	for($i=0; $i < count($entry); $i++) : ?>
                                <th class="data_row text-center"><?php echo $entry[$i];?></th>
                                <?php endfor; ?>
                            </tr>
                                    
                            <?php
                                            $sl++;
                                        endforeach;
										}
                                    endforeach;									
                                endforeach;		
                            endif;
                            ?>
                            </tbody>
                        </table>
                  </div>
                </div>		
            </div>
            <?php endif; ?>
        </div>  
    </section>
</body>
</html>
