<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../wp-core/login.php';

class P4_CarouselHeader extends P4_login {

  /**
   * @var \RemoteWebDriver
   */


  public function testCarouselHEader()
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
    $this->webDriver->getKeyboard()->sendKeys('Test automated - Carousel Header');

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

	//Select Carousel Header block
	try{
	$ta = $this->webDriver->findElement(
	WebDriverBy::cssSelector("li[data-shortcode='shortcake_carousel_header']")
	);
	$ta -> click();
	}catch(Exception $e){
		$this->fail("->Failed to select 'Carousel Header' post element");
	}

	//Validate corresponding fields are present
	try{
		$this->webDriver->findElement(WebDriverBy::id("image_1"));
		$this->webDriver->findElement(WebDriverBy::name("header_1"));
		$this->webDriver->findElement(WebDriverBy::name("subheader_1"));
		$this->webDriver->findElement(WebDriverBy::name("description_1"));
		$this->webDriver->findElement(WebDriverBy::name("link_text_1"));
		$this->webDriver->findElement(WebDriverBy::name("link_url_1"));
		$this->webDriver->findElement(WebDriverBy::id("image_2"));
		$this->webDriver->findElement(WebDriverBy::name("header_2"));
		$this->webDriver->findElement(WebDriverBy::name("subheader_2"));
		$this->webDriver->findElement(WebDriverBy::name("description_2"));
		$this->webDriver->findElement(WebDriverBy::name("link_text_2"));
		$this->webDriver->findElement(WebDriverBy::name("link_url_2"));
		$this->webDriver->findElement(WebDriverBy::id("image_3"));
		$this->webDriver->findElement(WebDriverBy::name("header_3"));
		$this->webDriver->findElement(WebDriverBy::name("subheader_3"));
		$this->webDriver->findElement(WebDriverBy::name("description_3"));
		$this->webDriver->findElement(WebDriverBy::name("link_text_3"));
		$this->webDriver->findElement(WebDriverBy::name("link_url_3"));
		$this->webDriver->findElement(WebDriverBy::id("image_4"));
		$this->webDriver->findElement(WebDriverBy::name("header_4"));
		$this->webDriver->findElement(WebDriverBy::name("subheader_4"));
		$this->webDriver->findElement(WebDriverBy::name("description_4"));
		$this->webDriver->findElement(WebDriverBy::name("link_text_4"));
		$this->webDriver->findElement(WebDriverBy::name("link_url_4"));
	}catch(Exception $e){
		$this->fail("->Fields corresponding to 'Carousel Header' block not found");
	}


	//---Fill in fields for slide 1
	$titl1 = 'Header 1 Test';
	$subtitl1 = 'Subheader 1 Test';
	$desc1 = 'This is test content created by an automated test for testing content in slide 1 of carousel header block';
	$blink1 = 'Detox Germany';
	$burl1 = 'http://www.detox-outdoor.org/de-CH/';
	$this->webDriver->findElement(WebDriverBy::id('image_1'))->click();
	$this->webDriver->findElement(WebDriverBy::linkText('Media Library'))->click();
	//Wait for media library to load
	$this->webDriver->wait(10, 1000)->until(
		WebDriverExpectedCondition::presenceOfElementLocated(
		WebDriverBy::cssSelector('ul.attachments')));
	$this->webDriver->manage()->timeouts()->implicitlyWait(10);
	//Select first image of media library
	$srcfirstchild = $this->webDriver->findElement(
		WebDriverBy::cssSelector("li.attachment:first-child img"))->getAttribute('src');
	$this->webDriver->findElement(WebDriverBy::cssSelector("li.attachment:first-child"))->click();
	$this->webDriver->findElement(WebDriverBy::className("media-button-select"))->click();
	//Fill in rest of fields
	$this->webDriver->findElement(WebDriverBy::name('header_1'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$titl1");
	$this->webDriver->findElement(WebDriverBy::name('subheader_1'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$subtitl1");
	$this->webDriver->findElement(WebDriverBy::name('description_1'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$desc1");
	$this->webDriver->findElement(WebDriverBy::name('link_text_1'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$blink1");
	$this->webDriver->findElement(WebDriverBy::name('link_url_1'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$burl1");
	
	//---Fill in fields for slide 2
	$titl2 = 'Header 2 Test';
	$subtitl2 = 'Subheader 2 Test';
	$desc2 = 'This is test content created by an automated test for testing content in slide 2 of carousel header block';
	$blink2 = 'Detox Italy';
	$burl2 = 'http://www.detox-outdoor.org/it-IT/';
	/** Adding second image pending after fixing bug

	$this->webDriver->findElement(WebDriverBy::id('image_1'))->click();
	$this->webDriver->findElement(WebDriverBy::linkText('Media Library'))->click();
	//Wait for media library to load
	$this->webDriver->wait(10, 1000)->until(
		WebDriverExpectedCondition::presenceOfElementLocated(
		WebDriverBy::cssSelector('ul.attachments')));
	$this->webDriver->manage()->timeouts()->implicitlyWait(10);
	//Select first image of media library
	$this->webDriver->findElement(WebDriverBy::cssSelector("li.attachment:first-child"))->click();
	$this->webDriver->findElement(WebDriverBy::className("media-button-select"))->click();
	**/
	//Fill in rest of fields
	$this->webDriver->findElement(WebDriverBy::name('header_2'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$titl2");
	$this->webDriver->findElement(WebDriverBy::name('subheader_2'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$subtitl2");
	$this->webDriver->findElement(WebDriverBy::name('description_2'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$desc2");
	$this->webDriver->findElement(WebDriverBy::name('link_text_2'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$blink2");
	$this->webDriver->findElement(WebDriverBy::name('link_url_2'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$burl2");

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
		$this->webDriver->findElement(WebDriverBy::className("carousel-header"));
		$this->assertEquals("$titl1",
			$this->webDriver->findElement(WebDriverBy::cssSelector(".carousel-caption .page-header h1"))->getText());
		$this->assertEquals("$subtitl1",
			$this->webDriver->findElement(WebDriverBy::cssSelector(".carousel-caption .page-header h3"))->getText());
		$this->assertEquals("$desc1",
			$this->webDriver->findElement(WebDriverBy::cssSelector(".carousel-caption .page-header p"))->getText());
		//Get image source and remove format extension so that we can compare it to the thumbnail src
		$srcimg = substr(($this->webDriver->findElement(
		WebDriverBy::cssSelector('#carousel-wrapper .carousel-item.active img'))
		->getAttribute('src')), 0, -4);
		$this->assertContains("$srcimg","$srcfirstchild");
	}catch(Exception $e){
		$this->fail("->Some of the content created is not displayed in front end page");
	}
	

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
