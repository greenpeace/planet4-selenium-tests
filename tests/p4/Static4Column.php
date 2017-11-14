<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../wp-core/login.php';

class P4_Static4Column extends P4_login {

  /**
   * @var \RemoteWebDriver
   */


  public function testStatic4Column()
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
	$this->webDriver->getKeyboard()->sendKeys('Test automated - Static 4 Column');

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

	//Select Static 4 column block
	try{
	$ta = $this->webDriver->findElement(
		WebDriverBy::cssSelector("li[data-shortcode='shortcake_static_four_column']")
	);
	$ta -> click();
	}catch(Exception $e){
		$this->fail("->Failed to select 'Static Four Column' post element");
	}

	//Validate corresponding fields are present
	try{
		$this->webDriver->findElement(WebDriverBy::id("attachment_1"));
		$this->webDriver->findElement(WebDriverBy::name("title_1"));
		$this->webDriver->findElement(WebDriverBy::name("description_1"));
		$this->webDriver->findElement(WebDriverBy::name("link_text_1"));
		$this->webDriver->findElement(WebDriverBy::name("link_url_1"));
		$this->webDriver->findElement(WebDriverBy::id("attachment_2"));
        $this->webDriver->findElement(WebDriverBy::name("link_url_2"));
        $this->webDriver->findElement(WebDriverBy::name("link_text_2"));
        $this->webDriver->findElement(WebDriverBy::name("description_2"));
        $this->webDriver->findElement(WebDriverBy::name("title_2"));
		$this->webDriver->findElement(WebDriverBy::id("attachment_3"));
        $this->webDriver->findElement(WebDriverBy::name("title_3"));
        $this->webDriver->findElement(WebDriverBy::name("description_3"));
        $this->webDriver->findElement(WebDriverBy::name("link_text_3"));
        $this->webDriver->findElement(WebDriverBy::name("link_url_3"));
		$this->webDriver->findElement(WebDriverBy::id("attachment_4"));
        $this->webDriver->findElement(WebDriverBy::name("title_4"));
        $this->webDriver->findElement(WebDriverBy::name("description_4"));
        $this->webDriver->findElement(WebDriverBy::name("link_text_4"));
        $this->webDriver->findElement(WebDriverBy::name("link_url_4"));
	}catch(Exception $e){
		$this->fail("->Fields corresponding to 'Static Four Column' block not found");
	}

