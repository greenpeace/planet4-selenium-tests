<?php


//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../AbstractClass.php';

class P4_googlelogin extends AbstractClass {

  /**
   * @var \RemoteWebDriver
   */

  protected $webDriver;

  public function setUp()
  {
	parent::setUp();
  }


  public function testGoogleLogin()
  {

    //find login form
    $this->webDriver->findElement(WebDriverBy::id('loginform'));
    $this->webDriver->wait(3);
    $form = $this->webDriver->findElement(WebDriverBy::className('galogin'));
    $form->click();
    $this->webDriver->wait(10, 1000)->until(
      WebDriverExpectedCondition::titleIs('Sign in - Google Accounts'));

    $heading = $this->webDriver->findElement(WebDriverBy::id('headingText'))->getText();

    if ($heading=="Sign in"){
      //Enter email
      $usernamefield = $this->webDriver->findElement(WebDriverBy::id('identifierId'));
      $usernamefield->click();
      $this->webDriver->getKeyboard()->sendKeys('tester.greenwire@gmail.com');
      usleep(2000000);
      //Click on Next
      $this->webDriver->findElement(WebDriverBy::id('identifierNext'))->click();
      //Enter password
      usleep(2000000);
      $passfield = $this->webDriver->findElement(WebDriverBy::id('password'));
      $passfield->click();
      $passfield = $this->webDriver->findElement(WebDriverBy::name('password'));
      $passfield->click();
      $this->webDriver->getKeyboard()->sendKeys('u3vsREsvjwo');
      //Click on Next
      $this->webDriver->findElement(WebDriverBy::id('passwordNext'))->click();
    }else{
      $acc = $this->webDriver->findElement(
        WebDriverBy::cssSelector("p[data-email='lreyes@greenpeace.org']"));
      $acc->click();
    }

    usleep(3000000);

    $this->assertContains('Dashboard', $this->webDriver->getTitle());

  }


  protected function assertElementNotFound($by)
  {
	$this->webDriver->takeScreenshot('reports/screenshots/'.__CLASS__.'.png');
	$els = $this->webDriver->findElements($by);
	if (count($els)) {
		$this->fail("Unexpectedly element was found");
	}
	// increment assertion counter
	$this->assertTrue(true);

  }

}
?>
