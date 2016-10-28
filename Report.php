<?php
namespace Object;
use Object\Record AS Record;
use Object\Model\RecordModel AS RecordModel;


Class Report extends RecordModel
{
    public $format = 'excel';
    public $qc = true;

    public function __construct()
	{
       // Nothing to do else this
       parent::__construct();
	}

    public function getRecords($start = null, $end = null, $output = 'screen')
    {
        
        $recordsAll = $this->recordBetween($start, $end);

        // Qc records found
        $records = array();
        
        // If no records found return null
        if(count($recordsAll) == 0) {
            return null;
        }

        // If with qc then status will be 4 if without qc then status will be 2
        $recordStatus = '4';
        if ($this->qc == false) {
             $recordStatus = '2';
        }

        foreach($recordsAll as $record) {
            if($record->status ==  $recordStatus) {
                $records[] = $record;
            }
        }

        // if output is file
        if ($output == 'file') {
            $globalSet = null;
            $header = Entry::getHeader();
            
            foreach ($records as $record) {
                // If with qc then status will be 4 if without qc then status will be 2
               
                $folderNameHash = md5($record->folder_name);
                if(empty($globalSet[$folderNameHash])) {
                    $globalSet[$folderNameHash] = array('list' => array());
                }
            
            
                foreach ($record->task as $task) {
                    if (!empty($task->detail)) {
                        $list = array();
                        $entryArray = $task->detail['operator'];
                        if(!empty($task->detail['qc'])) {
                            //$entryArray = $task->detail['qc']; 
                        }
                        $entryList = null;
                        foreach($entryArray as $entry) {
                            $entryList = $entry->lists();
                            $entryList[] = $task->status;
                            
                            if ($entry->type == 'baptism') {
                                $list[] = $entryList;
                            } else {
                                $otherData[] = $entryList;
                            }
                        }

                        // this ensure that baptism come later than birth
                        if(!empty($otherData)) {
                            foreach($otherData as $other) {
                                $list[] = $other;
                            }
                            unset($otherData);
                        }
                        
                        $globalSet[$folderNameHash]['list'] = array_merge($globalSet[$folderNameHash]['list'], $list);
                        $globalSet[$folderNameHash]['folder_name'] = $record->folder_name;
                        
                    }
                }

            }
            
            // For Single file
            if (count($globalSet) == 1 && !empty($globalSet[$folderNameHash])) {
                $outputData = array_merge(array($header), $globalSet[$folderNameHash]['list']);
                Tool::exportXls($outputData, $globalSet[$folderNameHash]['folder_name']);
            } else {
                
                foreach ($globalSet as $recordList) {
                    # code...
                    $outputData = array_merge(array($header), $recordList['list']);
                    $filesName[] = Tool::exportXls($outputData, $recordList['folder_name'], false);
                }
                
                Tool::getZip($filesName);

            }
        }
        return $records;  
    }

	
}