	//----- FILL IN FIELDS FOR COLUMN 1
	//Define test content
	$title1 = "Column 1 Test";
	$description1 = "This is test content created by an automated test for testing content in column 1 of static 4 column block";
	$linktext1 = "Detox Germany";
	$linkurl1 = "http://www.detox-outdoor.org/de-CH/";
	$this->webDriver->findElement(WebDriverBy::id('attachment_1'))->click();
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
	$this->webDriver->findElement(WebDriverBy::name('title_1'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$title1");
	$this->webDriver->findElement(WebDriverBy::name('description_1'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$description1");
	$this->webDriver->findElement(WebDriverBy::name('link_text_1'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$linktext1");
	$this->webDriver->findElement(WebDriverBy::name('link_url_1'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$linkurl1");
	

	//----- FILL IN FIELDS FOR COLUMN 2
	
	//Define test content
	$title2 = "Column 2 Test";
	$description2 = "This is test content created by an automated test for testing content in column 2 of static 4 column block";
	$linktext2 = "Detox Italy";
	$linkurl2 = "http://www.detox-outdoor.org/it-IT";
	/** Uploading pictures in this column will be postponed until we find a solution for the bug
	$this->webDriver->findElement(WebDriverBy::id('attachment_2'))->click();
	$this->webDriver->findElement(WebDriverBy::linkText('Media Library'))->click();
	//Wait for media library to load
	$this->webDriver->wait(10, 1000)->until(
		WebDriverExpectedCondition::presenceOfElementLocated(
		WebDriverBy::cssSelector('ul.attachments')));
	$this->webDriver->manage()->timeouts()->implicitlyWait(10);
	//Select first image of media library
	$ul_id= $this->webDriver->findElement(WebDriverBy::className("attachments"))->getAttribute('id');
        $xpath = "//*[@id='".$ul_id."']/li[2]";
        $this->webDriver->findElement(WebDriverBy::xPath("{$xpath}"))->click();
	$this->webDriver->findElement(WebDriverBy::className("media-button-select"))->click();
	**/
	//Fill in rest of fields
	$this->webDriver->findElement(WebDriverBy::name('title_2'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$title2");
	$this->webDriver->findElement(WebDriverBy::name('description_2'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$description2");
	$this->webDriver->findElement(WebDriverBy::name('link_text_2'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$linktext2");
	$this->webDriver->findElement(WebDriverBy::name('link_url_2'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$linkurl2");

	//----- FILL IN FIELDS FOR COLUMN 3
	
	//Define test content
	$title3 = "Column 3 Test";
	$description3 = "This is test content created by an automated test for testing content in column 3 of static 4 column block";
	$linktext3 = "Detox France";
	$linkurl3 = "http://www.detox-outdoor.org/fr-CH";
	/** Uploading pictures in this column will be postponed until we find a solution for the bug
	$this->webDriver->findElement(WebDriverBy::id('attachment_3'))->click();
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
	$this->webDriver->findElement(WebDriverBy::name('title_3'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$title3");
	$this->webDriver->findElement(WebDriverBy::name('description_3'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$description3");
	$this->webDriver->findElement(WebDriverBy::name('link_text_3'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$linktext3");
	$this->webDriver->findElement(WebDriverBy::name('link_url_3'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$linkurl3");

	//----- FILL IN FIELDS FOR COLUMN 4
	
	//Define test content
	$title4 = "Column 4 Test";
	$description4 = "This is test content created by an automated test for testing content in column 4 of static 4 column block";
	$linktext4 = "Detox Finland";
	$linkurl4 = "http://www.detox-outdoor.org/fi";
	/** Uploading pictures in this column will be postponed until we find a solution for the bug
	$this->webDriver->findElement(WebDriverBy::id('attachment_4'))->click();
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
	$this->webDriver->findElement(WebDriverBy::name('title_4'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$title4");
	$this->webDriver->findElement(WebDriverBy::name('description_4'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$description4");
	$this->webDriver->findElement(WebDriverBy::name('link_text_4'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$linktext4");
	$this->webDriver->findElement(WebDriverBy::name('link_url_4'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$linkurl4");


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
		WebDriverExpectedCondition::presenceOfElementLocated(
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
	$this->webDriver->manage()->timeouts()->implicitlyWait(10000);
	//Go to page to validate page contains added block
	$link = $this->webDriver->findElement(
		WebDriverBy::linkText('View page')
	);	
	$link->click();	

	try{
		$this->webDriver->switchTo()->alert()->accept();
	}catch(Exception $e){}

	try{
		$this->webDriver->findElement(WebDriverBy::className('four-coloum'));
		//Validate column 1 fields
		$srcimg = substr(($this->webDriver->findElement(
		WebDriverBy::cssSelector('div.four-coloum-symbol:nth-child(1) img'))
		->getAttribute('src')), 0, -4);
		$this->assertContains("$srcimg","$srcfirstchild");
		$this->assertEquals("$title1",$this->webDriver->findElement(
		WebDriverBy::cssSelector('div.four-coloum-information:nth-child(1) h5'))->getText());
		$this->assertEquals("$description1",$this->webDriver->findElement(
		WebDriverBy::cssSelector('div.four-coloum-information:nth-child(1) p'))->getText());
		$this->assertEquals("$linktext1",$this->webDriver->findElement(
		WebDriverBy::cssSelector('div.four-coloum-information:nth-child(1) .four-coloum-action a'))->getText()); 
		$this->assertEquals("$linkurl1",$this->webDriver->findElement(
		WebDriverBy::cssSelector('div.four-coloum-information:nth-child(1) .four-coloum-action a'))->getAttribute('href'));
		//Validate column 2 fields
		$this->assertEquals("$title2",$this->webDriver->findElement(
		WebDriverBy::cssSelector('div.col-md-3.col-lg-3.col-xl-3:nth-child(2) .four-coloum-information h5'))->getText());
		$this->assertEquals("$description2",$this->webDriver->findElement(
		WebDriverBy::cssSelector('div.col-md-3.col-lg-3.col-xl-3:nth-child(2) .four-coloum-information p'))->getText());
		$this->assertEquals("$linktext2",$this->webDriver->findElement(
		WebDriverBy::cssSelector('div.col-md-3.col-lg-3.col-xl-3:nth-child(2) .four-coloum-information .four-coloum-action a'))->getText()); 
		$this->assertEquals("$linkurl2",$this->webDriver->findElement(
		WebDriverBy::cssSelector('div.col-md-3.col-lg-3.col-xl-3:nth-child(2) .four-coloum-information .four-coloum-action a'))->getAttribute('href'));	
		//Validate column 3 fields
		$this->assertEquals("$title3",$this->webDriver->findElement(
		WebDriverBy::cssSelector('div.col-md-3.col-lg-3.col-xl-3:nth-child(3) .four-coloum-information h5'))->getText());
		$this->assertEquals("$description3",$this->webDriver->findElement(
		WebDriverBy::cssSelector('div.col-md-3.col-lg-3.col-xl-3:nth-child(3) .four-coloum-information p'))->getText());
		$this->assertEquals("$linktext3",$this->webDriver->findElement(
		WebDriverBy::cssSelector('div.col-md-3.col-lg-3.col-xl-3:nth-child(3) .four-coloum-information a'))->getText()); 
		$this->assertEquals("$linkurl3",$this->webDriver->findElement(
		WebDriverBy::cssSelector('div.col-md-3.col-lg-3.col-xl-3:nth-child(3) .four-coloum-information a'))->getAttribute('href'));	
		//Validate column 4 fields
		$this->assertEquals("$title4",$this->webDriver->findElement(
		WebDriverBy::cssSelector('div.col-md-3.col-lg-3.col-xl-3:nth-child(4) .four-coloum-information h5'))->getText());
		$this->assertEquals("$description4",$this->webDriver->findElement(
		WebDriverBy::cssSelector('div.col-md-3.col-lg-3.col-xl-3:nth-child(4) .four-coloum-information p'))->getText());
		$this->assertEquals("$linktext4",$this->webDriver->findElement(
		WebDriverBy::cssSelector('div.col-md-3.col-lg-3.col-xl-3:nth-child(4) .four-coloum-information a'))->getText()); 
		$this->assertEquals("$linkurl4",$this->webDriver->findElement(
		WebDriverBy::cssSelector('div.col-md-3.col-lg-3.col-xl-3:nth-child(4) .four-coloum-information a'))->getAttribute('href'));	
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
