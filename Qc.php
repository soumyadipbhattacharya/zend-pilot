<?php
namespace Object;

/**
 * Created By Solution Team (Rana Saha)
 * This is a Qc Object
 */
Class Qc
{
    
    
    /*
    tid tinyint(3) UN PK 
    start_time timestamp 
    end_time timestamp 
    empid varchar(10) 
    status enum('positive','negetive') 
    feedback varchar(255) 
    comment text 
    task_tid tinyint(3) UN PK 
    task_uid bigint(20) UN PK 
    entry_type varchar(45)
    */
    
    public $tid; // The qc task id
    public $start_time; // The timestamp when the employee open the task
	public $end_time; //The timestamp when the employee submit the qc task
	public $empid; // The Employee id who upload the file
	public $status; // the status of the work may be 'positive' or 'negetive'
    public $feedback; // feedback of the qc operator
    public $comment; // Comment of the qc operator
    public $task_uid; // The unique ID of the record
    public $task_tid; // The unique ID of the task here it is Ticket ID
	public $entry_type; // Type of this entry. is it first qc, second QC or so on
    

    
    
    public function __construct()
    {
        return $this;
    }
	
}


