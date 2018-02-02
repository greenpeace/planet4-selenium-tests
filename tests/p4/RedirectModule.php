<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../AbstractClass.php';

class P4_Redirect extends AbstractClass {

  /**
   * @var \RemoteWebDriver
   */

  //This test is hardcoded for planet 4 dev environment

  public function testRedirectModule()
  {
  	//Url to be checked
  	$u = "http://dev.p4.greenpeace.org/international/Global/international/briefings/other/Statement-of-concern.pdf";
  	$this->webDriver->get($u);
  	usleep(2000000);
  	$new_u = $this->webDriver->getCurrentURL();
  	$this->assertContains("/archive-international/Global/international/briefings/other/Statement-of-concern.pdf",$new_u);
	echo "\n-> Redirects Module test PASSED";
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
