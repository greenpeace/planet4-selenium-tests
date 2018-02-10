<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../wp-core/login.php';

class P4_ThreeColumn extends P4_login {

  /**
   * @var \RemoteWebDriver
   */


  public function testThreeColumn()
  {
  	//Log in
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
	$this->webDriver->getKeyboard()->sendKeys('Test automated - Three Column Content');

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

	//Select Three Columns block
	try{
		$ta = $this->webDriver->findElement(
		WebDriverBy::cssSelector("li[data-shortcode='shortcake_content_three_column']")
		);
		$ta -> click();
	}catch(Exception $e){
		$this->fail("->Failed to select 'Three Column Content' post element");
	}

	//Validate corresponding fields are present
	try{
		$this->webDriver->findElement(WebDriverBy::name("title"));
		$this->webDriver->findElement(WebDriverBy::name("description"));
		$this->webDriver->findElement(WebDriverBy::id("image_1"));
		$this->webDriver->findElement(WebDriverBy::id("image_2"));
		$this->webDriver->findElement(WebDriverBy::id("image_3"));
	}catch(Exception $e){
		$this->fail("->Fields corresponding to 'Three Column Content' block not found");
	}

	//Enter Block Title and Description
	$titl = 'Three column block Test';
	$desc = 'This is content created by an automated test for testing content in 3-column block';
	$field = $this->webDriver->findElement(
	WebDriverBy::name('title')
	);
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys("$titl");
	
	$field = $this->webDriver->findElement(
	WebDriverBy::name('description')
 	);
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys("$desc");
	
	//UPLOAD IMAGE ON COLUMN 1
	$field = $this->webDriver->findElement(
	WebDriverBy::id('image_1'))->click();
	$tab = $this->webDriver->findElement(WebDriverBy::linkText('Media Library'));
	$tab->click();
    //Wait for media library to load
	$this->webDriver->wait(10, 1000)->until(
	WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector('ul.attachments'))
	);
	$this->webDriver->manage()->timeouts()->implicitlyWait(10);
    //Select first image
    $srcfirstchild = $this->webDriver->findElement(
		WebDriverBy::cssSelector("li.attachment:first-child img"))->getAttribute('src');
	$img = $this->webDriver->findElement(WebDriverBy::cssSelector("li.attachment:first-child"));
	$img->click();
	//Get info needed to upload image 2 and 3
	$img2 = $this->webDriver->findElement(
		WebDriverBy::cssSelector("li.attachment:nth-child(2)"))->getAttribute('data-id');
	$src2child = $this->webDriver->findElement(
		WebDriverBy::cssSelector("li.attachment:nth-child(2) img"))->getAttribute('src');
	$img3 = $this->webDriver->findElement(
		WebDriverBy::cssSelector("li.attachment:nth-child(3)"))->getAttribute('data-id');
	$src3child = $this->webDriver->findElement(
		WebDriverBy::cssSelector("li.attachment:nth-child(3) img"))->getAttribute('src');
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

	//Edit WYSIWYG text to add image 2 and 3
	$this->webDriver->findElement(WebDriverBy::id("content-html"))->click();
	$this->webDriver->findElement(WebDriverBy::id("content"))->click();
	$this->webDriver->getKeyboard()->pressKey(WebDriverKeys::ARROW_RIGHT);
	$this->webDriver->getKeyboard()->pressKey(WebDriverKeys::BACKSPACE);
	$this->webDriver->getKeyboard()->pressKey(WebDriverKeys::BACKSPACE);
	$this->webDriver->getKeyboard()->sendKeys("image_2=$img2 image_3=$img3 /]");

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
	//Go to page to validate page contains added block
	$link = $this->webDriver->findElement(
	WebDriverBy::linkText('View page')
	);	
	$link->click();
	//If alert shows up asking to confirm leaving the page, confirm
	try{
		$this->webDriver->switchTo()->alert()->accept();
	}catch(Exception $e){}
	try{
		$this->webDriver->findElement(WebDriverBy::className('split-three-column'));
		$this->webDriver->findElement(WebDriverBy::cssSelector('.col:nth-child(2)'));
		$this->webDriver->findElement(WebDriverBy::cssSelector('.col:nth-child(3)'));
		$titl_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('div.three-column-info h2'))->getText();
		$desc_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('div.three-column-info p'))->getText();
		$srcimg1 = explode("-",$this->webDriver->findElement(
			WebDriverBy::cssSelector('.three-column-images .first-column img'))->getAttribute('src'));
		$srcimg1 = $srcimg1[1];
		$srcimg2 = explode("-",$this->webDriver->findElement(
			WebDriverBy::cssSelector('.three-column-images .second-column img'))->getAttribute('src'));
		$srcimg2 = $srcimg2[1];
		$srcimg3 = explode("-",$this->webDriver->findElement(
			WebDriverBy::cssSelector('.three-column-images .third-column img'))->getAttribute('src'));
		$srcimg3 = $srcimg3[1];
	}catch(Exception $e){
		$this->fail('->Some of the content created is not displayed in front end page');
	}
	$this->assertEquals("$titl","$titl_pg");
	$this->assertEquals("$desc","$desc_pg");
	$this->assertContains("$srcimg1","$srcfirstchild");
	$this->assertContains("$srcimg2","$src2child");
	$this->assertContains("$srcimg3","$src3child");
	// I log out after test
	$this->wpLogout();
	
	echo "\n-> Three column block test PASSED";
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
