<?php

/*
	All configuration data goes here
		1.app configurations
		2.hash configurations
		2.database configurations
		3.auth configurations
		4.mail configurations
		5.twig(template engine) configurations
		6.csrf token configurations
*/

return [
	'app' => [
		'url' => 'https://ciaompe.com/sites/deals',
		'sitename' => 'Best deals on earth',
		'discription'=> 'The best deals on earth',
		'css' => '/public/css',
		'js' => '/public/js',
		'images' => '/public/images',
		'avatar' => '/public/avatar/',
		'tmp' => '/public/tmp/',
		'deals' => '/public/deals/',
		'categories' => '/public/categories/',
		'advertise' => '/public/advertise/',
		'timezone' => 'Asia/Colombo',
		'contact' => [
			'address' => 'NO 100/56 World Trade Center, Colombo, Srilanka',
			'phone' => '+941126345678',
			'fax' => '+941126345678',
			'email' => 'test@gmail.com'
		]
	],
	'hash' => [
		'algo' => PASSWORD_BCRYPT,
		'cost' => 10
	],
	'db' => [
		'driver' => 'mysql',
		'host' => 'localhost',
		'database' => 'deals',
        	'username' => 'root',
        	'password' => '',
		'charset' => 'utf8',
		'collation' => 'utf8_unicode_ci',
		'prefix' => ''
	],
	'auth' => [
		'session' => 'user_id',
		'admin' => 'admin_id',
		'remember' => 'remember_token'
	],
	'mail' => [
		'smtp_auth' => true,
		'smtp_secure' => 'tls',
		'host' => 'smtp.gmail.com',
		'username' => 'hnd49batch@gmail.com',
		'password' => 'lolmpe123',
		'port' => 587,
		'html' => true,
		'from' => 'hello@lucids.co',
		'name' => 'Lucids'
	],
	'twig' => [
		'debug' => true
	],
	
	'hybrid_auth' => [
	    "base_url"   => "https://ciaompe.com/sites/deals/public/hybrid.php",
	    "providers"  => [
	        "Google"   => [
	            "enabled" => true,
	            "keys"    => [ "id" => "", "secret" => "" ],
	        ],
	        "Facebook" => [
	            "enabled" => true,
	            "keys"    => [ "id" => "", "secret" => "" ],
	            "scope"   => "email, user_about_me, user_birthday, user_hometown"
	        ],
	        "Twitter"  => [
	            "enabled" => true,
	            "keys"    => [ "key" => "", "secret" => "" ]
	        ],
	    ],
	    "debug_mode" => true,
	    "debug_file" => "bug.txt",
	],
	
	'recaptcha' => [
        'secret' => '',
        'siteKey' => ''
    ],

    'images' => [
    	'source' => [
    		'width' => 120,
    		'height' => 120
    	],
    	'deal' => [
    		'thumbnail' => [
    			'width' => 150,
    			'height' => 100,
    		],
    		'medium' => [
    			'width' => 440,
    			'height' => 320
    		],
    		'image' => [
    			'width' => 900,
    			'height' => 520
    		]
    	],
    	'category' => [
    		'width' => 120,
    		'height' => 120
    	]
    ],

    'perpage' => [
    	'deals' => 20,
    	'comments' => 10,
    	'watchlist' => 5,
    ]
];
