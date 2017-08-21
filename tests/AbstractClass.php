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
	$drivers = &self::$_driverInstances;
	try{
		register_shutdown_function( function() use ( &$drivers ) {
			foreach ( $drivers as $driv ) {
                		try {
					$driv->close();
				} catch ( Exception $e ) {
					}                
			}
		});
	}
	catch(Exception $e){}
	$initialized = true;
	
	$_config = include('./config/config.php');
        $this->_url = $_config['url'];
	$capabilities = array(\WebDriverCapabilityType::BROWSER_NAME => $_config['browser']);
	$driver = $this->webDriver = RemoteWebDriver::create($_config['host'], $capabilities);
	
	//  Set width and height of browser
	$this->webDriver->manage()->window()->setSize(new WebDriverDimension(1366, 996));
	self::$_driverInstances[] = $driver;
	file_put_contents('tmp/empty',$this->webDriver->getSessionID());
		
        $this->_driver = $driver;
        $this->_driver->get($this->_url);
        self::$_handle = $this->_driver->getWindowHandle();
    }

   public function tearDown()
    {
	 try{
               $this->_driver->close();
            } catch ( Exception $e ) {
            }
    }
    
}

?>
