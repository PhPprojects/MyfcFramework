<?php


 namespace Myfc;
 
 use Myfc\Mail\PHPMAILER\PHPMailer;

/**
 * Class Mail
 *
 *  *****************************
 *
 * @packpage MyfcFramework
 *
 *
 *  ***********************
 *
 *  MyfcFramework Mail g�nderme S�n�f�
 *
 * @packpage PHPmailer
 *
 * @see https://github.com/PHPMailer/PHPMailer
 *
 *
 */
 class Mail
 {
     /**
      * @var  $toadress
      * @var  $toname
      * @var  $username
      * @var  $password
      * @var  $configs
      * @var  $port
      * @var  $subject
      * @var  $msg
      * @var  $attachment
      * @var  $fromname
      * @var  $fromadress
      * @var  $replyadress
      * @var  $replyname
      * @var  $host
      * @var  $returnadress
      * @var  $body
      * @var $cc
      *
      */

     protected $toadress;
     protected $toname;
     protected $username;
     protected $password;
     protected $configs;
     protected $port;
     protected $subject;
     protected $msg;
     protected $attachment;
     protected $fromname;
     protected $fromadress;
     protected $replyadress;
     protected $replyname;
     protected $host;
     protected $returnadress;
     protected $body;
     protected $cc;

     /**
      *
      * Başlatıcı fonskiyon
      * 
      *   $options ile mailConfigs den ayr� olarak ayar g�nderebilirsiniz
      *
      * @param string $options
      * 
      * @return mixed $this
      *
      */

     public function __construct($options = '')
     {
           $mail = new PHPMailer();
           if($options !== '')
           {
               $this->configs = $options;
           }else{
              $configs = Config::get('mailConfigs');
           }
           $configs = $configs['mailer'];
           $this->password = $configs['password'];
           $this->username = $configs['username'];
           $this->host = $configs['host'];
           $this->secure = $configs['secure'];
           $this->mailServer = $mail;
           return $this;
     }

     /** 
      *  Mailin gidece�i ve mail le ilgili bilgiler parametre olarak girilebilir
      * 
      * @param $adress
      * @param $name
      * @param string $subject
      * @param string $msg
      * @param string $body
      * @return $this
      *
      */

     public function to($adress = '',$name ='',$subject = '',$msg = '',$body = '')
     {
         $this->toadress = $adress;
         $this->toname = $name;
         $this->subject = $subject;
         $this->msg = $msg;
         $this->body = $body;
         return $this;
     }

     /**
      * 
      *  mailin i�eri�ini girebilirsiniz, $content de bir .html dosyas�n�n yolu girilebilir yada direk i�erikde girilebilir
      * 
      * @param $content
      * @param string $body
      * @return $this
      */

     public function setContent($content,$body = '')
     {
         if( substr($content,-5) == '.html')
         {
             $this->msg = file_get_contents($content);
         }else{
             $this->msg = $content;
         }
         if(!isset($this->body)) $this->body  = $body;

         return $this;
     }

     /**
      *  
      *  Maildeki body k�sm�n� d�zenler
      * 
      * @param $body
      * @return mixed $this
      */

     public function body($body)
     {
         if(!isset($this->body)) $this->body  = $body;
         return $this;
     }

     /**
      *  Maile dosya ekler, $filepath dosya yoludur, $newname girilecek yeni isimdir( bo� b�rak�labilir )
      * 
      * @param $filePath
      * @param string $newname
      * @return $this
      */

     public function addAttachment($filePath,$newname = '')
     {
         if($newname == '')
         {
             $newname  = $filePath;
         }
         $this->attachment[$filePath] = $newname;
         return $this;
     }

     /**
      * 
      *  Mailin kimden gitti�ini belirten $adress ve $name de�i�kenleridir, girilmek zorundad�r
      * 
      * @param $adress
      * @param $name
      * @return $this
      */

     public function from($adress,$name)
     {
         $this->fromname = $name;
         $this->fromadress = $adress;
         return $this;
     }

     /**
      *  Mailin konusunu d�zenler
      * 
      * @param string $subject
      * @return $this
      */

     public function setSubject($subject = '')
     {
         if(!isset($this->subject)) $this->subject = $subject;
         return $this;
     }

     /**
      * @param $cc
      * @return $this
      */

     public function addCC($cc)
     {
         $this->cc = $cc;
         return $this;
     }

     /**
      * Maile cevab�n ba�ka bir maile yollanmas�n� istiyorsan�z  $adress ve $name de�i�kenleri doldurulmal�d�r
      * @param string $reply
      * @param string $name
      * @return mixed $this
      */

     public function reply($adress,$name = '')
     {
          $this->replyadress = $adress;
          $this->replyname = $name;
          return $this;
     }

     /**
      * 
      *  Mail g�nderme fonksiyondur,  Facade alt�nda static kullan�mda parametre olarak �a�r�labilir bir fonksyon girilmelidir
      *  
      *  S�n�f taraf�ndan fonksiyona $this ��esi atan�r
      * 
      * @param callable $callable
      * @return bool
      * @throws Exception
      * @throws phpmailerException
      */

     public function send($callable = null)
     {

         if(is_callable($callable))
         {
             
             call_user_func_array($callable, array($this));
             
         }else{
             
             $this->mailServer->isSMTP();
             $this->mailServer->SMTPDebug = 2;
             $this->mailServer->Debugoutput = 'html';
             $this->mailServer->Host = $this->host;
             $this->mailServer->Username = $this->username;
             $this->mailServer->SMTPSecure = $this->secure;
             $this->mailServer->port = $this->port;
             $this->mailServer->SMTPAuth = true;
             $this->mailServer->Password = $this->password;
             $this->mailServer->Subject = $this->subject;
             $this->mailServer->msgHTML($this->msg);
             $this->mailServer->AltBody = $this->body;
             
             if(isset($this->replyadress) && isset($this->replyname)) $this->mailServer->addReplyTo($this->replyadress,$this->replyname);
             if(isset($this->fromadress) && isset($this->fromname)) $this->mailServer->setFrom($this->fromadress,$this->fromname);
             if(isset($this->cc)) $this->mailServer->addCC($this->cc);
             if(isset($this->toadress)) $this->mailServer->AddAddress($this->toadress, $this->toname);
             if(isset($this->attachment)){
                 foreach($this->attachment as $name => $newname)
                 {
                     $this->mailServer->addAttachment($name,$newname);
                 }
             
             }
             if($this->mailServer->send())
             {
                 return true;
             }else{
                 echo $this->mailServer->ErrorInfo;
                 return false;
             }
             
         }
          


     }
     
     


 }
