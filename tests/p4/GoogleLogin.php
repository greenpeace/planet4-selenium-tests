<?php


//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../AbstractClass.php';

class P4_googlelogin extends AbstractClass {

  /** @var array */
    private static $_config = array();
  /**
   * @var \RemoteWebDriver
   */

  protected $driver;

  public function setUp()
  {
	parent::setUp();
  }


  public function testGoogleLogin()
  {

    $_config = include('./config/config.php');
    $email = $_config['email'];
    $pass = $_config['email_password'];
    $u = $this->_url . "/wp-admin";
    $this->driver->get($u);
    //find login form
    $this->driver->findElement(WebDriverBy::id('loginform'));
    $this->driver->wait(3);
    $form = $this->driver->findElement(WebDriverBy::className('galogin'));
    $form->click();
    $this->driver->wait(10, 1000)->until(
      WebDriverExpectedCondition::titleIs('Sign in - Google Accounts'));

    $heading = $this->driver->findElement(WebDriverBy::id('headingText'))->getText();

    if ($heading=="Sign in"){
      //Enter email
      $usernamefield = $this->driver->findElement(WebDriverBy::id('identifierId'));
      $usernamefield->click();
      $this->driver->getKeyboard()->sendKeys("$email");
      usleep(2000000);
      //Click on Next
      $this->driver->findElement(WebDriverBy::id('identifierNext'))->click();
      //Enter password
      usleep(2000000);
      $passfield = $this->driver->findElement(WebDriverBy::id('password'));
      $passfield->click();
      $passfield = $this->driver->findElement(WebDriverBy::name('password'));
      $passfield->click();
      $this->driver->getKeyboard()->sendKeys("$pass");
      //Click on Next
      $this->driver->findElement(WebDriverBy::id('passwordNext'))->click();
    }elseif($heading=="Choose an account"){
      $acc = $this->driver->findElement(
        WebDriverBy::cssSelector("p[data-email='".$email."']"));
      $acc->click();
    }

    usleep(3000000);

    $this->assertContains('Dashboard', $this->driver->getTitle());
    echo "\n-> Google Login test PASSED";
  }


  protected function assertElementNotFound($by)
  {
	$this->driver->takeScreenshot( 'reports/screenshots/' . __CLASS__ . '.png');
	$els = $this->driver->findElements($by);
	if (count($els)) {
		$this->fail("Unexpectedly element was found");
	}
	// increment assertion counter
	$this->assertTrue(true);

  }

}
?>
