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
		$_config = include('./config/config.php');
	        $this->_url = $_config['url'];
		$options = new ChromeOptions();
		$options->addArguments(array('--window-size=1366,996',));
		$capabilities = DesiredCapabilities::chrome();
	    $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);
	    $driver = $this->webDriver = RemoteWebDriver::create($_config['host'], $capabilities);

		self::$_driverInstances[] = $driver;
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
