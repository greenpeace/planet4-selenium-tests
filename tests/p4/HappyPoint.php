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
		$this->webDriver->findElement(WebDriverBy::name("opacity"));
		$this->webDriver->findElement(WebDriverBy::id("boxout_title"));
		$this->webDriver->findElement(WebDriverBy::name("boxout_descr"));
		$this->webDriver->findElement(WebDriverBy::name("boxout_link_text"));
		$this->webDriver->findElement(WebDriverBy::name("boxout_link_url"));
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

	$op = '50';
	$titl = 'Happy test title';
	$desc = 'This is content created by an automated test for testing happy point block';
	$blink = 'Test button';
	$burl = 'http://www.greenpeace.org';	

	$field = $this->webDriver->findElement(
		WebDriverBy::name('opacity'));
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys("$op");
	$field = $this->webDriver->findElement(
		WebDriverBy::name('boxout_title'));
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys("$titl");
	$field = $this->webDriver->findElement(
		WebDriverBy::name('boxout_descr'));
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys("$desc");
	$field = $this->webDriver->findElement(
		WebDriverBy::name('boxout_link_text'));
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys("$blink");
	$field = $this->webDriver->findElement(
		WebDriverBy::name('boxout_link_url'));
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys("$burl");
		
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
		
		$this->webDriver->findElement(WebDriverBy::className('happy-point-block-wrap'));
		$srcimg = substr($this->webDriver->findElement(
			WebDriverBy::cssSelector('.col-md-10 pt-20 iframe'))->getAttribute('src'), 0, -4);
		$this->assertContains("$srcimg","$srcfirstchild");



		$this->webDriver->findElement(WebDriverBy::id('p4bks_tasks_container'));
		$this->assertEquals("$ttitle",$this->webDriver->findElement(
			WebDriverBy::cssSelector('#p4bks_tasks_container .container h3'))->getText());
		$this->assertEquals("$tdesc",$this->webDriver->findElement(
			WebDriverBy::cssSelector('#p4bks_tasks_container .container div.col-md-12'))->getText());
		/**
		$this->assertEquals('1',$this->webDriver->findElement(
			WebDriverBy::cssSelector('.col:nth-child(1) .step-info-wrap span'))->getText());
		$this->assertEquals("$title1",$this->webDriver->findElement(
			WebDriverBy::cssSelector('div.col:nth-child(1) .steps-information h5'))->getText());
		$this->assertEquals("$desc1",$this->webDriver->findElement(
			WebDriverBy::cssSelector('div.col:nth-child(1) .steps-information p'))->getText());
		$this->assertEquals("2",$this->webDriver->findElement(
			WebDriverBy::cssSelector('span.step-number:nth-child(2)'))->getText());
		$this->assertEquals("$title2",$this->webDriver->findElement(
			WebDriverBy::cssSelector('div.col:nth-child(2) .steps-information h5'))->getText());
		$this->assertEquals("$desc2",$this->webDriver->findElement(
			WebDriverBy::cssSelector('div.col:nth-child(2) .steps-information p'))->getText());
	}catch(Exception $e){
		$this->fail('->Some of the content created is not displayed in front end page');
	}
	**/

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
