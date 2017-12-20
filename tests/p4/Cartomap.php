<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../wp-core/login.php';

class P4_Jetpack extends P4_login {

  /**
   * @var \RemoteWebDriver
   */


  public function testJetpack()
  {

  	//Define variables
  	$cartomaplink = 'https://greenpeacemaps.carto.com/builder/d1359bc0-339b-40af-bfd0-98f97a574f3a/embed';
  	$baseurl = 'greenpeacemaps.carto.com';
  	$urlid = 'd1359bc0-339b-40af-bfd0-98f97a574f3a';

   	//I log in
	try{
   		$this->wpLogin();
	}catch(Exception $e){
		$this->fail('->Failed to log in, verify credentials and URL');
	}

	//Go to pages and create content  
   	$this->webDriver->wait(3);
	$pages = $this->webDriver->findElement(
		WebDriverBy::id("menu-pages"));
	$pages->click();
	try{
		$link = $this->webDriver->findElement(
			WebDriverBy::linkText("Add New")
		);
	}catch(Exception $e){
		$this->fail("->Could not find 'Add New' button in Pages overview");
	}
	$link->click();

	//Enter title of page
	$field	= $this->webDriver->findElement(
		WebDriverBy::id('title-prompt-text'));
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys('Test automated - Cartomap');

	//Insert map link in body field
	$field	= $this->webDriver->findElement(
		WebDriverBy::id('content'));
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys("$cartomaplink");
	
	//Publish content
	$this->webDriver->findElement(
                WebDriverBy::id('publish')
	)->click();
	
	//Wait to see successful message
	$this->webDriver->wait(10, 1000)->until(
		WebDriverExpectedCondition::visibilityOfElementLocated(
		WebDriverBy::id('message')));
	//Validate I see successful message
	try{
		$this->assertContains(
			'Page published',$this->webDriver->findElement(
			WebDriverBy::id('message'))->getText()
		);
	}catch(Exception $e){
		$this->fail('->Failed to publish content - no sucessful message after saving content');
	}
	//Wait for saved changes to load
	usleep(2000000);
	//Go to page to validate page contains map
	$link = $this->webDriver->findElement(
	WebDriverBy::linkText('View page')
	);	
	$link->click();
	//If alert shows up asking to confirm leaving the page, confirm
	try{
		$this->webDriver->switchTo()->alert()->accept();
	}catch(Exception $e){}
	//Wait for map to load
	usleep(2000000);
	try{
		$cartomap = explode("/",$this->webDriver->findElement(
			WebDriverBy::cssSelector('.page-template-default iframe'))->getAttribute('src'));
		$baseurl_pg = $cartomap[2];
		$urlid_pg = $cartomap[4];
	}catch(Exception $e){
		$this->fail("->Some of the content created is not displayed in front end page");
	}
	$this->assertEquals("$baseurl","$baseurl_pg");
	$this->assertEquals("$urlid","$urlid_pg");

	// I log out after test
    $this->wpLogout();
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
