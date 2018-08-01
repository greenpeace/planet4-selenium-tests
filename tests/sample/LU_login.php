<?php

require_once __DIR__ . '/../AbstractClass.php';

class P3_LU_Test extends AbstractClass {

	/**
	 * @var \RemoteWebDriver
	 */
	protected $driver;

	public function setUp() {
		parent::setUp();
	}

	//Start from login page
	//protected $url = 'https://secured.greenpeace.org/international/en/My/login/?returnUrl=/international/en/';

	//Start from FAQ page
	protected $url = 'http://www.greenpeace.org/international/en/about/jobs/';

	/*public function testHomepage()
	{
		$this->webDriver->get($this->url);
		// checking that page title contains word 'Detox'
		$this->assertContains('Detox', $this->webDriver->getTitle());
	} */


	public function testLogin() {
		$this->driver->get( $this->url );
		// find search field by its id
		$signin = $this->driver->findElement( WebDriverBy::xpath( '/html/body/form/div[3]/div[2]/div[2]/div/ul/li[1]/a' ) );
		$signin->click();

		// find login form
		$form = $this->driver->findElement(
		// some CSS selectors can be very long:
			WebDriverBy::id( 'ctl00_cphContentArea_ucLogin_pnlLogin' )
		);

		$this->assertContains(
			'Login',
			$this->driver->getTitle()
		);

		//Enter username
		$usernamefield = $this->driver->findElement( WebDriverBy::id( 'ctl00_cphContentArea_ucLogin_txtUserName' ) );
		$usernamefield->click();
		$this->driver->getKeyboard()->sendKeys( 'tester.greenwire@gmail.com' );

		//Enter password
		$passfield = $this->driver->findElement( WebDriverBy::id( 'ctl00_cphContentArea_ucLogin_txtPassword' ) );
		$passfield->click();
		$this->driver->getKeyboard()->sendKeys( 'test_P3' );

		//Click on log in
		$login = $this->driver->findElement( WebDriverBy::id( 'ctl00_cphContentArea_ucLogin_btnLogin' ) );
		$login->click();

		//$driver->wait(10, 1000)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('ctl00_hlLoggedIn')));
		//$this->assertElementContainsText($this->findById('ctl00_hlLoggedIn'),'Welcome back');
		$this->assertContains( 'Work for Greenpeace International', $this->driver->getTitle() );
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
