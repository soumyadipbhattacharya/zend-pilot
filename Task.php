<?php
namespace Object;

/**
 * Created By Solution Team (Rana Saha)
 * This is a Task Object
 */

Class Task
{
    
    
    public $tid; // The task id
    public $uid; // The unique ID of the task here it is Ticket ID
	public $empid; // The Employee id who upload the file
	public $start_time; // The timestamp when the employee open the file
	public $end_time; //The timestamp when the employee submit the file
	public $entry_type; // Type of this entry. is it first entry, second entry or QC
	public $detail;	// Here we insert full dataset of a record as an array
	public $status; // the status of the work
	public $encription; // if 1 then data will be stored after encription

    
    
    public function __construct()
    {
        return $this;
    }
    
    /**
     * This function is used for creating the 
     */
    public function getEntry()
    {
        if ($this->encription == 1) {
            $this->detail = Encription::decrypt($this->detail);
        }
        
        $entry = unserialize($this->detail);
        return $entry; 
    }
    
    public function setEntry($entry)
    {
        $this->detail = serialize($entry);
        
        if ($this->encription == 1) {
            $entryDetails = $this->detail;
            $this->detail = Encription::encrypt($entryDetails);
        }
        
        
    }
	
}


