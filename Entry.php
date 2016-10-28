<?php
namespace Object;

/**
 * Created By Solution Team (Rana Saha)
 * This is a Entry Object
 * It should differ project by project
 */

class Entry
{
    public $type;
    public $date; 
    public $name;
    public $address;
    public $page;
    
    public $entryType;
    
    /**
     * Data Structure will be as follows
     * 
     * All data['name'] will be in name array like follows 
     * name['person']['fname']['lname']
     * name['father']['fname']['lname']
     * name['mother']['fname']['lname'] and so on ...
     * 
     * All data['address'] will be in below format
     * address['city']
     * address['county'] 
     * address['state']
     * address['country']
     * 
     * Same for date
     * date['date']
     * 
     * Same for data['type']
     * type['type'] may be baptisim or birth but we can accomodate any thing
     * 
     * @param array $data
     * @param string $entryType is it Entry or QC
     * @return \Object\Entry
     * 
     * Fields require 
     * DATE	- LAST NAME - FIRST NAME -	RECORD - TYPE - PAGE	
     * FATHER'S LAST NAME - FATHER'S FIRST NAME	
     * MOTHER'S LAST NAME - MOTHER'S FIRST NAME	
     * WITNESS 1 LAST NAME - WITNESS 1 FIRST NAME	
     * WITNESS 2 LAST NAME - WITNESS 2 FIRST NAME	
     * SPOUSE LAST NAME - SPOUSE FIRST NAME	
     * CITY - COUNTY - STATE - COUNTRY	
     * WITNESS 3 LAST NAME - WITNESS 3 FIRST NAME
     */
    
    public function __construct(array $data, $entryType = 'entry') 
    {
       $this->type      = $data['type'];
       $this->name      = $data['name'];
       $this->date      = $data['date'];
       $this->address   = $data['address'];
       $this->page      = $data['page'];
       
       $this->entryType = $entryType;
       
       return $this;
    }

    public static function getHeader()
    {
        $header = array(
            "Month",
            "Day",
            "Year",                 	
            "LAST NAME",            
            "FIRST NAME",           
            "RECORD TYPE",          
            "PAGE",                  
            "FATHER'S LAST NAME",   
            "FATHER'S FIRST NAME",  
            "MOTHER'S LAST NAME",  
            "MOTHER'S FIRST NAME",   
            "WITNESS 1 LAST NAME",   
            "WITNESS 1 FIRST NAME", 	
            "WITNESS 2 LAST NAME",   
            "WITNESS 2 FIRST NAME", 
            "SPOUSE LAST NAME",      	
            "SPOUSE FIRST NAME",   
            "CITY",                  
            "COUNTY",                
            "STATE",	                
            "COUNTRY",	           
            "WITNESS 3 LAST NAME",   
            "WITNESS 3 FIRST NAME",
            "STATUS", 
        );

        return $header;

    }

    public function lists()
    {
         

        $row = array();

        // Create the date as per client requirements;
        $dateArray = Tool::getDateArray($this->date);

        $row[] = $dateArray['month'];
        $row[] = $dateArray['day'];
        $row[] = $dateArray['year'];

        $row[] = $this->name['person']['last_name'];
        $row[] = $this->name['person']['first_name'];
        $row[] = $this->type;
        $row[] = $this->page;
        $row[] = $this->name['father']['last_name'];
        $row[] = $this->name['father']['first_name'];
        $row[] = $this->name['mother']['last_name'];
        $row[] = $this->name['mother']['first_name'];
        $row[] = $this->name['wittness1']['last_name'];
        $row[] = $this->name['wittness1']['first_name'];
        $row[] = $this->name['wittness2']['last_name'];
        $row[] = $this->name['wittness2']['first_name'];
        
        $row[] = $this->name['spouse']['last_name'];
        $row[] = $this->name['spouse']['first_name'];
        $row[] = $this->address['city'];
        $row[] = $this->address['county'];
        $row[] = $this->address['state'];
        $row[] = $this->address['country'];

        $row[] = $this->name['wittness3']['last_name'];
        $row[] = $this->name['wittness3']['first_name'];

        return $row;
    }
    
}