<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../wp-core/login.php';

class P4_Covers extends P4_login {

  /**
   * @var \RemoteWebDriver
   */


  public function testCovers()
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
	WebDriverBy::id("menu-pages")
	);
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
	//WebDriverBy::id('title-prompt-text')
		WebDriverBy::id('titlewrap')
	);
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys('Test automated - Take Action Covers test');

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

	//Select TakeAction block
	try{
		$ta = $this->webDriver->findElement(
		WebDriverBy::cssSelector("li[data-shortcode='shortcake_covers']")
		);
		$ta -> click();
	}catch(Exception $e){
		$this->fail("->Failed to select 'Take Action Covers' post element");
	}

	//Fill in Block fields
	$titl = 'Take Action Block Test';
	$desc = 'This is content created by an automated test for testing take action covers block';
	$tg = 'FixFood';
	
	$field = $this->webDriver->findElement(
		WebDriverBy::name('title'));
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys("$titl");
	
	$field = $this->webDriver->findElement(
		WebDriverBy::name('description'));
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys("$desc");

	$field = $this->webDriver->findElement(
		WebDriverBy::className('select2-selection__rendered'));
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys("$tg");
	//Sleep for 3 seconds
	usleep(5000000); 
	//Select suggestion
	$this->webDriver->getKeyboard()->pressKey(WebDriverKeys::ENTER);


	//Insert block
	try{
		$insert = $this->webDriver->findElement(
		WebDriverBy::className('media-button-insert')
		);
		$insert -> click();
	}catch(Exception $e){
		$this->fail('->Failed to insert element');
	}


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
	$this->webDriver->manage()->timeouts()->implicitlyWait(100);
	//Go to page to validate page contains Articles Block
	$link = $this->webDriver->findElement(
	WebDriverBy::linkText('View page')
	);	
	$link->click();
	//If alert shows up asking to confirm leaving the page, confirm
	try{
		$this->webDriver->switchTo()->alert()->accept();
	}catch(Exception $e){}
	try{
		$this->webDriver->findElement(WebDriverBy::className('covers-block'));
		$titl_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('h2.page-section-header'))->getText();
		$desc_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('p.page-section-description'))->getText();
		$subtg1 = substr(($this->webDriver->findElement(
			WebDriverBy::cssSelector('.col-lg-4.col-md-6:first-child .cover-card a:first-child'))->getText()), 1, strlen("$tg"));
		$subtg2 = substr(($this->webDriver->findElement(
			WebDriverBy::cssSelector('.col-lg-4.col-md-6:nth-child(2) .cover-card a:first-child'))->getText()), 1, strlen("$tg"));
		$subtg3 = substr(($this->webDriver->findElement(
			WebDriverBy::cssSelector('.col-lg-4.col-md-6:nth-child(2) .cover-card a:first-child'))->getText()), 1, strlen("$tg"));
		$btntxt = $this->webDriver->findElement(WebDriverBy::cssSelector('.cover-card:first-child .cover-card-btn'))->getText();
	}catch(Exception $e){
		$this->fail('->Some of the content created is not displayed in front end page');
	}
	$this->assertEquals("$titl", "$titl_pg");
	$this->assertEquals("$desc", "$desc_pg");
	$this->assertEquals("$tg", "$subtg1");
	$this->assertEquals("$tg", "$subtg2");
	$this->assertEquals("$tg", "$subtg3");
	$this->assertEquals("TAKE ACTION", "$btntxt");

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

