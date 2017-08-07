<?php


abstract class AbstractClass extends PHPUnit\Framework\TestCase {
    
    /** @var array */
    private static $_config = array();
    /** @var WebDriver[]*/
    private static $_driverInstances = array();
    /** @var string */
    private static $_handle = '';
    /** @var WebDriver */
    protected $_driver = null;
    /** @var string */
    protected $_url = '';
   /** @var \RemoteWebDriver */
    protected $webDriver;


    public function setUp()
    {
	static $initialized = false;
	if ( !$initialized ) {
		$drivers = &self::$_driverInstances;
		try{
			$sessionID = file_get_contents('tmp/empty');
                	$driver =  $this->webDriver = RemoteWebDriver::createBySessionID($sessionID);
			register_shutdown_function( function() use ( &$drivers ) {
				foreach ( $drivers as $driv ) {
                			try {
						$driv->quit();
					} catch ( Exception $e ) {
					}                
				}
			});
		}
		catch(Exception $e){}
		$initialized = true;
	}
	
     $_config = include('./config/config.php');
        $this->_url = $_config['url'];
        if ( !self::$_handle ) {
                $capabilities = array(\WebDriverCapabilityType::BROWSER_NAME => $_config['browser']);
		$driver = $this->webDriver = RemoteWebDriver::create($_config['host'], $capabilities);
                self::$_driverInstances[] = $driver;
		file_put_contents('tmp/empty',$this->webDriver->getSessionID());
		
      	} else {
		$sessionID = file_get_contents('tmp/empty');
               	$driver =  $this->webDriver = RemoteWebDriver::createBySessionID($sessionID);
            	self::$_driverInstances[] = $driver;

           }

        $this->_driver = $driver;
        $this->_driver->get($this->_url);
        self::$_handle = $this->_driver->getWindowHandle();
    }
    
}

?>
