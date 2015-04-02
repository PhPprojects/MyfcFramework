<?php
namespace Myfc;
 
 use Myfc\Bootstrap;

/**
 *
 * @author vahit�erif
 *        
 */
class Router
{

    private $container;
    
    private $url;
    
    private $collection;
    
    private $method;
    
    public function run(Bootstrap $container, $collection)
    {
        
        $this->container = $container;
        
        $this->collection = $collection;
        
        $this->method = $this->container->server->method;
        
        $this->url = $this->container->get['url'];
        
        $this->startParsing();
        
    }
    
    /**
     * Par�alama i�lemi Ba�lar
     */
    
    private function startParsing()
    {
        
        $collection = $this->collection;
        
        $this->methodParsing($this->method);
        
        
    }
    
    /**
     * Yap�lan tipe g�re par�alanmaya ba�lan�r
     * @param unknown $method
     */
    
    private function methodParsing( $method )
    
    {
        
         $selected = $this->collection[$method];
         
         foreach($selected as $select)
         {
         
              if($parametres = $this->actionParsing($select['action'])){
                  
                  $this->callbackParsing($select['callback'], $parametres);
                  
              }
              
             
         }
        
    }
    
    /**
     * Link kontrolu ba�lang��
     * @param unknown $action
     * @return unknown
     */
    private function actionParsing($action)
    {
       
         if(strstr($action, "{") && strstr($action, "}"))
         {
             
             $url = $this->url;
             
             $url =  rtrim($url,"/");
             
             $url = explode("/",$url);
            
             
             $action = rtrim($action,"/");
             
             $explode = explode("/", $action);

    
             preg_match_all("#\{(.*?)\}#", $action,$finds);
             
             $find = $finds[1];
             
             $cikart = count($explode)-count($find);
             
             $cikart = count($explode)-$cikart;
             
             
             $exp = array_slice($url, $cikart, count($explode));
             
      
             
             $check = array_map(function($a)
                 {
                     
                     if($b = $this->collection['WHERE'][$a])
                     {
                          
                         if( preg_match($b, $a) )
                         {
                             
                             return $a;
                         }
                          
                     }else{
                         
                         return $a;
                         
                     }
                     
                 }  , $exp);
             
            
               return $check;
            
             
         }
        
    }
    
 
    
    private function callbackParsing($callback, array $parametres = array())
    {
        
        // e�er �a�r�labilir bir ifadeyse
        
        if(is_callable($callback))
        {
            
            $this->runCallable($callback,$parametres);
            
        }
        
        // �a�r�labilir biti�
        
        // e�er dizi ise
        if(is_array($callback))
        {
            
            if(is_string($callback[0]))
            {
                
                $this->callBackString($callback,$parametres);
                
            }
            
            if(is_array($callback[0]))
            {
                
                $this->callBackArray($callback,$parametres);
                
            }
            
            
            
            
            
        }
        
        // dizi biti�
        
        // e�er string ise
        
        if(is_string($callback))
        {
            
            $this->runController($callback,$parametres);
            
        }
        
        // string biti�
        
        
    }
    
    /**
     * Callback olay�n�n 0. indisi string ise �a�r�l�r
     * @param unknown $callback
     */
    private function callBackString($callback,$parametres)
    {
        
        if($this->securityChecker($callback[0]))
        {
        
            if(is_callable($callback[1]))
            {
        
                $this->runCallable($callback[1],$parametres);
        
            }else{
        
        
                if(is_string($callback[1]))
                {
        
                    $this->runController($callback[1],$parametres);
        
                }
        
            }
        
        
        }
        
    }
    
    /**
     * callback olay�n�n 0. indisi array ise �a�r�l�r
     * @param array $callback
     */
    private function callBackArray(array $callback)
    {
        
        //
        
    }
    /**
     * Https den girilip girilmedi�ini alg�lar
     * @param unknown $https
     */
    private function securityChecker($https){
        
        $isSecure = false;
        if($https == "HTTPS" || $https == "https://")
        {
            
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
                $isSecure = true;
            }
            elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' || !empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] == 'on') {
                $isSecure = true;
            }
            return  $isSecure ? true : false;
            
        }
        else{
            
            return  true;
            
        }
        
        
        
    }
    
    /**
     * �a�r�labilir ifade y�r�tmesi
     * @param unknown $callback
     */
    private function runCallable($callback,$parametres)
    {
        
        $response = Singleton::make('\Myfc\Http\Response');
        
        $parametres['response'] = $response;
        
        return call_user_func_array($callback, $parametres);
        
    }
    
    /**
     * Kontroller Y�r�t�r
     * @param unknown $string
     */
    private function runController($string,$paremetres)
    {
        
        
        if(strstr($string, "@"))
        {
            
            list($controller,$method) = explode("@",$string);
            
        }else{
            
            $controller = $string;
            
            $method = null;
            
        }
        
        
        if($method !== null)
        {
            
            $controller = $this->container->make($controller);
            
            if(method_exists($controller, $method) && is_callable(array($controller,$method)))
            {
                
                return call_user_func_array(array($controller,$method), $paremetres);
                
            }
            
        }else{
            
               return  $controller = $this->container->make($controller,$paremetres);
             
        }
        
       
        
    }
    
    
}


?>