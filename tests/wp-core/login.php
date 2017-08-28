<?php


//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../AbstractClass.php';

class P4_login extends AbstractClass {

  /**
   * @var \RemoteWebDriver
   */

  protected $webDriver;

  public function setUp()
  {
	parent::setUp();
  }


  public function wpLogin()
  {

    // find login form
    $form = $this->webDriver->findElement(WebDriverBy::id('loginform'));
    $this->webDriver->wait(3);

    //Enter username
    $usernamefield = $this->webDriver->findElement(WebDriverBy::id('user_login'));
    $usernamefield->click();
    $this->webDriver->getKeyboard()->sendKeys('dev');

    //Enter password
    $passfield = $this->webDriver->findElement(WebDriverBy::id('user_pass'));
    $passfield->click();
    $this->webDriver->getKeyboard()->sendKeys('u3vsREsvjwo');
	
    //Click on log in
    $login = $this->webDriver->findElement(WebDriverBy::id('wp-submit'));
    $login->click();
    $this->webDriver->wait(3);

    // Validates user is logged in by locating dashboard
    $this->assertContains('Dashboard', $this->webDriver->getTitle());
  }

  public function wpLogout()
  {
    $usermenu = $this->webDriver->findElement(WebDriverBy::id('wp-admin-bar-my-account'));
    $this->webDriver->getMouse()->mouseMove( $usermenu->getCoordinates() );    

    //Waits for hidden menu to be visible
    $this->webDriver->wait(10, 1000)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('wp-admin-bar-logout')));
    
    //Locates log out option and clicks on it
    $logout = $this->webDriver->findElement(WebDriverBy::xpath("/html/body/div[1]/div[2]/div[1]/div/ul[2]/li/div/ul/li[3]/a"));
    $logout->click();

    // Validates user is logged out by locating login form
    $form = $this->webDriver->findElement(WebDriverBy::id('loginform'));
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
