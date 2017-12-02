<?php

return array(
    'host' => 'http://localhost:4444/wd/hub',
    'browser' => 'chrome',
    /**
    // Production
	'url'=> 'http://planet4.greenpeace.org/wp-admin'      
    **/
    //Staging
    'url'=> 'http://dev.p4.greenpeace.org/wp-admin',
    /**
    //P3 sample tests
	'url' => 'http://www.greenpeace.org/international/en/about/jobs/'
    **/
    'chromeOptions' => "{args: ['window-size=1366, 996']}",
    'p4_user' => 'test_user',
    'p4_password' => 'u3vsREsvjwo',
    'email' => 'tester.greenwire@gmail.com',
    'email_password' => 'u3vsREsvjwo'
);
