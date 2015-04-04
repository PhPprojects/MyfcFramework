<?php 

  return [


      'Cache' => [ // cache ayarlar�
	      'driver'        => 'memcache', // -> apc,memcache[�nerilen],file,predis
		  'path'          => APP_PATH.'Stroge/Cache',
		  'defaultDriver' => 'predis' // se�ilen driver y�kl� de�ilse 
	  ],
	  
	  'Session' => [ // session ayarlar� 
	  
	    'driver' => 'php', // -> cacheBased,fileBased,php[�nerilen]
		'path'   =>  APP_PATH.'Stroge/Session',
		'defaultDriver' => 'php' // se�ilen driver y�kl� de�ilse 
		
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