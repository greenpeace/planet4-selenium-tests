<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../AbstractClass.php';

class P4_Actpage extends AbstractClass {

  /**
   * @var \RemoteWebDriver
   */

  public function testActpage()
  {
  	$this->webDriver->get($this->_url);
  	$this->webDriver->findElement(WebDriverBy::className('act-nav-link'))->click();
  	//$url_act = $this->webDriver->getCurrentURL();
  	$this->assertEquals($this->webDriver->getCurrentURL(),"https://dev.p4.greenpeace.org/international/act/");

	//Validate header block is present in page
	try{
		$this->webDriver->findElement(WebDriverBy::className('page-header'));
		$this->webDriver->findElement(WebDriverBy::className('page-header-title'));
		$this->webDriver->findElement(WebDriverBy::className('page-header-subtitle'));
		$this->webDriver->findElement(WebDriverBy::className('page-header-content'));
		$this->webDriver->findElement(WebDriverBy::className('covers-block'));
		$this->webDriver->findElement(WebDriverBy::className('happy-point-block-wrap'));
	}catch(Exception $e){
		$this->fail('->Failed to see header block in act page');
	}

	//Validate covers block is present in page
	try{
		$this->webDriver->findElement(WebDriverBy::className('covers-block'));
		$this->webDriver->findElement(WebDriverBy::className('cover-card'));
	}catch(Exception $e){
		$this->fail('->Failed to see covers block in act page');
	}
	//Validate happy point block is present in page
	try{
		$this->webDriver->findElement(WebDriverBy::className('happy-point-block-wrap'));
		$this->webDriver->findElement(WebDriverBy::cssSelector('.happy-point-block-wrap .col-md-10 iframe'));
	}catch(Exception $e){
		$this->fail('->Failed to see happy point block in act page');
	}
	//Validate footer block is present in page
	try{
		$this->webDriver->findElement(WebDriverBy::className('site-footer'));
		$this->webDriver->findElement(WebDriverBy::className('footer-social-media'));
		$this->webDriver->findElement(WebDriverBy::className('footer-links'));
		$this->webDriver->findElement(WebDriverBy::className('footer-links-secondary'));
	}catch(Exception $e){
		$this->fail('->Failed to see footer block in act page');
  	}
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
