<?php

   namespace Myfc;

   use Myfc\Http\Request;

   use Myfc\Redirect\Redirecter;

   use Myfc\Exceptions\ClassExceptions\undefinedClassException;
  
  class Redirect extends Redirecter
  
  {
      
      

      
      public function __construct()
      {

          $url = Config::get('Configs', 'url');
          
          $request = Request::this();
          
          parent::__construct($request, $url);
          
      }
      
      public static function boot()
      {
          
          return new static();
          
      }
      
      /**
       * bekleme yapmadan y�nlendirme yapar
       * @param string $href
       */
      
      public function location($href= '')
      {
          
          $this->redirect('location', func_get_args());
          
      }
      
      /**
       * Zamana g�re y�nlendirme yapar
       * @param string $href
       * @param number $time
       */
      public function refresh($href = '', $time = 2)
      {
          
          $this->redirect('refresh', func_get_args());
          
      }
      
      
      public function __call($method, $parametres)
      {
          
          throw new undefinedClassException(sprint_f("%s ad�nda bir method bulunamad� ",$method));
          
      }


  }