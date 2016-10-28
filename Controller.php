<?php
namespace Object;

require_once('Autoloader.php');

/**
 * Created By Solution Team (Rana Saha)
 * This is a Main operation controller
 */

/**
* Get A task for Contributor
*/
Class Controller extends Record 
{
	private $empID = null;
	public $task = array();
    public $qc = array();
    public $activeQC = null;
    public $message = array();

	public function __construct($empID) {
		parent::__construct($empID, 'empID');
        $this->task = $this->getTasks();
        // if qc then we should store the old entry in old key
        if (OPERATION_TYPE == 'qc' ) {
            $this->qc = $this->getQcs();
        }
		$this->empID = $empID;
		return $this;
	}

    public function update($data)
    {
        $this->process($data);
        // Update or save the task
        $task = $this->saveTask($this->task[$data['tid']]);

        // Update the Qc also
        if($this->activeQC !== null) {
            // We have to deal with new QC
            if (empty($this->activeQC->tid)) {
                $this->activeQC = $this->newQc($task);
                $this->activeQC->start_time = $data['start_time'];
                $this->activeQC->entry_type = '1st Qc';
                $this->activeQC->feedback = "New Entry Inserted";
            }
            $this->updateQc($this->activeQC);
        }

        // redirect and load the task
        header('Location: '.$_SERVER['PHP_SELF']);
        exit;
        
    } 

    public function nextRecord($data)
    {
        foreach($this->task as $task) {
            // Found if enty have any not submitted nextRecord
            if(empty($task->status) || $task->status == 'none') {
                $this->message['alert'] = 'Please complete the open task first!';
            } else {
                // All the work for this entry done = request for new entry
                if (isset($data['new_record']) &&  $data['new_record'] == 'true') {
                    
                    // if entry then status will be promoted by +1 qc then next record status will be 4
                    $this->status = $this->status + 1;
                    
                    // Update record
                    $this->updateRecord($this);
                }
                if (isset($_POST['end_task']) && $_POST['end_task'] == 'true') {
                    header('Location: endoftask.php');
                    exit;
                } else {
                    // redirect and load the task
                    header('Location: '.$_SERVER['PHP_SELF']);
                    exit;
                }
            }
        } 
    }
    
    public function process($data)
    {
        
        /*
         * Data format getting from form
         * 
        [first_name] => Randall
        [last_name] => TERAN
        [birth_year] => 1980
        [baptism_year] => 1982
        [father_first_name] => Rebecca
        [father_last_name] => TERAN
        [mother_first_name] => Suzanne
        [mother_last_name] => TERAN
        [spouse_first_name] => Dewey
        [spouse_last_name] => TEACHOUT
        [baptism_wittness1_first_name] => Godde
        [baptism_wittness1_last_name] => SUPER
        [baptism_wittness2_first_name] => Kate
        [baptism_wittness2_last_name] => Ferty
        [baptism_wittness3_first_name] => Norma
        [baptism_wittness3_last_name] => SUPER
        [birth_city] => Kolkata
        [birth_county] => West Bengal
        [birth_state] => West Bengal
        [birth_country] => India
        [baptism_city] => Kolkata
        [baptism_county] => West Bengal
        [baptism_state] => West Bengal
        [baptism_country] => India
        [to_do] => add_more
        */

        // trim every element of an array
        $data = array_map('trim', $data);
        
        // Setting name
        $name = array();
        $name['person']['first_name'] = $data['first_name'];
        $name['person']['last_name'] = $data['last_name'];
        
        // Setting record for common use
        $nameArray = array('father','mother','spouse');
        
        // Setting the name
        foreach ($nameArray as $value) {
            
            $name[$value]['first_name'] = $data[$value.'_first_name'];
            $name[$value]['last_name'] = $data[$value.'_last_name'];
        }
        
        $commonRecord = array($name);
        
        // Create type wise record do not change the order
        $typrArray = array('baptism', 'birth');
        $entry = array();
        
        foreach ($typrArray as $value) {
            
            if (!empty($data[$value.'_year']) || $data[$value.'_year'] == '0') {
                $entryData = array_merge( 
                            $this->setRecord($data, $value),
                            array('page' => ltrim(pathinfo($this->file_name, PATHINFO_FILENAME),0)));
                            
                $entryData['name'] = array_merge($entryData['name'], $name);
                            
                $entryData['type'] = $value;
                $entry['operator'][] = new Entry($entryData);
            }
        }
        
        if (count($entry) == 0) {
            Tool::flashMessage('Please provide year in Birth Or Baptism fields!');
             // redirect and load the task as no year given
            header('Location: '.$_SERVER['PHP_SELF']);
            exit;
        }
        
        // If old record present then preserver it
        if(!empty($this->task[$data['tid']]->detail['old'])) {
            $entry['old'] = $this->task[$data['tid']]->detail['old'];
        }

        // if qc then we should store the old entry in old key
        if (OPERATION_TYPE == 'qc' ) {
            
            if (!empty($data['tid'])) {
                $qcStatus = 'positive';
                $taskQc = $this->task[$data['tid']];
                
                if(md5(serialize($taskQc->detail['operator'])) != md5(serialize($entry['operator']))) {
                    $entry['old'][date('Y-M-d H:i:s')] = $taskQc->detail['operator'];
                    $qcStatus = 'negetive';
                }
                 
                 $qc = $this->getSingleQc( $this->task[$data['tid']]->uid,  $this->task[$data['tid']]->tid);
    
                 $qc->start_time = $data['start_time'];
                 $qc->feedback = empty($data['feedback'])? $qc->feedback: $data['feedback'];
                 $qc->comment = empty($data['comment'])? $qc->comment: $data['comment'];
                 $qc->status = $qcStatus;
    
                 // It will updated later once task is saved;
                 $this->activeQC = $qc;
            } else {
                // We should update the qc once task is save
                $this->activeQC = new Qc();
            }
        }

        if (count($this->task) > 0) {

            // Check the status 
            $taskStatus = 'done';
            if(isset($data['status']) && in_array($data['status'], array('escalate', 'partial'))) {
                $taskStatus = $data['status'];
            }

            if($data['tid'] == 0) {
                // create a new Task
                $task = new Task();
                $task->empid = $this->empID;
                $task->uid = $this->id;
                $this->task[$data['tid']] = $task;
            } else {
                // Update the open task
                $task = $this->task[$data['tid']];
            }

            $task->start_time = $data['start_time'];
            $task->status = $taskStatus;
            
            /**
            * If operator is other than 1st entry then that have to specify
            * If no Encription that have to specify
            * ['entry_type'] = '1st entry'
            * ['encription'] = 0 // false
            */

            if(empty($data['entry_type'])) {
                $data['entry_type'] = '1st Entry';
            }

            if(empty($data['encription'])) {
                $data['encription'] = 0;
            }
            $task->encription = $data['encription'];
            $task->entry_type = $data['entry_type'];
            
            // The below command set the $task->details = $entry with encription if required;
            // Do not change the position of below code as it will not work if encription is not set
            $task->setEntry($entry);  
        }
    }
        
    public function setRecord($data, $type)
    {
        // Setting the address for type
        $addressArray = array('city','county','state','country');
        $defaultAddress = unserialize($this->detail);
        
        $address = array();

         
        foreach ($addressArray as $value) {
            
            if (isset($data['address']) &&  $data['address'] == 'default') {
                $address[$value] = $defaultAddress[$value];
            } else {
                if (empty($type.'_'.$value) && isset($defaultAddress[$value])) {
                    $data[$type.'_'.$value] = $this->$value;
                }
                $address[$value] = $data[$type.'_'.$value];
            }
            
        }
        
        $year['date'] = null;
        
        if(isset($data[$type.'_year'])) {
            
            // Setting date 
            $year['date'] = $data[$type.'_year'];
        }

        // No of witness
        $wittnessNo = 3;
        
        for($i= 1; $i <= $wittnessNo; $i++) {
            $nameArray[] = 'wittness'.$i;
        }
        
        // Setting the name
        foreach ($nameArray as $value) {
            
            $name[$value]['first_name'] = $data[$type.'_'.$value.'_first_name'];
            $name[$value]['last_name']  = $data[$type.'_'.$value.'_last_name'];
        }

        return array('address' => $address, 'date' => $year['date'], 'name' => $name);
    }
    

}



