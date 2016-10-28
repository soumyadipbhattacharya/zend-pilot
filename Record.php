<?php
namespace Object;
use Object\Model\RecordModel AS RecordModel;
/**
 * Created By Solution Team (Rana Saha)
 * This is a Report Object
 */

Class Record extends RecordModel
{
  
    public $id; // record Id
    public $folder_name; // folder name
    public $file_name; // file name
    public $file_hash; // file hash
    public $upload_datetime; // Uploaded time
    public $status; // record status
    public $detail;

    
    
    public function __construct($id = null, $type = null)
    {
        parent::__construct();

        if ($type == "empID") {
            $recordID = $this->allocateJob($id);
        } else {
            $recordID = $id;
        }

        // Setting the Object
        $this->setValue($recordID);
        
    }

    public function getTasks()
    {
        return $this->getTask($this->id);
    }

    public function getQcs()
    {
        return $this->getQc($this->id);
    }

    /**
    * Setting the value in object
    */
    private function setValue($recordID)
    {
        $pdoStmt = $this->getRecord($recordID);

        $pdoStmt->setFetchMode(\PDO::FETCH_INTO, $this);

        $pdoStmt->fetch();

    }
    
    public function setTaskOrder($orderArray) {
        $this->taskOrder($this->id, $orderArray);
    }


	
}


