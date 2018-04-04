<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../AbstractClass.php';

class P4_Cookies extends AbstractClass {

  /**
   * @var \RemoteWebDriver
   */

  public function testCookies()
  {
  	$link = "greenpeace.org/international/privacy";
  	//Validate banner is visible and contains link to more info
	try{
		$this->webDriver->findElement(WebDriverBy::id('set-cookie'));
		$val = $this->webDriver->findElement(WebDriverBy::id('set-cookie'))->getCSSValue('display');
		if($val!="block"){
			$this->fail('->Failed due to cookie banner not visible');
		}
		$link_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('#set-cookie .row p a'))->getAttribute('href');
	}catch(Exception $e){
		$this->fail('->Failed due to cookie banner not visible');		
	}
	$this->assertContains("$link","$link_pg");

	//Validate button to close banner
	try{
		$this->webDriver->findElement(WebDriverBy::id('hidecookie'))->click();
		usleep(1500000);
	}catch(Exception $e){
		$this->fail('->Failed when trying to click on button to close cookie banner');		
	}

	//Validate banner is closed
	$val = $this->webDriver->findElement(WebDriverBy::id('set-cookie'))->getCSSValue('display');
	if($val!="none"){
		$this->fail('->Failed due to cookie banner not hidden');
	}
	echo "\n-> Cookies banner test PASSED";
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
