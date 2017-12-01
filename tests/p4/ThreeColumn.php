<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../wp-core/login.php';

class P4_ThreeColumn extends P4_login {

  /**
   * @var \RemoteWebDriver
   */


  public function testThreeColumn()
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
	$this->webDriver->findElement(WebDriverBy::className("media-button-select"))->click();

	/**
	//Insert block
	try{
		$insert = $this->webDriver->findElement(
		WebDriverBy::className('media-button-insert')
		);
		$insert -> click();
	}catch(Exception $e){
		$this->fail('->Failed to insert element');
	}

	$this->webDriver->switchTo()->frame("content_ifr");
	$cnt = $this->webDriver->findElement(WebDriverBy::cssSelector("[data-id='content']"));
	$cnt->click();
	$this->webDriver->action()->moveToElement($cnt)->perform();
	//$btn = $this->webDriver->findElement(WebDriverBy::id("mceu_57"));
	//$this->webDriver->switchTo()->frame($this->webDriver->findElement(WebDriverBy::id("mceu_57")));
	$this->webDriver->switchTo()->defaultContent();
	$this->webDriver->findElement(WebDriverBy::id("mceu_55"))->click();

	

	//UPLOAD IMAGE ON COLUMN 2
	$field = $this->webDriver->findElement(
	WebDriverBy::id('image_2'))->click();
	$tab = $this->webDriver->findElement(WebDriverBy::linkText('Media Library'));
	$tab->click();
    //Wait for media library to load
	$this->webDriver->wait(10, 1000)->until(
	WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector('ul.attachments'))
	);
	$this->webDriver->manage()->timeouts()->implicitlyWait(10);
    //Select second image
    $srcfirstchild = $this->webDriver->findElement(
		WebDriverBy::cssSelector("li.attachment:nth-child(2) img"))->getAttribute('src');
	//$img = $this->webDriver->findElement(WebDriverBy::cssSelector("li.attachment:nth-child(2)"));
	$modalid = $this->webDriver->findElement(WebDriverBy::cssSelector("ul.attachments"))->getAttribute("id");;
	$xpth = '"//*[@id=\''.$modalid.'\']/li[2]"';
	//$this->webDriver->switchTo()->window($this->webDriver->findElement(
	//	WebDriverBy::id("__wp-uploader-id-3")));
	//$this->webDriver->switchTo()->activeElement();
	//$img->click();
	$this->webDriver->findElement(WebDriverBy::xpath("$xpth"))->click();
	$this->webDriver->findElement(WebDriverBy::className("media-button-select"))->click();





	//UPLOAD IMAGE ON COLUMN 3
	$field = $this->webDriver->findElement(
	WebDriverBy::id('image_3'))->click();
	$tab = $this->webDriver->findElement(WebDriverBy::linkText('Media Library'));
	$tab->click();
    //Wait for media library to load
	$this->webDriver->wait(10, 1000)->until(
	WebDriverExpectedCondition::presenceOfElementLocated(WebDriverBy::cssSelector('ul.attachments'))
	);
	$this->webDriver->manage()->timeouts()->implicitlyWait(10);
    //Select third image
    $srcfirstchild = $this->webDriver->findElement(
		WebDriverBy::cssSelector("li.attachment:nth-child(3) img"))->getAttribute('src');
	$img = $this->webDriver->findElement(WebDriverBy::cssSelector("li.attachment:nth-child(3)"));
	$img->click();
	$this->webDriver->findElement(WebDriverBy::className("media-button-select"))->click();

**/

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
		$this->assertEquals("$titl",$this->webDriver->findElement(
			WebDriverBy::cssSelector('div.three-column-info h2'))->getText());
		$this->assertEquals("$desc",$this->webDriver->findElement(
			WebDriverBy::cssSelector('div.three-column-info p'))->getText());
		$srcimg = substr(($this->webDriver->findElement(
		WebDriverBy::cssSelector('.col:nth-child(1) .first-column img'))
		->getAttribute('src')), 0, -4);
		$this->assertContains("$srcimg","$srcfirstchild");
		$this->webDriver->findElement(WebDriverBy::cssSelector('.col:nth-child(2)'));
		$this->webDriver->findElement(WebDriverBy::cssSelector('.col:nth-child(3)'));
	}catch(Exception $e){
		$this->fail('->Some of the content created is not displayed in front end page');
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
