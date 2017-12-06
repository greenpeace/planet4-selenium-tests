<?php


//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../AbstractClass.php';

class P4_login extends AbstractClass {

  
  /** @var array */
    private static $_config = array();
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
    $u = $this->_url . "/wp-admin";
    $this->webDriver->get($u);
    $_config = include('./config/config.php');
    $p4_user = $_config['p4_user'];
    $p4_pass = $_config['p4_password'];
    // find login form
    $form = $this->webDriver->findElement(WebDriverBy::id('loginform'));
    $this->webDriver->wait(3);

    //Enter username
    $usernamefield = $this->webDriver->findElement(WebDriverBy::id('user_login'));
    $usernamefield->click();
    $this->webDriver->getKeyboard()->sendKeys("$p4_user");

    //Enter password
    $passfield = $this->webDriver->findElement(WebDriverBy::id('user_pass'));
    $passfield->click();
    $this->webDriver->getKeyboard()->sendKeys("$p4_pass");

    try{
      //Fill in prove you are human field
      $q = $this->webDriver->findElement(WebDriverBy::cssSelector('#loginform div'))->getText();
      $q = substr($q, strpos($q, ":") + 1);
      $q = substr($q, 0, -3);
      $a = eval('return '.$q.';');
      $ans = $this->webDriver->findElement(WebDriverBy::name('jetpack_protect_num'));
      $ans->click();
      $this->webDriver->getKeyboard()->sendKeys("$a");
    }catch(Exception $e){}

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
//    $usermenu = $this->webDriver->findElement(WebDriverBy::id('wp-admin-bar-top-secondary'));
    $this->webDriver->getMouse()->mouseMove( $usermenu->getCoordinates() );    

    //Waits for hidden menu to be visible
    $this->webDriver->wait(10, 1000)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('wp-admin-bar-logout')));
    
    //Locates log out option and clicks on it
//    $logout = $this->webDriver->findElement(WebDriverBy::xpath("/html/body/div[1]/div[2]/div[1]/div/ul[2]/li/div/ul/li[3]/a"));
    $logout = $this->webDriver->findElement(WebDriverBy::linkText('Log Out'));
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
