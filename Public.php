<?php

  /**
   * 
   *  Sabitlerin tan�mlanmas� -> app klas�r�
   *  @var unknown
   */

  define("APP_PATH", "app/");
  
  /**
   *
   *  Sabitlerin tan�mlanmas� -> public klas�r�
   *  @var unknown
   */
  
  define("PUBLIC_PATH","public/");
  
  /**
   *
   *  Sabitlerin tan�mlanmas� -> system klas�r�
   *  @var unknown
   */
  
  define("SYSTEM_PATH","system/");
  
  /**
   *
   *  Sabitlerin tan�mlanmas� -> vendor klas�r�
   *  @var unknown
   */
  
  define("VENDOR_PATH","vendor/");
  
  /**
   * Sabitlerin tan�mlanmas� -> language(dil) klas�r�
   * @var unknown
   */
  
  define("LANGUAGE_PATH", PUBLIC_PATH.'language');
  
  include SYSTEM_PATH.'bootstrap.php';