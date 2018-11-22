<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../AbstractClass.php';
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;


class P4_login extends AbstractClass {

  /**
   * @var \RemoteWebDriver
   */
  protected $driver;

  public function wpLogin() {
    $u = $this->_url . "/wp-admin";
    $this->driver->get($u);
    $p4_user  = self::$_config['p4_user'];
    $p4_pass  = self::$_config['p4_password'];
    // find login form
    $this->driver->findElement(WebDriverBy::id('loginform'));
    $this->driver->wait(3);

    // Enter username by setting the value via javascript. Works for running tests on localhost,
	// while sendKeys() does not work on localhost because the characters are being sent faster than the browser can recieve.
	$this->driver->executeScript("document.getElementById('user_login').setAttribute('value', '$p4_user')");

	// Enter password by setting the value via javascript. Works for running tests on localhost,
	// while sendKeys() does not work on localhost because the characters are being sent faster than the browser can recieve.
	$this->driver->executeScript("document.getElementById('user_pass').setAttribute('value', '$p4_pass')");

    try{
      //Fill in prove you are human field
      $q = $this->driver->findElement(WebDriverBy::cssSelector('#loginform div'))->getText();
      $q = substr($q, strpos($q, ":") + 1);
      $q = substr($q, 0, -3);
      $a = eval('return '.$q.';');
      $ans = $this->driver->findElement(WebDriverBy::name('jetpack_protect_num'));
      $ans->click();
      $this->driver->getKeyboard()->sendKeys("$a");
    }catch(Exception $e){}

    //Click on log in
    $login = $this->driver->findElement(WebDriverBy::id('wp-submit'));
    $login->click();

    // Wait until user is logged in and sees the Dashboard title
    $this->driver->wait(30, 1000)->until(WebDriverExpectedCondition::titleContains('Dashboard'));
  }

  public function wpLogout() {
    $usermenu = $this->driver->findElement(WebDriverBy::id('wp-admin-bar-my-account'));
    $this->driver->getMouse()->mouseMove( $usermenu->getCoordinates() );

    //Waits for hidden menu to be visible
    $this->driver->wait(10, 1000)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('wp-admin-bar-logout')));

    //Locates log out option and clicks on it
    $logout = $this->driver->findElement(WebDriverBy::linkText('Log Out'));
    $logout->click();

    // Validates user is logged out by locating login form
    $this->driver->findElement(WebDriverBy::id('loginform'));
  }
}
