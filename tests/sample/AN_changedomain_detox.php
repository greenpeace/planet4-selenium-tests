<?php

require_once __DIR__ . '/../AbstractClass.php';

class P3_AN_Test_Detox extends AbstractClass {

	/**
	 * @var \RemoteWebDriver
	 */
	protected $driver;

	public function setUp() {
		parent::setUp();
	}

	protected $url = 'http://detox-outdoor.org/';

	public function testchangeDomain() {
		$this->driver->get( $this->url );
		// find search field by its id
		$search = $this->driver->findElement( WebDriverBy::id( 'lang-select' ) );
		$search->click();

		// typing into field
		//$this->webDriver->getKeyboard()->sendKeys('save the arctic');

		// pressing "Enter"
		//$this->webDriver->getKeyboard()->pressKey(WebDriverKeys::ENTER);

		$domains = $this->driver->findElement(
		// some CSS selectors can be very long:
			WebDriverBy::xpath( '/html/body/div[1]/nav/section/ul[2]/li[3]/ul/li/ul/li[7]/a' )
		);

		$domains->click();

		$this->assertEquals(
			'http://detox-outdoor.org/it-IT',
			$this->driver->getCurrentURL()
		);

	}

	protected function assertElementNotFound( $by ) {
		$this->driver->takeScreenshot( 'reports/screenshots/' . __CLASS__ . '.png' );
		$els = $this->driver->findElements( $by );
		if ( count( $els ) ) {
			$this->fail( "Unexpectedly element was found" );
		}
		// increment assertion counter
		$this->assertTrue( true );
	}


	public function tearDown() {
		parent::tearDown();
	}

}

?>
