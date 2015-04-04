<?php
  namespace Myfc\Predis;
  
  use Myfc\Config;
  
  use PredisClient;
  
  use Exception;
  
  use PredisAutoloader;
  
  /**
   * 
   * @author vahit�erif
   *
   */

  class  Installer

  {
      /**
       * 
       * @var boolean 
       */
      protected static $installed = false;

        /**
         *  S�n�f� ba�lat�r
         */
        public function boot(  )
        {
            try{
                
                PredisAutoloader::register();
                static::$installed = true;;
            }
            
            catch(Exception $e)
            {
                static::$installed = false;
                echo $e;
            }

        }

        /**
         * Yeni bir predis client i olu�turup d�nd�r�r
         * @param string $configs
         * @return \PredisClient
         */
        
       public static  function create( $configs = null)
       {
           if(static::$installed == false) static::boot();
           if($configs == null)
           {
              $configs = Config::get('strogeConfigs','predis'); 
           }

           try{

               $redis = new PredisClient( $configs );

           }catch(Exception $e)
           {
               
               $redis = $e->getMessage();
               
           }
           
           
           return $redis;


       }



  }