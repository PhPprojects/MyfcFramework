<?php
namespace Myfc\Providers\Error;

/**
 *
 * @author vahit�erif
 *        
 */
class Reporting
{

    /**
     */
    public function __construct()
    {
        
        ini_set('error_reporting', REPORTING);
        
    }
}

?>