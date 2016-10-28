<?php
namespace Object;

class Tool
{
    public static function dump($data, $details = 0) 
    {
        echo '<pre><code>';
        if ($details)
            var_dump($data);
        else
            print_r($data);
        echo '</code></pre>';
    }

    /**
    * Setting & Flasing the message once its got
    */
    public static function flashMessage($message = null)
    {
        $session =& self::session();
        if ($message === null) {
            if (!empty($session['flashMessage'])) {
                $msg = $session['flashMessage'];
                unset($session['flashMessage']);
                return $msg;
            } else {
                return false;
            }
        } else {
            // if not exist make it blank
            if(empty($session['flashMessage'])) {
                $session['flashMessage'] = '';   
            }
            $session['flashMessage'] .= $message.' ';
        } 
    }
    
    /**
     * @return bool
     */
    public static function is_session_started()
    {
        if ( php_sapi_name() !== 'cli' ) {
            if ( version_compare(phpversion(), '5.4.0', '>=') ) {
                return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
            } else {
                return session_id() === '' ? FALSE : TRUE;
            }
        }
        return FALSE;
    }
    
    /**
     * @return Session
     */
    public static function &session()
    {
        if ( self::is_session_started() === FALSE ) session_start();
        $session =& $_SESSION;
        return $session;
    }
	
	/* This function convert all array value to uppercase
	@param $post is an array
	*/
	public static function toUpper($data)
	{
		//print_r ($post);
		$convert_data=array_map('strtoupper', $data);
		
		return $convert_data;
	
	}

    /*
    * Get date array from string
    */
    public static function getDateArray($date, $format = "j-F-Y", $seperator = "-")
    {
        $monthArray = array(
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        );

        // Check if date is in date format or not
        $dateExploded = explode($seperator, $date);
        $date = array('day' => '', 'month' => '', 'year' => '');

        if ($format == "j-F-Y") {
            if (count($dateExploded) > 0) {
                foreach ($dateExploded as $item) {
                    if (is_numeric($item)) {
                        if ($item <= 31) {
                            $date['day'] = $item;
                        } elseif ( strlen($item) == 4) {
                            $date['year'] = $item;
                        }
                    } elseif (in_array($item, $monthArray)) {
                        $date['month'] = $item;
                    } 
                }
            }
            return $date;
        }
    }


    public static function exportXls($data, $name = null, $download = true)
    {
        set_time_limit( 0 );

        if ($name == null) {
            $name = 'Download_'.date("d_M_Y_H_i_s");
        }
        
        $filename =  __DIR__."/../uploads/".$name.".xls";
        $realPath = realpath( $filename );
        if ( false === $realPath )
        {
            touch( $filename );
            chmod( $filename, 0777 );
        }
        $filename = realpath( $filename );
        $handle = fopen( $filename, "w" );
        $finalData = $data;
        
        foreach ( $finalData AS $finalRow )
        {
            fputcsv( $handle, $finalRow, "\t" );
        }
        fclose( $handle );

        if ($download === true) {
            ob_clean();
            header( "Content-Type: application/vnd.ms-excel; charset=UTF-8" );
            header( "Content-Disposition: attachment; filename=".$name.".xls" );
            header( "Content-Transfer-Encoding: binary" );
            header( "Expires: 0" );
            header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
            header( "Pragma: public" );
            header( "Content-Length: " . filesize( $filename ) );
            readfile( $filename ); 
            unlink( $filename );
            exit();
        } else {
            return $filename;
        }
        
        
    }

    /*
    * Create Zip file from files name
    */

    public static function getZip($filesname)
    {
        $zip = new \ZipArchive;
        $fname = "zip_".date("d_M_Y_H_i_s").".zip";
        $zipName = __DIR__."/../uploads/".$fname;
        if ($zip->open($zipName, \ZipArchive::CREATE) === TRUE) {
            foreach($filesname as $filename) {
                $zip->addFile($filename, basename($filename));
            }
            $zip->close();

            // Download files
            ob_clean();
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename='.$fname);
            header( "Content-Transfer-Encoding: binary" );
            header( "Expires: 0" );
            header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
            header( "Pragma: public" );
            header( "Content-Length: " . filesize( $zipName ) );
            readfile( $zipName ); 
            unlink( $zipName );
            exit();
            
        } else {
            return false;
        }
    }
    
}

?>