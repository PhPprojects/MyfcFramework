<?php
namespace Myfc\Providers\Language;

 use Myfc\Bootstrap;
/**
 *
 * @author vahit�erif
 *        
 */
class Installer
{

    /**
     * 
     *  Language s�n�f�n�n kurulabilmesi i�in s�n�f� ba�lat�r
     * 
     * @param Bootstrap $bootstrap
     */
    public function __construct(Bootstrap $bootstrap)
    {
        
        $bootstrap->singleton('\Myfc\Language');
        
    }
}

?>