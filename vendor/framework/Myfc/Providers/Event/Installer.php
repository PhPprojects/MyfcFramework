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
        
    }
}

?>