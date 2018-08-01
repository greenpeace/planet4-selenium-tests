<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../AbstractClass.php';

class P4_ExplorePage extends AbstractClass {

  /**
   * @var \RemoteWebDriver
   */

  public function testExplorepage()
  {
  	$this->driver->get($this->_url);
  	$this->driver->findElement(WebDriverBy::className('explore-nav-link'))->click();
  	//$url_act = $this->webDriver->getCurrentURL();
  	$this->assertEquals($this->driver->getCurrentURL(),"https://dev.p4.greenpeace.org/international/explore/");

	//Validate header block is present in page
	try{
		$this->driver->findElement(WebDriverBy::className('page-header'));
		$this->driver->findElement(WebDriverBy::className('page-header-title'));
		$this->driver->findElement(WebDriverBy::className('page-header-subtitle'));
		$this->driver->findElement(WebDriverBy::className('page-header-content'));
	}catch(Exception $e){
		$this->fail('->Failed to see header block in explore page');
	}

	//Validate split 2 column block is present in page
	try{
		$this->driver->findElement(WebDriverBy::className('split-two-column'));
		$this->driver->findElement(WebDriverBy::cssSelector('.split-two-column-item.item--left'));
		$this->driver->findElement(WebDriverBy::cssSelector('.split-two-column-item.item--right'));
		$this->driver->findElement(WebDriverBy::className('split-two-column-item-image'));
		$this->driver->findElement(WebDriverBy::className('split-two-column-item-content'));
		
	}catch(Exception $e){
		$this->fail('->Failed to see split 2 column block block in explore page');
	}

	//Validate articles block is present in page
	try{
		$this->driver->findElement(WebDriverBy::className('article-listing'));
		$this->driver->findElement(WebDriverBy::className('article-listing-intro'));
		$this->driver->findElement(WebDriverBy::className('article-list-section'));
		$this->driver->findElement(WebDriverBy::className('article-list-item'));

		
	}catch(Exception $e){
		$this->fail('->Failed to see articles block in explore page');
	}

	//Validate happy point block is present in page
	try{
		$this->driver->findElement(WebDriverBy::className('happy-point-block-wrap'));
		$element = $this->driver->findElement(
     		WebDriverBy::id('happy-point'));
		//Scroll down and wait to happy point to load
   		$element->getLocationOnScreenOnceScrolledIntoView(); 
   		usleep(2000000);
   		$this->driver->switchTo()->frame($this->driver->findElement(
			WebDriverBy::cssSelector('#happy-point iframe')));
   		//Validate input fields are present
   		$this->driver->findElement(WebDriverBy::id("en__field_supporter_emailAddress"));
		$this->driver->findElement(WebDriverBy::id("en__field_supporter_country"));
		$this->driver->findElement(WebDriverBy::className("subscriber-btn"));
	}catch(Exception $e){
		$this->fail('->Failed to see happy point block in explore page');
	}
	$this->driver->switchTo()->defaultContent();
	//Validate footer block is present in page
	try{
		$this->driver->findElement(WebDriverBy::className('site-footer'));
		$this->driver->findElement(WebDriverBy::className('footer-social-media'));
		$this->driver->findElement(WebDriverBy::className('footer-links'));
		$this->driver->findElement(WebDriverBy::className('footer-links-secondary'));
	}catch(Exception $e){
		$this->fail('->Failed to see footer block in explore page');
  	}
  	echo "\n-> Explore page test PASSED";
 }

  protected function assertElementNotFound($by)
  {
	$this->driver->takeScreenshot( 'reports/screenshots/' . __CLASS__ . '.png');
	$els = $this->driver->findElements($by);
	if (count($els)) {
		$this->fail("Unexpectedly element was found");
	}
	// increment assertion counter
	$this->assertTrue(true);

  }
}
