<?php
namespace Myfc\Providers\Event;

  use Myfc\Bootstrap;
/**
 *
 * @author vahit�erif
 *        
 */
class Installer
{

    /**
     */
    public function __construct(Bootstrap $bootstrap)
    {
        
        $bootstrap->singleton('\Myfc\Event', $bootstrap);
        
        $eventPath = APP_PATH.'Events.php';
        
        if(file_exists($eventPath))
        {
            
            include $eventPath;
            
        }
        
    }
}

?>