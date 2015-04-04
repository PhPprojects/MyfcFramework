<?php

    namespace Myfc;

    class Cookie{
        
        /**
         * T�m cookileri temizler
         */
		
        public static function flush()
        {
			

            foreach($_COOKIE as  $key => $value)
			
            {
				
                static::delete($key);
				
            }
			
        }

        /**
         * cookie atamas� yapar, $name de�eri zorunludur ve string dir, $time integer girilmelidir
         * @param string $name
         * @param mixed $value
         * @param integer $time
         */
        public static function set($name = '',$value,$time= 3600)
        {
			
            setcookie($name,$value,time()+$time);
			
        }
        
        /**
         *  
         *  Girilen $name de�i�kenine g�re cookie olup olmad���n� kontrol eder varsa cookie i d�nd�r�r yoksa false d�ner
         *  
         * @param string $name
         * @return mixed|boolean
         */

        public static function get($name = '')
        {
			
            if(isset($_Cookie[$name])) return $_COOKIE[$name];else return false;
			
        }


        /**
         * 
         *  girilen $name de�i�keni varsa silinir yoksa exception olu�tururlur 
         * 
         * @param string $name
         * @throws Exception
         */
        public static function delete($name = '')
        {
			
            if(isset($_Cookie[$name])) setcookie($name,'',time()-29556466);else throw new Exception(" $name diye bir cookie bulunamadı ");
			
        }
    }