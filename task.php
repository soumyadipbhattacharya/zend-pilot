<!DOCTYPE html>
<html lang="en">
<head>
  <title>Form Design</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="js/formSubmit.js"></script>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <style>
  .ui-autocomplete-loading {
    background: white url("images/ui-anim_basic_16x16.gif") right center no-repeat;
  }
  </style>
  
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script src="js/autocomplete.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.6.8-fix/jquery.nicescroll.min.js"></script>
  <link rel="stylesheet" href="css/style.css"/>
  <style>
  .skin-purple .wrapper {background-color:#FFFFFF;}
  </style>

  <?php
    $taskList = array();
    foreach ($task as $taskItem) :
        $operator = $taskItem->detail['operator'];
        if(is_array($operator)) {
            foreach($operator as $entry) {
                $list = $entry->lists();
                $list[] = $taskItem->status;
                $header = $entry->getHeader();

                $arrayList = array_combine($header,$list);
                $arrayList['tid'] = $taskItem->tid;
                $arrayList['uid'] = $taskItem->uid;
                $taskList[$taskItem->tid][] = $arrayList;
            }
        }
    endforeach;
  ?>
 
 <script>
    $( function() {

        // This is for shorting function
    	$( "#sortable" ).sortable({
    		stop: function( event, ui ) {
        		var record = {uid:"<?php echo $record->id ?>", 'order': {}};
				var tbody = $(event.target);
				var order = 1;
				tbody.children().each( function() {
					record.order[order++] = $(this).children().first().text();
				});

				$.post( "updateOrder.php", record)
				  .done(function( data ) {
					  if (data = 'success') {
				    	$('.list-group margin-top').fadeOut(1000).fadeIn(500);
					  } else {
						  alert('Some error happen! Try again!');
					  }
				});
            }
    	});
    	$( "#sortable" ).disableSelection();

    	// This is for add - reset toggle
    	$( "input:reset" ).click( function() { 
        	$( "#tid" ).val('0');
        	$(this).addClass('hidden');
        	$(this).parent("div").children("input:submit").val("Add More");
        	if (!$( "#adition" ).is( ":checked" )) {
    			$( "#adition" ).click();
    		}
        });

        // Alert for next record
        <?php if (!empty($taskList) && count($taskList) < MINIUM_RECORD) :?>
        $( "#nextRecord" ).submit( function(e) {
			e.preventDefault();
			$( "#dialog-confirm" ).dialog({
			      resizable: false,
			      height: "auto",
			      width: 400,
			      modal: true,
			      buttons: {
			    	Cancel: function() {
				          $( this ).dialog( "close" );
				    },
			        "Submit Less Records": function() {
			          $( this ).dialog( "close" );
			          $( "#nextRecord" ).unbind("submit");
			          
			          $( "#nextRecord" ).children("input:submit").click();
			        }
			      }
			  });
        });
        <?php endif; ?>
  	});
    </script>
</head>
<body>
<?php


include('common/inner_header_new.php');

 //echo "Logged in Employee: ".$_SESSION['login'];
?>
<div class="container margin-top margin-bottom" style="background-color:#FFFFFF;">
  <div class="row border-all">
    <!-- list preview section -->
    <div class="col-md-12 col-sm-12 border-bottom">
      <div class="margin-bottom min-hi">
        <div class="full-width set-posi">
          <!-- <div class="col-sm-12"> -->
            <!-- <button type="button" class="btn btn-danger btn-close">close</button> -->
            
            <!-- <img src="img/cut.jpg" height="100" class="img-responsive"/> -->
            <style>
              .prev-zoom {
                background-image: url('uploads/<?php echo $record->folder_name.'/'.$record->file_name?>');
				background-size: cover;
              }
            </style>
			<div class="fix-onscroll">
            <span class="glyphicon glyphicon-remove-sign btn-close" aria-hidden="true"></span>
            <span id="zoomIn" class="glyphicon glyphicon-zoom-in zoomIn" value="0"></span>
            <span class="glyphicon glyphicon-zoom-out zoomOut"></span>
            <div id="wide" class="wide">
              <div class="prev-zoom">
               
               <div id="slider"></div>
             </div>
            </div>
           	 
			</div>
        <!-- </div> -->
      </div>
      <div class="list-group margin-top ">
	  <div class="list-group-item listItem">
	  <?php 
	  if(!empty($taskList)): ?>
		<table width="100%" class="table">
			<thead>
				<tr>
					<th class="color-c-bgreen">#</th>
					<th class="color-c-blue">FIRST NAME</th>
					<th class="color-c-blue">LAST NAME</td>
                    <th class="color-c-blue text-center" colspan="3">BIRTH</th>
                    <th class="color-c-blue text-center" colspan="3">BAPTISM</th> 
                    <th class="color-c-blue">FATHER'S NAME</th>
                    <th class="color-c-blue">LAST NAME</td> 
                    <th class="color-c-blue">MOTHER'S NAME</th>                   
                    <th class="color-c-blue">LAST NAME</th> 
                    <th class="color-c-blue hidden">SPOUSE FIRST NAME</th> 
                    <th class="color-c-blue hidden">SPOUSE LAST NAME</th>  
                    <th class="color-c-blue hidden">WITNESS 1 FIRST NAME</th>                   
                    <th class="color-c-blue hidden">WITNESS 1 LAST NAME</th> 
                    <th class="color-c-blue hidden">WITNESS 2 FIRST NAME</th> 
                    <th class="color-c-blue hidden">WITNESS 2 LAST NAME</th> 
                    <th class="color-c-blue hidden">WITNESS 3 FIRST NAME</th> 
                    <th class="color-c-blue hidden">WITNESS 3 LAST NAME</th>                    
                    <th class="color-c-blue hidden">BIRTH CITY</th>
                    <th class="color-c-blue hidden">BIRTH COUNTY</th>
                    <th class="color-c-blue hidden">BIRTH STATE</th>
                    <th class="color-c-blue hidden">BIRTH COUNTRY</th>  
                    <th class="color-c-blue hidden">BAPTISM CITY</th>
                    <th class="color-c-blue hidden">BAPTISM COUNTY</th>
                    <th class="color-c-blue hidden">BAPTISM STATE</th>
                    <th class="color-c-blue hidden">BAPTISM COUNTRY</th>                  
                    <th class="color-c-blue">PAGE</th>
                    <th class="color-c-blue">STATUS</th>
                    <th class="color-c-blue text-center"> ACTION </th>
				</tr>
			</thead>
			<tbody id="sortable">
	  		<?php
	        foreach ($taskList as $taskID => $taskItem) :
		      
                $entryItem = $taskItem[0];
                $birthEntry = $baptismEntry = '';

                foreach ($taskItem as $entryItemEach) {
                    switch ($entryItemEach["RECORD TYPE"]) {
                        case "birth":
                            $birthEntry = $entryItemEach;
                            break;
                        case "baptism":
                            $baptismEntry = $entryItemEach;
                            break;
                        default:
                            // more type can be added here
                            break;
                    }
                }

                if (count($baptismEntry)) {
                    $entryItem = $baptismEntry;
                } elseif(count($birthEntry)) {
                    $entryItem = $birthEntry;
                }
                
                // setting the QC color code
                if(!empty($qc[$taskID])) {
                    $qcItem = $qc[$taskID];
                    $rowClass = '';
                    
                    if ($qcItem->status == "negetive") {
                        $rowClass = "danger";
                    } elseif ($qcItem->status == "positive" && 
                        !empty($qcItem->start_time) && 
                        $qcItem->start_time != '0000-00-00 00:00:00' &&
                        $qcItem->start_time != $qcItem->end_time
                    ) {
                        $rowClass = "success";
                    } elseif (empty($qcItem->start_time) || $qcItem->start_time == '0000-00-00 00:00:00' ) {
                        $rowClass = "";
                    }
                    
                    if (strpos($qcItem->feedback, 'New Entry') !== false) {
                        $rowClass .= " text-bold";
                    }
                }

            ?>  
				<tr class="<?php echo empty($rowClass)? '': $rowClass; ?>">
                <!--
                    [DATE] => 5646
                    [RECORD TYPE] => birth
                    [PAGE] => 4
                    [FATHER'S LAST NAME] => SDFSF
                    [FATHER'S FIRST NAME] => RDFS
                    [MOTHER'S LAST NAME] => SDFSF
                    [MOTHER'S FIRST NAME] => SDGGJFG
                    [WITNESS 1 LAST NAME] => 
                    [WITNESS 1 FIRST NAME] => 
                    [WITNESS 2 LAST NAME] => 
                    [WITNESS 2 FIRST NAME] => 
                    [SPOUSE LAST NAME] => 
                    [SPOUSE FIRST NAME] => 
                    [CITY] => Boston
                    [COUNTY] => Suffolk
                    [STATE] => Massachusetts
                    [COUNTRY] => United-States
                    [WITNESS 3 LAST NAME] => 
                    [WITNESS 3 FIRST NAME] => 
                    [STATUS] => done
                    [tid] => 1
                    [uid] => 9
                    -->

					<td><?php echo $entryItem['tid']; ?></td>
					
                    
                    <td><?php echo $entryItem["FIRST NAME"] ?></td>
                    <td><?php echo $entryItem["LAST NAME"] ?></td>
                    
					<td><?php echo (!empty($birthEntry['Day']))? $birthEntry['Day']:''; ?></td>
					<td><?php echo (!empty($birthEntry['Month']))? $birthEntry['Month']:''; ?></td>
					<td><?php echo (!empty($birthEntry['Year']))? $birthEntry['Year']:''; ?></td>
					
					<td><?php echo (!empty($baptismEntry['Day']))? $baptismEntry['Day']:'';	 ?></td>
					<td><?php echo (!empty($baptismEntry['Month']))? $baptismEntry['Month']:''; ?></td>
					<td><?php echo (!empty($baptismEntry['Year']))? $baptismEntry['Year']:''; ?></td>
					
                    <td><?php echo $entryItem["FATHER'S FIRST NAME"] ?></td>
                    <td><?php echo $entryItem["FATHER'S LAST NAME"] ?></td>
                    <td><?php echo $entryItem["MOTHER'S FIRST NAME"] ?></td>
                    <td><?php echo $entryItem["MOTHER'S LAST NAME"] ?></td>
                    
                    <td class="hidden"><?php echo $entryItem["SPOUSE FIRST NAME"] ?></td>
                    <td class="hidden"><?php echo $entryItem["SPOUSE LAST NAME"] ?></td>
                    <td class="hidden"><?php echo $entryItem["WITNESS 1 FIRST NAME"] ?></td>
                    <td class="hidden"><?php echo $entryItem["WITNESS 1 LAST NAME"] ?></td>
                    <td class="hidden"><?php echo $entryItem["WITNESS 2 FIRST NAME"] ?></td>
                    <td class="hidden"><?php echo $entryItem["WITNESS 2 LAST NAME"] ?></td>
                    <td class="hidden"><?php echo $entryItem["WITNESS 3 FIRST NAME"] ?></td>
                    <td class="hidden"><?php echo $entryItem["WITNESS 3 LAST NAME"] ?></td>

                    
                    <td class="hidden"><?php echo (!empty($birthEntry['CITY']))? $birthEntry['CITY']:''; ?></td>
                    <td class="hidden"><?php echo (!empty($birthEntry['COUNTY']))? $birthEntry['COUNTY']:''; ?></td>
                    <td class="hidden"><?php echo (!empty($birthEntry['STATE']))? $birthEntry['STATE']:''; ?></td>
                    <td class="hidden"><?php echo (!empty($birthEntry['COUNTRY']))? $birthEntry['COUNTRY']:''; ?></td>
                    <td class="hidden"><?php echo $entryItem["CITY"] ?></td>
                    <td class="hidden"><?php echo $entryItem["COUNTY"] ?></td>
                    <td class="hidden"><?php echo $entryItem["STATE"] ?></td>
                    <td class="hidden"><?php echo $entryItem["COUNTRY"] ?></td>

                    <td><?php echo $entryItem["PAGE"] ?></td>
					<td><?php echo $entryItem["STATUS"] ?></td>
                    <td class="icon-all">
                        <span style="color:red" class="glyphicon glyphicon-remove rmv"></span>
                        <span class="glyphicon glyphicon-edit edit-list"></span>
                        <span class="glyphicon glyphicon-eye-open view"  data-toggle="modal" data-target="#modal"></span>
                    </td>
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
        <?php endif; ?>
		</div>
      </div>
    </div>
  </div>
    <!-- left side image section -->
    <div class="col-md-5 col-sm-12 left">
      <div class="total-v-img margin-top">
        <div class="img-perticuler">
          <img src="uploads/<?php echo $record->folder_name.'/'.$record->file_name?>" class="center-block" width="370"/>
          <div id="capture" class="capture-image"></div>
        </div>
		<form name="myform1" id="nextRecord" action="" method="post">
		<input type="hidden" name="new_record" value="true">
		<div class="btn btn-danger">
 			<input id="checkbox" type="checkbox" name="end_task" value="true"> End Task
		</div>
		<input type="submit" name="submit" class="btn btn-primary nxt-absolute" value="Next">
		</form>
      </div>
    </div>
    <!-- right section field section -->
    <div class="col-md-7 col-sm-12 right border-left">
      <form name="myform" action="" method="post" id="add_more">
        <div class="row margin-top-2">
          <div class="col-sm-12"><label class="color-c-blue">Candidate information</label></div>
          <div class="form-group col-sm-6">
            <input type="text" class="form-control rq" id="f_name" placeholder="FIRST NAME" name="first_name" pattern="[A-Za-z/./ /,/'/-]{2,30}" title="Please index these as we customarily do:  'Michl., Michael,' 'Patt., Patrick,' 'Mary, Maria' 'Quin, Quinn' and so on."required/>
          </div>
          <div class="form-group col-sm-6">
            <input type="text" class="form-control rq" id="sur_name" placeholder="LAST NAME" pattern="[A-Za-z/./ /,/'/-]{2,30}" title="Ten letter alphabet only" name="last_name" required/>
          </div>
		  </div>
		  <div class="row margin-bottom">
		  <?php
		  //php format should be like d-F-Y
		  ?>
          <div class="form-group col-sm-6"><label class="color-c-blue">Birth Date</label>
		  <input type="number" placeholder="Birth Date" id="birth_date" name="birth_date" min="00" max="31" title="Please put Birth date" class="form-control birth">
		  <select name="birth_month" class="form-control birth" id="birth_month">
		  <option value="">N/A</option>
		  <option value="January">Jan</option>
		  <option value="February">Feb</option>
		  <option value="March">Mar</option>
		  <option value="April">Apr</option>
		  <option value="May">May</option>
		  <option value="June">June</option>
		  <option value="July">July</option>
		  <option value="August">Aug</option>
		  <option value="September">Sept</option>
		  <option value="October">Oct</option>
		  <option value="November">Nov</option>
		  <option value="December">Dec</option>
		  </select>
		  <input type="text" placeholder="Birth Year" name="old_birth_year" id="old_birth_year" title="Please put Birth Year" pattern="[0-9]{4}" class="form-control birth">
		  
		  <input type="hidden" class="form-control" id="y-birth" placeholder="YEAR OF BIRTH" name="birth_year" />
          </div>
		  <div class="form-group col-sm-6"><label class="color-c-blue">Baptism Date</label>
		  <input type="number" placeholder="Baptism Date" name="baptism_date" id="baptism_date" min="00" max="31" title="Please put Baptism date" class="form-control baptism">
		  <select name="baptism_month" class="form-control baptism" id="baptism_month">
		  <option value="">N/A</option>
		  <option value="January">Jan</option>
		  <option value="February">Feb</option>
		  <option value="March">Mar</option>
		  <option value="April">Apr</option>
		  <option value="May">May</option>
		  <option value="June">June</option>
		  <option value="July">July</option>
		  <option value="August">Aug</option>
		  <option value="September">Sept</option>
		  <option value="October">Oct</option>
		  <option value="November">Nov</option>
		  <option value="December">Dec</option>
		  </select>
		  <input type="text" placeholder="Baptism Year" name="old_baptism_year" id="old_baptism_year" title="Please put Baptism Year" pattern="[0-9]{4}" class="form-control baptism" required>
            <input type="hidden" class="form-control" id="y-bapti" placeholder="YEAR OF BAPTISM" name="baptism_year"/>
          </div>
        </div>
        <div class="row margin-bottom">
          <div class="col-xs-4"></div>
          <div class="col-xs-4 text-center color-c-blue"><label>FIRST NAME</label></div>
          <div class="col-xs-4 text-center color-c-blue"><label>LAST NAME</label></div>
        </div>
        <hr style="margin-top:0;"/>
        <div class="row margin-bottom">
          <div class="col-xs-4"><label>FATHER</label></div>
          <div class="col-xs-4"><input type="text" class="form-control rq" id="father_f_name" name="father_first_name" pattern="[A-Za-z/./ /,/'/-]{2,30}" title="Ten letter alphabet only" required/></div>
          <div class="col-xs-4"><input type="text" class="form-control rq" id="father_sur_name" name="father_last_name" pattern="[A-Za-z/./ /,/'/-]{2,30}" title="Ten letter alphabet only" required/></div>
        </div>
        <div class="row margin-bottom">
          <div class="col-xs-4"><label>MOTHER</label></div>
          <div class="col-xs-4"><input type="text" class="form-control rq" id="mother_f_name" name="mother_first_name" pattern="[A-Za-z/./ /,/'/-]{2,30}" title="Ten letter alphabet only" required/></div>
          <div class="col-xs-4"><input type="text" class="form-control rq" id="mother_sur_name" name="mother_last_name" pattern="[A-Za-z/./ /,/'/-]{2,30}" title="index both the married and maiden names in the mother’s last name column as we customarily do" required/></div>
        </div>
        <div class="row margin-bottom">
          <div class="col-xs-4"><label>SPOUSE</label></div>
          <div class="col-xs-4"><input type="text" class="form-control" id="spouse_f_name" name="spouse_first_name" pattern="[A-Za-z/./ /,/'/-]{2,30}" title="Ten letter alphabet only" /></div>
          <div class="col-xs-4"><input type="text" class="form-control" id="spouse_sur_name" name="spouse_last_name" pattern="[A-Za-z/./ /,/'/-]{2,30}" title="Ten letter alphabet only" /></div>
        </div>
        <div class="row margin-bottom">
          <div class="col-xs-4"><label>WITNESS 1</label></div>
          <div class="col-xs-4"><input type="text" class="form-control" id="baptism_witness1_f_name" name="baptism_wittness1_first_name" pattern="[A-Za-z/./ /,/'/-]{2,30}" title="Ten letter alphabet only" /></div>
          <div class="col-xs-4"><input type="text" class="form-control" id="baptism_witness1_sur_name" name="baptism_wittness1_last_name" pattern="[A-Za-z/./ /,/'/-]{2,30}" title="Ten letter alphabet only" /></div>
        </div>
        <div class="row margin-bottom">
          <div class="col-xs-4"><label>WITNESS 2</label></div>
          <div class="col-xs-4"><input type="text" class="form-control" id="baptism_witness2_f_name" name="baptism_wittness2_first_name" pattern="[A-Za-z/./ /,/'/-]{2,30}" title="Ten letter alphabet only" /></div>
          <div class="col-xs-4"><input type="text" class="form-control" id="baptism_witness2_sur_name" name="baptism_wittness2_last_name" pattern="[A-Za-z/./ /,/'/-]{2,30}" title="Ten letter alphabet only" /></div>
        </div>
        <div class="row margin-bottom">
          <div class="col-xs-4"><label>WITNESS 3</label></div>
          <div class="col-xs-4"><input type="text" class="form-control" id="baptism_witness3_f_name" name="baptism_wittness3_first_name" pattern="[A-Za-z/./ /,/'/-]{2,30}" title="Ten letter alphabet only" /></div>
          <div class="col-xs-4"><input type="text" class="form-control" id="baptism_witness3_sur_name" name="baptism_wittness3_last_name" pattern="[A-Za-z/./ /,/'/-]{2,30}" title="Ten letter alphabet only" /></div>
        </div>
				<div class="row">
					<div class="col-xs-12">
						<label><input type="checkbox" name="address" value="default" id="adition" checked="checked"/>&nbsp;&nbsp;Default Address</label>
					</div>
				</div>
				<div class="add-aditional hidden">
	        <div class="row margin-top ">
	          <div class="col-xs-3"></div>
	          <div class="col-xs-2 text-center color-c-blue">CITY</div>
	          <div class="col-xs-2 text-center color-c-blue">COUNTY</div>
	          <div class="col-xs-2 text-center color-c-blue">STATE</div>
	          <div class="col-xs-2 text-center color-c-blue">COUNTRY</div>
	        </div>
	        <hr style="margin-top:0"/>
			<?php 
			$address_detail = $record->detail;
			$address_detail = unserialize($address_detail);
			//print_r ($address_detail);
			?>
	        <div class="row margin-bottom">
	          <div class="col-xs-3"><label>BIRTH</label></div>
	          <div class="col-xs-2 birth-address"><input type="text" class="form-control brthAdd" name="birth_city" pattern="[A-Za-z/./ /,/-]{2,30}" title="Ten letter alphabet only" value="<?php echo $address_detail['city'];?>" /></div>
	          <div class="col-xs-2 birth-address"><input type="text" class="form-control brthAdd" name="birth_county" pattern="[A-Za-z/./ /,/-]{2,30}" title="Ten letter alphabet only" value="<?php echo $address_detail['county'];?>"/></div>
	          <div class="col-xs-2 birth-address"><input type="text" class="form-control brthAdd" name="birth_state" pattern="[A-Za-z/./ /,/-]{2,30}" title="Ten letter alphabet only" value="<?php echo $address_detail['state'];?>"/></div>
	          <div class="col-xs-2 birth-address"><input type="text" class="form-control brthAdd" name="birth_country" pattern="[A-Za-z/./ /,/-]{2,30}" title="Ten letter alphabet only" value="<?php echo $address_detail['country'];?>"/></div>
	        </div>
	        <div class="row margin-bottom">
	          <div class="col-xs-3"><label>BAPTISM</label></div>
	          <div class="col-xs-2 bapti-address"><input type="text" class="form-control baptiAdd" name="baptism_city" pattern="[A-Za-z/./ /,/-]{2,30}" title="Ten letter alphabet only" value="<?php echo $address_detail['city'];?>"/></div>
	          <div class="col-xs-2 bapti-address"><input type="text" class="form-control baptiAdd" name="baptism_county" pattern="[A-Za-z/./ /,/-]{2,30}" title="Ten letter alphabet only" value="<?php echo $address_detail['county'];?>"/></div>
	          <div class="col-xs-2 bapti-address"><input type="text" class="form-control baptiAdd" name="baptism_state" pattern="[A-Za-z/./ /,/-]{2,30}" title="Ten letter alphabet only" value="<?php echo $address_detail['state'];?>"/></div>
	          <div class="col-xs-2 bapti-address"><input type="text" class="form-control baptiAdd" name="baptism_country" pattern="[A-Za-z/./ /,/-]{2,30}" title="Ten letter alphabet only" value="<?php echo $address_detail['country'];?>"/></div>
	        </div>
			</div>
				<div class="row">
					<div class="col-xs-12">
						<label><input type="checkbox" id="status" name="status" value="escalate"/>&nbsp;&nbsp;ILLEGIBLE/UNCLEAR</label>
					</div>
				</div>
        <div class="row" >
		<input type="hidden" class="form-control" id="birth_witness1_f_name" name="birth_wittness1_first_name" />
		<input type="hidden" class="form-control" id="birth_witness1_l_name" name="birth_wittness1_last_name" />
		<input type="hidden" class="form-control" id="birth_witness2_f_name" name="birth_wittness2_first_name" />
		<input type="hidden" class="form-control" id="birth_witness2_l_name" name="birth_wittness2_last_name" />
		<input type="hidden" class="form-control" id="birth_witness3_f_name" name="birth_wittness3_first_name" />
		<input type="hidden" class="form-control" id="birth_witness3_l_name" name="birth_wittness3_last_name" />
		<input type="hidden" name="to_do" value="add_more">
		<input type="hidden" id='tid' name="tid" value="<?php echo $tid;?>">
		<input type="hidden" name="uid" value="<?php echo $uid;?>">
		<input type="hidden" name="start_time" value="<?php echo date("Y-m-d H:i:s");?>">
          <div class="col-sm-12">
          <input type="reset" class="hidden add_more_btn btn btn-success pull-left margin-top margin-bottom" value="Reset New Entry">
		  <input type="submit" class="add_more_btn btn btn-success pull-right margin-top margin-bottom" id="add_more" value="Add">
		 <!-- <button type="submit" class="btn btn-info pull-right margin-top margin-bottom common-btn">Submit</button>-->
		  </div>
        </div>
     	</form>
    	</div>
	</div>
</div>
<!-- Modal -->
<div id="modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">View Details</h4>
      </div>
      <div class="modal-body">
        <p>Data error...</p>
      </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-default" data-dismiss="modal"></button> -->
      </div>
    </div>

  </div>
</div>
<input type="hidden" id="hd" value="101" name="">
<div class="hidden">
    <div  id="dialog-confirm" title="Submitting Less Than <?php echo MINIUM_RECORD ?> records?">
      <p>
      	<span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>
      	You are asking for new task without entering the minimum records. 
         If you still want to continue, it will be marked and reported!
      </p>
	</div>
</div>

<script type="text/javascript">
  $(document).ready(function() {
    $('.btn-close').click(function() {
      $('.set-posi').addClass('hidden');
    });


    $("#capture").draggable({
      drag: function() {
        var getCss = $(this).position();
        $('.prev-zoom').attr('style','background-position: 0px -'+ getCss.top *3 +'px');
        // console.log(getCss.top)
        $('.set-posi').removeClass('hidden');
      }
    });

		$('#adition').on('click',function() {
			if ($(this).is(':checked')) {
				// alert('hdbsh')
				$('.add-aditional').addClass('hidden');
				$('.left').removeClass('border-right');
				$('.right').addClass('border-left');
			} else {
				$('.add-aditional').removeClass('hidden');
				$('.left').addClass('border-right');
				$('.right').removeClass('border-left');
			}
		});

		$('#status').on('click',function() {
			if ($(this).is(':checked')) {
				// alert('checked');
				var rq = $('.rq');
				$(rq).attr('required',false);
			} else {
				var rq = $('.rq');
				$(rq).attr('required',true);
			}
		});
		
		//set values to hidden element for birth date			
		$("#add_more").on('submit',function(e){
			
			e.preventDefault();
			var birth_date = $("input[name='birth_date']").val();
			var birth_month = $("select[name='birth_month']").val();
			var old_birth_year = $("input[name='old_birth_year']").val();
			
			var birth_year = birth_date+'-'+birth_month+'-'+old_birth_year;
			
			var baptism_date = $("input[name='baptism_date']").val();
			var baptism_month = $("select[name='baptism_month']").val();
			var old_baptism_year = $("input[name='old_baptism_year']").val();
			
			var baptism_year = baptism_date+'-'+baptism_month+'-'+old_baptism_year;
			
			
			$("input[name='birth_year']").attr('value',birth_year);		
			
			$("input[name='baptism_year']").attr('value',baptism_year);	
			
			//alert($("input[name='birth_year']").val());
			//alert($("input[name='baptism_year']").val());
			
			$("form[id='add_more']").unbind('submit').submit();
		});
		
		

  });
</script>
<script type="text/javascript" src="js/custom.js"></script>
</body>
</html>