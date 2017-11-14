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
	//static $initialized = false;
	//$drivers = &self::$_driverInstances;
	/**
	try{
		$sessionID = file_get_contents('tmp/empty'); // Added line - test
		$driver =  $this->webDriver = RemoteWebDriver::createBySessionID($sessionID); //Added line - test
		//register_shutdown_function( function() use ( &$drivers ) {
			/**
			foreach ( $drivers as $driv ) {
				try {
					$driv->close();
				} catch ( Exception $e ) {
					echo 'Entro al catch 2 del Setup';
				}                
			}
			
		//});
	}
	catch(Exception $e){echo 'Entro al catch 1 del Setup';} 
	**/
	//$initialized = true;
	
	$_config = include('./config/config.php');
        $this->_url = $_config['url'];
	$capabilities = array(\WebDriverCapabilityType::BROWSER_NAME => $_config['browser']);
	$driver = $this->webDriver = RemoteWebDriver::create($_config['host'], $capabilities);
	
	//  Set width and height of browser
	//$this->webDriver->manage()->window()->setSize(new WebDriverDimension(1366, 996)); //Method not supported in newest webdriver version
	//$this->webDriver->manage()->window()->getSize();
	//$this->webDriver->manage()->window()->maximize();
	self::$_driverInstances[] = $driver;
	file_put_contents('tmp/empty',$this->webDriver->getSessionID());
		
        $this->_driver = $driver;
        $this->_driver->get($this->_url);
        self::$_handle = $this->_driver->getWindowHandle();
    }

   public function tearDown(){
	$classname = get_called_class();
	$failed = parent::hasFailed();
	if ($failed){
		$this->webDriver->takeScreenshot('reports/screenshots/'.$classname.'.png');
	}
	try{
		$this->webDriver->quit();
    } catch ( Exception $e ) {}
    
   }
    
}

?>
