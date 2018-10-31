<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../wp-core/login.php';

class P4_Carousel extends P4_login {

  /**
   * @var \RemoteWebDriver
   */


  public function testCarousel()
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
	'Add Page Element',$this->webDriver->findElement(
	WebDriverBy::className('shortcake-add-post-element'))->getText()
	);


    //Enter title of page
    $field	= $this->webDriver->findElement(
	WebDriverBy::id('title-prompt-text')
    );
    $field->click();
    $this->webDriver->getKeyboard()->sendKeys('Test automated - Carousel');

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

	//Select Carousel block
	try{
	$ta = $this->webDriver->findElement(
	WebDriverBy::cssSelector("li[data-shortcode='shortcake_carousel']")
	);
	$ta -> click();
	}catch(Exception $e){
		$this->fail("->Failed to select 'Carousel' post element");
	}

	//Enter Block Title
	$titl = 'Carousel Block Test';
	$field = $this->webDriver->findElement(
	WebDriverBy::name('carousel_block_title')
	);
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys("$titl");
	
	//Upload Carousel Images
	$btn = $this->webDriver->findElement(
	WebDriverBy::id('multiple_image')
	);
	$btn->click();
	
	$tab = $this->webDriver->findElement(
	WebDriverBy::linkText('Media Library')
	);
	$tab->click();
	
	//Wait for media library to load
	$this->webDriver->wait(10, 1000)->until(
		WebDriverExpectedCondition::presenceOfElementLocated(
		WebDriverBy::cssSelector('ul.attachments')));
	$this->webDriver->manage()->timeouts()->implicitlyWait(10);
	//Select first image of media library
	$srcfirstchild = explode("-",$this->webDriver->findElement(
		WebDriverBy::cssSelector("li.attachment:first-child img"))->getAttribute('src'));
	$srcfirstchild = $srcfirstchild[1];
	$srcsecondchild = explode("-",$this->webDriver->findElement(
		WebDriverBy::cssSelector("li.attachment:nth-child(2) img"))->getAttribute('src'));
	$srcsecondchild = $srcsecondchild[1];
	$srcthirdchild = explode("-",$this->webDriver->findElement(
		WebDriverBy::cssSelector("li.attachment:nth-child(3) img"))->getAttribute('src'));
	$srcthirdchild = $srcthirdchild[1];
	$this->webDriver->findElement(WebDriverBy::cssSelector("li.attachment:first-child"))->click();
	$thirdimg = $this->webDriver->findElement(WebDriverBy::cssSelector("li.attachment:nth-child(3)"));
	//Press shift key while clicking on third image so that 3 images are selected
	$this->webDriver->action()->keyDown(null,WebDriverKeys::SHIFT)->click($thirdimg)->keyUp(null,WebDriverKeys::SHIFT)->perform();
	$this->webDriver->findElement(WebDriverBy::className("media-button-select"))->click();

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
	usleep(2000000);
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
		$this->webDriver->findElement(WebDriverBy::className('carousel-wrap'));
		$this->webDriver->findElement(WebDriverBy::className('slide'));
		$titl_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('.carousel.slide h1'))->getText();
		$srcimg1 = explode("-",$this->webDriver->findElement(
			WebDriverBy::cssSelector('.carousel-inner .carousel-item:nth-child(2) img'))->getAttribute('src'));
		$srcimg1 = $srcimg1[1];
		$srcimg2 = explode("-",$this->webDriver->findElement(
			WebDriverBy::cssSelector('.carousel-inner .carousel-item:nth-child(3) img'))->getAttribute('src'));
		$srcimg2 = $srcimg2[1];
		$srcimg3 = explode("-",$this->webDriver->findElement(
			WebDriverBy::cssSelector('.carousel-inner .carousel-item:nth-child(4) img'))->getAttribute('src'));
		$srcimg3 = $srcimg3[1];
	}catch(Exception $e){
		$this->fail('->Some of the content created is not displayed in front end page');
	}
	$this->assertEquals("$titl","$titl_pg");
	$this->assertContains("$srcimg1","$srcfirstchild");
	$this->assertContains("$srcimg2","$srcsecondchild");
	$this->assertContains("$srcimg3","$srcthirdchild");
	// I log out after test
	$this->wpLogout();
	echo "\n-> Carousel block test PASSED";
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
