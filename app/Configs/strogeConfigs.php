<?php 

  return [


      'Cache' => [ // cache ayarlar�
	      'driver'        => 'memcache',
		  'path'          => APP_PATH.'Stroge/Cache',
		  'defaultDriver' => 'predis'
	  ],
	  
	  'Session' => [ // session ayarlar� 
	  
	    'driver' => 'php',
		'path'   =>  APP_PATH.'Stroge/Session',
		'defaultDriver' => 'php'
		
	  ],

      'Cookie' => [ // cookie ayarlar�



      ],
      
      'predis' => [ // predis ayarlar� [ standart ayarlard�r, dokumanaman�z �nerilir ]
           
          'cluster' => false,
           
          'default' => [
               
              'scheme'   => 'tcp',
              'host'     => '127.0.0.1',
              'port'     =>  6379,
              'database' =>  0,
               
          ]
           
      ]
	  
  ]
 

?>