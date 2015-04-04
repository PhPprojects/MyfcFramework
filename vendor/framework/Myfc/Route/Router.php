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
    
    private $unsetParams;
    
    private $params;
    
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
         
              if($this->actionParsing($select['action'])){
                  
                  $this->callbackParsing($select['callback']);
                  
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
       

             $url = explode("/",rtrim($this->url,"/"));
            
             $action = rtrim($action,"/");
             
             $explode = explode("/",$action);
             
             
             if(count($url) && count($explode))
             {
                 $array = array_map(function($a){
                      
                     if(strstr($a, "{") && strstr($a, "}")){
                          
                         return $a;
                          
                     }
                      
                 }, $explode);
                      
                     $unsetArray = array_map(function($a){
                 
                         if(!strstr($a,"!"))
                         {
                              
                             return $a;
                              
                         }
                 
                     }
                         , $array);
                          
                         preg_match_all("#\{(.*?)\}#", $action,$finds);
                 
                         $find = $finds[1];
                          
                         $params = array();
                          
                          
                 
                         for($i=0;$i<count($array);$i++)
                         {
                 
                         if(isset($url[$i]) && $array[$i] !== null)
                         {
                 
                          
                         $params[] = $url[$i];
                 
                         }
                 
                         }
                 
                 
                             $unsetParams = array();
                 
                             for($a=0;$a<count($url);$a++)
                             {
                 
                              
                             if(isset($url[$a]) && $unsetArray[$a] !== null )
                             {
                              
                             $unsetParams[] = $url[$a];
                                  
                             }
                              
                             }
                 
                 
                             $this->unsetParams = $unsetParams;
                 
                             $this->params = $params;
                 
                 
                             return (is_array($this->unsetParams) && is_array($this->params)) ? true:false;
                 
             }
             
           
             
            
       
        
    }
    
 
    
    private function callbackParsing($callback)
    {
        
        // e�er �a�r�labilir bir ifadeyse
        
        if(is_callable($callback))
        {
            
            $this->runCallable($callback);
            
        }
        
        // �a�r�labilir biti�
        
        // e�er dizi ise
        if(is_array($callback))
        {
            
            if(is_string($callback[0]))
            {
                
                $this->callBackString($callback);
                
            }
            
            if(is_array($callback[0]))
            {
                
                $this->callBackArray($callback);
                
            }
            
            
            
            
            
        }
        
        // dizi biti�
        
        // e�er string ise
        
        if(is_string($callback))
        {
            
            $this->runController($callback);
            
        }
        
        // string biti�
        
        
    }
    
    /**
     * Callback olay�n�n 0. indisi string ise �a�r�l�r
     * @param unknown $callback
     */
    private function callBackString($callback)
    {
        
        if($callback[0] == "AJAX" || $callback[0] == "HTTPS" || $callback[0] == "https://" )
        {
            if($this->securityChecker($callback[0]))
            {
            
                if(is_callable($callback[1]))
                {
            
                    $this->runCallable($callback[1]);
            
                }else{
            
            
                    if(is_string($callback[1]))
                    {
            
                        $this->runController($callback[1]);
            
                    }
            
                }
            
            
            }
            
        }elseif(strstr($callback[0],"@"))
        {
            
            if($this->runCallable($callback[1]))
            {
                
                $this->runController($callback[0]);
                
            }
            
        }    
        
    }
    
    /**
     * callback olay�n�n 0. indisi array ise �a�r�l�r
     * @param array $callback
     */
    private function callBackArray(array $callback)
    {
       
        if(is_string($callback[0]))
        {
            
           
            
        }
        
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
           
            
        }
       elseif($https =="AJAX"){
           
           if(filter_input(INPUT_SERVER, 'HTTP_X_REQUESTED_WITH') === 'xmlhttprequest')
           {
               
               $isSecure = true;
               
               
           }else{
               
               $isSecure = false;
               
           }
           
           
       }else{
           
           $isSecure = true;
           
       }
        
       return  $isSecure ? true : false;
        
    }
    
    /**
     * �a�r�labilir ifade y�r�tmesi
     * @param unknown $callback
     */
    private function runCallable($callback)
    {
        
        $parametres = $this->params;
        
        
        $response = Singleton::make('\Myfc\Http\Response');
        
        $parametres[] = $response;
        
        return call_user_func_array($callback, $parametres);
        
    }
    
    /**
     * Kontroller Y�r�t�r
     * @param unknown $string
     */
    private function runController($string)
    {
        
        $parametres = $this->unsetParams;
        
        if(strstr($string, "@"))
        {
            
            list($controller,$method) = explode("@",$string);
            
        }else{
            
            $controller = $string;
            
            $method = null;
            
        }
        
        
        if($method !== null)
        {
            
            $controller = $this->container->makeController($controller);
            
            if(method_exists($controller, $method) && is_callable(array($controller,$method)))
            {
                
                return call_user_func_array(array($controller,$method), $paremetres);
                
            }
            
        }else{
            
               return  $controller = $this->container->makeController($controller,$paremetres);
             
        }
        
       
        
    }
    
    
}


?>