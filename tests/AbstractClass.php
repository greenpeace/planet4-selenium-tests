<?php

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\Exception\NoSuchElementException;
use Facebook\WebDriver\Exception\TimeOutException;

include 'P4_Functions.php';

abstract class AbstractClass extends PHPUnit\Framework\TestCase {

	use P4_Functions;

	const CONFIG_FILE             = './config/config.php';
	const TIMEOUT_IN_SECOND       = 20;
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
	protected $driver;

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
			$options->addArguments( array( '--window-size=1366,1200', ) );
			$capabilities->setCapability( ChromeOptions::CAPABILITY, $options );
		}
		$driver = $this->driver = RemoteWebDriver::create( self::$_config['host'], $capabilities );

		self::$_driverInstances[] = $driver;
		$this->_driver            = $driver;
		$this->_driver->get( $this->_url );
		self::$_handle = $this->_driver->getWindowHandle();

		if ( isset($GLOBALS['CREATE_TEST_DATA']) && $GLOBALS['CREATE_TEST_DATA'] ) {

			$this->wpLogin();
			$data = $this->uploadMedia();
			$this->createCategories();
			$this->createTags();
			$this->createCustomTaxonomyTerms();
			$this->setPermalinks();
			$this->setPlanet4Options();
			$this->createPosts();
			$this->wpLogout();
		}
		$this->_driver->get( $this->_url );
	}

	/**
	 * Cleanup after test execution.
	 */
	public function tearDown() {
		if ( parent::hasFailed() ) {
			$this->driver->takeScreenshot( 'reports/screenshots/' . get_called_class() . '.png' );
		}
		try {
			$this->driver->quit();
		} catch ( Exception $e ) {
		}
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
	 */
	protected function assertElementNotFound( $selector ) {
		$elements = $this->driver->findElements( WebDriverBy::cssSelector( $selector ) );
		if ( count( $elements ) > 0 ) {
			$this->driver->takeScreenshot( 'reports/screenshots/' . get_called_class() . '.png' );
			$this->fail( 'Unexpectedly element with selector "' . $selector . '" exists' );
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
