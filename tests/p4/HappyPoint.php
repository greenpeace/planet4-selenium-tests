<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../wp-core/login.php';

class P4_HappyPoint extends P4_login {

  /**
   * @var \RemoteWebDriver
   */


  public function testHappyPoint()
  {

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

	//Validate button to add blocks to page is present
	$this->assertContains(
	'Add Post Element',$this->webDriver->findElement(
	WebDriverBy::className('shortcake-add-post-element'))->getText()
	);

	//Enter title of page
	$field	= $this->webDriver->findElement(
	WebDriverBy::id('title-prompt-text')
	);
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys('Test automated - Happy Point');

	//Click on button to add blocks
	$add = $this->webDriver->findElement(
	WebDriverBy::className("shortcake-add-post-element")
	);
	$add->click();

	//Validate blocks modal window is shown
	$this->assertContains(
	'Insert Post Element',$this->webDriver->findElement(
	WebDriverBy::className('media-frame-title'))->getText()
	);

	//Select block
	try{
	$ta = $this->webDriver->findElement(
	WebDriverBy::cssSelector("li[data-shortcode='shortcake_happy_point']")
	);
	$ta -> click();
	}catch(Exception $e){
		$this->fail("->Failed to select 'Happy Point' block post element");
	}

	//Validate corresponding fields are visible
	try{
		$this->webDriver->findElement(WebDriverBy::id("background"));
		$this->webDriver->findElement(WebDriverBy::name("mailing_list_iframe"));
	}catch(Exception $e){
		$this->fail("->Could not find tasks fields for 'Happy Point' block post element");
	}

	//---- Fill in fields
	$this->webDriver->findElement(WebDriverBy::id("background"))->click();
	$tab = $this->webDriver->findElement(WebDriverBy::linkText('Media Library'));
	$tab->click();
	//Wait for media library to load
	$this->webDriver->wait(10, 1000)->until(
  	WebDriverExpectedCondition::presenceOfElementLocated(
	WebDriverBy::cssSelector('ul.attachments'))
	);
	$this->webDriver->manage()->timeouts()->implicitlyWait(10);
	//Select first image
	$srcfirstchild = $this->webDriver->findElement(
		WebDriverBy::cssSelector("li.attachment:first-child img"))->getAttribute('src');
	$img = $this->webDriver->findElement(WebDriverBy::cssSelector("li.attachment:first-child"));
	$img->click();
	$this->webDriver->findElement(WebDriverBy::className("media-button-select"))->click();

	$this->webDriver->findElement(WebDriverBy::name("mailing_list_iframe"))->click();

	$chckbx = $this->webDriver->findElement(WebDriverBy::name("mailing_list_iframe"))->getAttribute('value');
	if ($chckbx != "true"){
		$this->webDriver->findElement(WebDriverBy::name("mailing_list_iframe"))->click();
	}
		
	//Insert block
	try{
		$insert = $this->webDriver->findElement(
		WebDriverBy::className('media-button-insert')
		);
		$insert -> click();
	}catch(Exception $e){
		$this->fail('->Failed to insert element');
	}

	$this->webDriver->manage()->timeouts()->implicitlyWait(5);

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
	$this->webDriver->manage()->timeouts()->implicitlyWait(10);
	//Go to page to validate page contains added block
	$link = $this->webDriver->findElement(
	WebDriverBy::linkText('View page')
	);	
	$link->click();
	//If alert shows up asking to confirm leaving the page, confirm
	try{
		$this->webDriver->switchTo()->alert()->accept();
	}catch(Exception $e){}

	//try{
		$srcimg = substr($this->webDriver->findElement(
			WebDriverBy::className('happy-point-block-wrap'))->getAttribute('src'), 0, -4);
		/**
	}catch(Exception $e){
		$this->fail('->Some of the content created is not displayed in front end page');
	}
	**/
	$this->assertContains("$srcimg","$srcfirstchild");

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
