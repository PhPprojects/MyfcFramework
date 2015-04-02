<?php
namespace Myfc;

 use Myfc\View\Loader;
 use Myfc\Singleton;
/**
 *
 * @author vahit�erif
 *        
 */
class MainController
{

    private $collection;
    
    /**
     * Ba�latma Fonksiyon
     */
    
    public function __construct()
    {
        
      $this->collection['view'] = new Loader();
      
      $this->collection['assets'] = Singleton::make('Myfc\Assets');
        
    }
    
    /**
     * Modal �a��rma i�lemi
     * @param unknown $modal
     */
    protected function modal($modal)
    {
        
        $path = APP_PATH."Modals/$modal.php";
        
        if(file_exists($path))
        {
            
            include $path;
            
            if(class_exists($modal))
            {
                
                $this->collection['modal'] = new $modal();
                
            }else{
                
                $this->collection['modal'] = null;
                
            }
            
        }
        
    }
    
    /**
     * Dinamik olarak de�er �a�r�lmas�
     * @param unknown $name
     */
    public function __get($name)
    {
        
        if(isset($this->collection[$name]))
        {
            
            return $this->collection[$name];
            
        }
        
    }
    
    public function __call($name,$parametres)
    {
        
        array_map(function($a) use ($name,$parametres,$thi){
             if(method_exists(array($thi->collection[$a],$name)) || is_callable(array($thi->collection[$a],$name))){
                 
                 return call_user_func_array(array($thi->collection[$a],$name),$parametres);
                 
             }
                
        }
            , $this->collection);
        
        
    }
}

?>