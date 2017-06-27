<?php

class P3_AN_Test_Detox extends PHPUnit_Framework_TestCase {

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

  protected $url = 'http://detox-outdoor.org/';

  public function testchangeDomain()
  {
    $this->webDriver->get($this->url);
    // find search field by its id
    $search = $this->webDriver->findElement(WebDriverBy::id('lang-select'));
    $search->click();

    // typing into field
    //$this->webDriver->getKeyboard()->sendKeys('save the arctic');

    // pressing "Enter"
    //$this->webDriver->getKeyboard()->pressKey(WebDriverKeys::ENTER);

    $domains = $this->webDriver->findElement(
      // some CSS selectors can be very long:
      WebDriverBy::xpath('/html/body/div[1]/nav/section/ul[2]/li[3]/ul/li/ul/li[7]/a')
    );

    $domains->click();

    $this->assertEquals(
      'http://detox-outdoor.org/it-IT',
      $this->webDriver->getCurrentURL()
    );

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
