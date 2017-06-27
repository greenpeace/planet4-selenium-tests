<?php

class P3_LU_Test extends PHPUnit_Framework_TestCase {

  /**
   * @var \RemoteWebDriver
   */

  protected $webDriver;

  public function setUp()
  {
    $configs = include('./config/config.php');
    $capabilities = array(\WebDriverCapabilityType::BROWSER_NAME => $configs['browser']);
    $this->webDriver = RemoteWebDriver::create($configs['host'], $capabilities);
  }

  //Start from login page
  //protected $url = 'https://secured.greenpeace.org/international/en/My/login/?returnUrl=/international/en/';

  //Start from FAQ page
  protected $url = 'http://www.greenpeace.org/international/en/about/jobs/';

    /*public function testHomepage()
    {
        $this->webDriver->get($this->url);
        // checking that page title contains word 'GitHub'
        $this->assertContains('Detox', $this->webDriver->getTitle());
    } */


  public function testLogin()
  {
    $this->webDriver->get($this->url);
    // find search field by its id
    $signin = $this->webDriver->findElement(WebDriverBy::xpath('/html/body/form/div[3]/div[2]/div[2]/div/ul/li[1]/a'));
    $signin->click();

    // find login form
    $form = $this->webDriver->findElement(
      // some CSS selectors can be very long:
      WebDriverBy::id('ctl00_cphContentArea_ucLogin_pnlLogin')
    );

    $this->assertContains(
      'Login',
      $this->webDriver->getTitle()
    );

    //Enter username
    $usernamefield = $this->webDriver->findElement(WebDriverBy::id('ctl00_cphContentArea_ucLogin_txtUserName'));
    $usernamefield->click();
    $this->webDriver->getKeyboard()->sendKeys('tester.greenwire@gmail.com');

    //Enter password
    $passfield = $this->webDriver->findElement(WebDriverBy::id('ctl00_cphContentArea_ucLogin_txtPassword'));
    $passfield->click();
    $this->webDriver->getKeyboard()->sendKeys('test_P3');

    //Click on log in
    $login = $this->webDriver->findElement(WebDriverBy::id('ctl00_cphContentArea_ucLogin_btnLogin'));
    $login->click();

    //$driver->wait(10, 1000)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('ctl00_hlLoggedIn')));
    //$this->assertElementContainsText($this->findById('ctl00_hlLoggedIn'),'Welcome back');
    $this->assertContains('Work for Greenpeace International', $this->webDriver->getTitle());
  }

  protected function assertElementNotFound($by)
  {
    $els = $this->webDriver->findElements($by);
    if (count($els)) {
      $this->fail("Unexpectedly element was found");
    }
    // increment assertion counter
    $this->assertTrue(true);

  }

  public function tearDown()
  {
    $this->webDriver->quit();
  }

}
?>
