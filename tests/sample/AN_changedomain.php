<?php

class P3_AN_Test extends PHPUnit_Framework_TestCase {

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

  /**
   * @group gpi
   */

  public function testchangeDomain()
  {
    $configs = include('./config/config.php');
    $this->webDriver->get($configs['url']);
    // find search field by its id
    $search = $this->webDriver->findElement(WebDriverBy::xpath('/html/body/form/div[3]/div[2]/div[1]/div[1]/fieldset/div/a'));
    $search->click();

    $domains = $this->webDriver->findElement(
      // some CSS selectors can be very long:
      WebDriverBy::xpath('/html/body/div[4]/ul/li[11]/a')
    );

    $domains->click();

    $this->assertEquals('http://www.greenpeace.org/belgium/nl/', $this->webDriver->getCurrentURL());
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
    $this->webDriver->close();
  }

}
?>
