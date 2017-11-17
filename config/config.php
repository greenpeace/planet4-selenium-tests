<?php

return array(
    'host' => 'http://localhost:4444/wd/hub',
    'browser' => 'chrome',
    // Production
	//'url'=> 'http://planet4.greenpeace.org/wp-admin'      
    //Staging
    'url'=> 'http://dev.p4.greenpeace.org/wp-admin',
    //P3 sample tests
	//'url' => 'http://www.greenpeace.org/international/en/about/jobs/'
    
    //Test lines
    'chromeOptions' => "{args: ['window-size=1366, 996']}"
);
