<?php

require_once __DIR__ . '/../AbstractClass.php';


class P3_AN_Test extends AbstractClass {

  /**
   * @var \RemoteWebDriver
   */
  protected $webDriver;
  protected $_quit;
  protected $_failing;

  public function setUp()
  {
	parent::setUp();
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
	$this->webDriver->takeScreenshot('reports/screenshots/'.__CLASS__.'.png');
	$els = $this->webDriver->findElements($by);
	if (count($els)) {
	$this->fail("Unexpectedly element was found");
    }
    // increment assertion counter
    $this->assertTrue(true);
  }

  public function tearDown()
  {
   	parent::tearDown();
  }

}
?>
