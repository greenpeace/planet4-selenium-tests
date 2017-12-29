<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../AbstractClass.php';

class P4_ExplorePage extends AbstractClass {

  /**
   * @var \RemoteWebDriver
   */

  public function testExplorepage()
  {
  	$this->webDriver->get($this->_url);
  	$this->webDriver->findElement(WebDriverBy::className('explore-nav-link'))->click();
  	//$url_act = $this->webDriver->getCurrentURL();
  	$this->assertEquals($this->webDriver->getCurrentURL(),"https://dev.p4.greenpeace.org/international/explore/");

	//Validate header block is present in page
	try{
		$this->webDriver->findElement(WebDriverBy::className('page-header'));
		$this->webDriver->findElement(WebDriverBy::className('page-header-title'));
		$this->webDriver->findElement(WebDriverBy::className('page-header-subtitle'));
		$this->webDriver->findElement(WebDriverBy::className('page-header-content'));
	}catch(Exception $e){
		$this->fail('->Failed to see header block in act page');
	}

	//Validate split 2 column block is present in page
	try{
		$this->webDriver->findElement(WebDriverBy::className('split-two-column'));
		$this->webDriver->findElement(WebDriverBy::cssSelector('.split-two-column-item.item--left'));
		$this->webDriver->findElement(WebDriverBy::cssSelector('.split-two-column-item.item--right'));
		$this->webDriver->findElement(WebDriverBy::className('split-two-column-item-image'));
		$this->webDriver->findElement(WebDriverBy::className('split-two-column-item-content'));
		
	}catch(Exception $e){
		$this->fail('->Failed to see split 2 column block block in act page');
	}

	//Validate articles block is present in page
	try{
		$this->webDriver->findElement(WebDriverBy::className('article-listing'));
		$this->webDriver->findElement(WebDriverBy::className('article-listing-intro'));
		$this->webDriver->findElement(WebDriverBy::className('article-list-section'));
		$this->webDriver->findElement(WebDriverBy::className('article-list-item'));

		
	}catch(Exception $e){
		$this->fail('->Failed to see articles block in act page');
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
  	echo "\n-> Explore page test PASSED";
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
