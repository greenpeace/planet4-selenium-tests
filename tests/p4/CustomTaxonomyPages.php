<?php

use WebDriverBy as By;

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../AbstractClass.php';

class P4_CustomTaxonomyPages extends AbstractClass {

  /**
   * @var \RemoteWebDriver
   */

  public function testCustomTaxonomyPage()
  {
  	$this->webDriver->get($this->_url);
  	$this->webDriver->findElement(By::className('page-type'))->click();
  	//$url_act = $this->webDriver->getCurrentURL();
  	$this->assertEquals($this->webDriver->getCurrentURL(), rtrim( $this->_url, '/' ) . "/page_type/press/");

	// Validate header block is present in page
	try{
		$this->webDriver->findElement(By::className('page-header'));
		$this->webDriver->findElement(By::className('page-header-title'));
	}catch(Exception $e){
		$this->fail('->Failed to see header block in custom taxonomy page');
    }

    // Validate at lease one post is present on the page
	try{
		$this->webDriver->findElement(By::className('search-result-list-item'));
		$this->webDriver->findElement(By::className('search-result-item-headline'));
	}catch(Exception $e){
		$this->fail('->Failed to see posts block in custom taxonomy page');
    }

	//Validate footer block is present in page
	try{
		$this->webDriver->findElement(By::className('site-footer'));
		$this->webDriver->findElement(By::className('footer-social-media'));
		$this->webDriver->findElement(By::className('footer-links'));
		$this->webDriver->findElement(By::className('footer-links-secondary'));
	}catch(Exception $e){
		$this->fail('->Failed to see footer block in custom taxonomy page');
    }
      
  	echo "\n-> Custom Taxonomy Page test PASSED";
  }
}
?>
