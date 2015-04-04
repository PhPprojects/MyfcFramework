<?php
namespace Myfc;

 use Myfc\DB;
 
 use Myfc\Singleton;
 
 use Myfc\Facade\Session;
 
 use Myfc\Cookie;
 
 use Myfc\Facade\Carbon;
/**
 *
 * @author vahit�erif
 *        
 */
class Auth extends DB
{

    private $table = 'user';
    
    private $key = 'login';
    /**
     */
    public function __construct($table = '')
    {
        
        
        if($table !== '')
        {
            
            
            $this->table($table);
            
        }
        
    }
    
    /**
     * Auth s�n�f�n�n sorgularda kullanaca�� veritaban� tablosunu se�er
     *   
     *  
     * @param string $table
     * @return \Myfc\Auth
     */
    public function table($table = '') {
        
        $this->table = $table;
        
        $this->setTable($table);
        
        return $this;
    }
    
    /**
     * Girilen verilere g�re veritan�ndan sorgulama yap�l�r 
     *   
     *    E�er $param1 array girilmediyse $param2 ile birlikte username, password �eklinde array olu�tururlur
     *    
     *    E�er $rememberMe true ise cookie atamas� yapar
     *    
     * @param string|array $param1
     * @param string $param2
     * @param string $rememberMe
     * @return integer|boolean
     */
    
    public function attemp($param1 = array(), $param2 = null, $rememberMe = false)
    {

        if(!is_array($param1))
        {
           
            $param1 = array('username' => $param1, 'password' => $param2);
            
        }
        
        
        
        if( $eslesen = $this->where($param1)->returnQuery()->rowCount())
        {
            
            $keys = array_values($param1);
            
            Session::set($this->key,$keys[0]);
            
            $time = 60*60;
            
            if($rememberMe === true)
            {
                
                Cookie::set($this->key, $keys[0], $time);
                
            }
            return $eslesen;
            
        }else{
            
            return false;
            
        }
        
        
    }
    
    /**
     * Giri� yap�l�p yap�lmad���n� kontrol eder, Session da ve Cookie de $this->key i arar
     * @return boolean
     */
    
    public function check()
    {
        
        $key = $this->key;
        
        if(Session::get($key))
        {
            
            return true;
            
        }elseif(Cookie::get($key))
        {
            
            return true;
            
        }else{
            
            return false;
            
        }
        
        
    }
    
    
    /**
     * 
     *   Girilen $array � veritaban�nda se�ilen tabloya ekler
     *   
     *    ��lem ba�ar�l� olursa true, e�er ba�ar�s�z olursa false olur.
     * 
     * @param array $array
     * @return boolean
     */
    
    public function register(array $array){
        
        $veri = $this->set($array)
        ->create();
        
        return($veri) ? true:false;
        
    }
    
    
}

?>