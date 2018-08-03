<?php

use PHPUnit\DbUnit\TestCase as PHPUnit_Extensions_Database_TestCase;
use PHPUnit\DbUnit\Operation\Factory as PHPUnit_Extensions_Database_Operation_Factory;
use PHPUnit\DbUnit\DataSet\CompositeDataSet as PHPUnit_Extensions_Database_DataSet_CompositeDataSet;

include 'P4_Functions.php';

abstract class AbstractClass extends PHPUnit_Extensions_Database_TestCase {

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

	protected $dataset_update;
	protected $dataset_insert;

	/**
	 * only instantiate pdo once for test clean-up/fixture load
	 * @var PDO
	 */
	static private $pdo = null;

	/**
	 * only instantiate PHPUnit_Extensions_Database_DB_IDatabaseConnection once per test
	 * @var type
	 */
	private $conn = null;

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
		$driver = $this->driver = RemoteWebDriver::create( self::$_config['host'], $capabilities );

		self::$_driverInstances[] = $driver;
		$this->_driver            = $driver;
		$this->_driver->get( $this->_url );
		self::$_handle = $this->_driver->getWindowHandle();


		if (!file_exists('data/dump-generated.xml')) {
			copy('data/dump.xml', 'data/dump-generated.xml');
			$data = $this->uploadMedia();
			$this->prepareDumpData( $data );
		}
		$this->_driver->get( $this->_url );

		PHPUnit_Extensions_Database_Operation_Factory::INSERT()
		                                             ->execute( $this->getConnection(), $this->getDataSetInsert() );
		PHPUnit_Extensions_Database_Operation_Factory::UPDATE()
		                                             ->execute( $this->getConnection(), $this->getDataSetUpdate() );

		$this->getConnection()->createDataSet();
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

		PHPUnit_Extensions_Database_Operation_Factory::DELETE()
		                                             ->execute( $this->getConnection(), $this->dataset_insert );

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

	protected function getConnection() {
		if ( $this->conn === null ) {
			if ( self::$pdo == null ) {
				self::$pdo = new PDO( 'mysql:dbname=' . $GLOBALS['DB_DBNAME'] . ';host=' . $GLOBALS['DB_HOST'] . '	', $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'] );
			}
			$this->conn = $this->createDefaultDBConnection( self::$pdo, 'p4testing' );
		}

		return $this->conn;
	}

	protected function getDataSet() {
		$ds1                  = $this->createXMLDataSet( __DIR__ . '/../data/dump_options.xml' );
		$this->dataset_update = $ds1;
		$ds2                  = $this->createXMLDataSet( __DIR__ . '/../data/dump-generated.xml' );
		$this->dataset_insert = $ds2;
		$composite_data_set   = new PHPUnit_Extensions_Database_DataSet_CompositeDataSet();
		$composite_data_set->addDataSet( $ds1 );
		$composite_data_set->addDataSet( $ds2 );

		return $composite_data_set;
	}

	protected function getDataSetInsert() {
		$ds2                  = $this->createXMLDataSet( __DIR__ . '/../data/dump-generated.xml' );
		$this->dataset_insert = $ds2;

		return $ds2;
	}

	protected function getDataSetUpdate() {
		$ds2                  = $this->createXMLDataSet( __DIR__ . '/../data/dump_options.xml' );
		$this->dataset_update = $ds2;

		return $ds2;
	}

	protected function uploadMedia() {
		$driver = $this->driver;
		$this->wpLogin();

		$pages = $this->driver->findElement( WebDriverBy::id( 'menu-media' ) );
		$pages->click();
		try {
			$link = $this->driver->findElement( WebDriverBy::linkText( 'Add New' )
			);
		} catch ( Exception $e ) {
			$this->fail( '->Could not find \'Add New\' button in Pages overview' );
		}
		$link->click();


		$input = $this->driver->findElement( WebDriverBy::xpath( "//input[starts-with(@id,'html5_')]" ) );

		// Set the file detector.
		$input->setFileDetector( new LocalFileDetector() );

		// Scan data images directory and upload each one to media library.
		$images = array_diff( scandir( 'data/images' ), array( '..', '.' ) );
		foreach ( $images as $image ) {
			$input->sendKeys( 'data/images/' . $image );
		}

		// Wait for images upload.
		$count_images = count( $images );
		$driver->wait( 90, 2000 )->until(
			function () use ( $driver, $count_images ) {
				return count( $driver->findElements(
						WebDriverBy::xpath( "//div[@id='media-items']//a[@class='edit-attachment']" ) ) ) == $count_images;
			}
		);

		$anchors = $this->driver->findElements( WebDriverBy::xpath( "//div[@id='media-items']//a[@class='edit-attachment']" ) );


		$ids = [];
		foreach ( $anchors as $anchor ) {
			$link   = $anchor->getAttribute( 'href' );
			$params = parse_url( $link, PHP_URL_QUERY );
			parse_str( $params, $output );
			$ids[] = $output['post'];
		}

		$this->wpLogout();
		return $ids;
	}

	protected function prepareDumpData($ids) {
		$this->find_replace( 'oceans_tag_attachment_id', $ids[0], 'data/dump-generated.xml' );
		$this->find_replace( 'arctic_sunrise_tag_attachment_id', $ids[1], 'data/dump-generated.xml' );
	}

}
