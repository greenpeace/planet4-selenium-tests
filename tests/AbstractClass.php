<?php

include 'P4_Functions.php';

abstract class AbstractClass extends PHPUnit\Framework\TestCase {

	const CONFIG_FILE             = './config/config.php';
	const TIMEOUT_IN_SECOND       = 5;
	const INTERVAL_IN_MILLISECOND = 250;

	/** @var array */
	protected static $_config = array();
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

	/**
	 * AbstractClass constructor.
	 *
	 * @param null   $name
	 * @param array  $data
	 * @param string $dataName
	 */
	public function __construct( $name = null, array $data = [], $dataName = '' ) {
		self::$_config = include( self::CONFIG_FILE );
		parent::__construct( $name, $data, $dataName );
	}

	/**
	 * Sets up the fixture, for example, open a network connection.
	 * This method is called before a test is executed.
	 */
	public function setUp() {
		$this->_url = self::$_config['url'];

		/** @var DesiredCapabilities $capabilities */
		$capabilities = DesiredCapabilities::{self::$_config['browser']}();
		if ( 'chrome' === self::$_config['browser'] ) {
			$options = new ChromeOptions();
			$options->addArguments( array( '--window-size=1366,996', ) );
			$capabilities->setCapability( ChromeOptions::CAPABILITY, $options );
		}
		$driver = $this->webDriver = RemoteWebDriver::create( self::$_config['host'], $capabilities );

		self::$_driverInstances[] = $driver;
		$this->_driver = $driver;
		$this->_driver->get( $this->_url );
		self::$_handle = $this->_driver->getWindowHandle();
	}

	/**
	 * Tears down the fixture, for example, close a network connection.
	 * This method is called after a test is executed.
	 */
	public function tearDown() {
		if ( parent::hasFailed() ) {
			$this->webDriver->takeScreenshot( 'reports/screenshots/' . get_called_class() . '.png' );
		}
		try {
			$this->webDriver->quit();
		} catch ( Exception $e ) {}
	}

	/**
	 * Gets the url that the test will run.
	 *
	 * @return mixed
	 */
	public function getBaseUrl() {
    	return self::$_config['url'];
	}

	/**
	 * Asserts that the given element is not inside the DOM.
	 *
	 * @param string $selector Selector for locating the element.
	 * @param string $message Custom message to display upon failure.
	 */
	protected function assertElementNotFound( $selector, $message = '' ) {
		$elements = $this->webDriver->findElements( WebDriverBy::cssSelector( $selector ) );
		if ( count( $elements ) > 0 ) {
			$this->webDriver->takeScreenshot( 'reports/screenshots/' . get_called_class() . '.png' );
			if ( ! $message ) {
				$message = 'Unexpectedly element with selector "' . $selector . '" exists';
			}
			$this->fail( $message );
		} else {
			$this->assertTrue( true );                  // Increment assertion counter.
		}
	}

	/**
	 * Waits until the given $element is loaded in DOM and it is visible.
	 *
	 * @param string $selector Selector for locating the element.
	 * @param int    $timeout_in_second Time to wait until the element is loaded and visible.
	 * @param int    $interval_in_millisecond Time to wait between each check if element is loaded and visible.
	 * @param string $message Custom message to display upon failure.
	 */
	protected function waitUntilVisible( $selector, $timeout_in_second = self::TIMEOUT_IN_SECOND, $interval_in_millisecond = self::INTERVAL_IN_MILLISECOND, $message = '->General Exception' ) {
		try {
			$this->_driver->wait( $timeout_in_second, $interval_in_millisecond )
			              ->until( WebDriverExpectedCondition::visibilityOfElementLocated( WebDriverBy::cssSelector( $selector ) ) );
		} catch ( NoSuchElementException $e ) {
			$this->fail( '->Element with selector "' . $selector . '" does not exist' );
		} catch ( TimeOutException $e ) {
			$this->fail( '->Could not find element with selector ' . $selector . ' before timeout expires' );
		} catch ( Exception $e ) {
			$this->fail( $message );
		}
	}
}
