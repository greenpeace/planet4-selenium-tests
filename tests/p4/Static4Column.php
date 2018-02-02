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
	$srcfirstchild = explode("-",$this->webDriver->findElement(
		WebDriverBy::cssSelector("li.attachment:first-child img"))->getAttribute('src'));
	$srcfirstchild = $srcfirstchild[1];
	$this->webDriver->findElement(WebDriverBy::cssSelector("li.attachment:first-child"))->click();
	//Get info needed to upload image 2, 3 and 4
	$img2 = $this->webDriver->findElement(
		WebDriverBy::cssSelector("li.attachment:nth-child(2)"))->getAttribute('data-id');
	$src2child = explode("-",$this->webDriver->findElement(
		WebDriverBy::cssSelector("li.attachment:nth-child(2) img"))->getAttribute('src'));
	$src2child = $src2child[1];
	$img3 = $this->webDriver->findElement(
		WebDriverBy::cssSelector("li.attachment:nth-child(3)"))->getAttribute('data-id');
	$src3child = explode("-",$this->webDriver->findElement(
		WebDriverBy::cssSelector("li.attachment:nth-child(3) img"))->getAttribute('src'));
	$src3child = $src3child[1];
	$img4 = $this->webDriver->findElement(
		WebDriverBy::cssSelector("li.attachment:nth-child(4)"))->getAttribute('data-id');
	$src4child = explode("-",$this->webDriver->findElement(
		WebDriverBy::cssSelector("li.attachment:nth-child(4) img"))->getAttribute('src'));
	$src4child = $src4child[1];
	//Add image
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

	//Edit WYSIWYG text to add image 2 and 3
	$this->webDriver->findElement(WebDriverBy::id("content-html"))->click();
	$this->webDriver->findElement(WebDriverBy::id("content"))->click();
	$this->webDriver->getKeyboard()->pressKey(WebDriverKeys::ARROW_RIGHT);
	$this->webDriver->getKeyboard()->pressKey(WebDriverKeys::BACKSPACE);
	$this->webDriver->getKeyboard()->pressKey(WebDriverKeys::BACKSPACE);
	$this->webDriver->getKeyboard()->sendKeys("attachment_2=$img2 attachment_3=$img3 attachment_4=$img4/]");

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

	//try{
		$this->webDriver->findElement(WebDriverBy::className('four-column'));
		//Get info of posted images
		$srcimg1 = explode("-",$this->webDriver->findElement(
			WebDriverBy::cssSelector('div.four-column-wrap:nth-child(1) img'))->getAttribute('src'));
		$srcimg1 = $srcimg1[1];
		$title1_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('div.four-column-wrap:nth-child(1) .four-column-information h5'))->getText();
		$description1_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('div.four-column-wrap:nth-child(1) .four-column-information p'))->getText();
		$linktext1_pg = $this->webDriver->findElement(
			WebDriverBy::xPath("/html/body/div[5]/section/div/div/div[1]/div[2]/a"))->getText();
		$linkurl1_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('div.four-column-wrap:nth-child(1) .four-column-information a'))->getAttribute('href');
		$srcimg2 = explode("-",$this->webDriver->findElement(
			WebDriverBy::cssSelector('div.four-column-wrap:nth-child(2) img'))->getAttribute('src'));
		$srcimg2 = $srcimg2[1];
		$title2_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('div.four-column-wrap:nth-child(2) .four-column-information h5'))->getText();
		$description2_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('div.four-column-wrap:nth-child(2) .four-column-information p'))->getText();
		$linktext2_pg = $this->webDriver->findElement(
			WebDriverBy::xPath("/html/body/div[5]/section/div/div/div[2]/div[2]/a"))->getText();
		$linkurl2_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('div.four-column-wrap:nth-child(2) .four-column-information a'))->getAttribute('href');
		$srcimg3 = explode("-",$this->webDriver->findElement(
			WebDriverBy::cssSelector('div.four-column-wrap:nth-child(3) img'))->getAttribute('src'));
		$srcimg3 = $srcimg3[1];
		$title3_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('div.four-column-wrap:nth-child(3) .four-column-information h5'))->getText();
		$description3_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('div.four-column-wrap:nth-child(3) .four-column-information p'))->getText();
		$linktext3_pg = $this->webDriver->findElement(
			WebDriverBy::xPath("/html/body/div[5]/section/div/div/div[3]/div[2]/a"))->getText();
		$linkurl3_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('div.four-column-wrap:nth-child(3) .four-column-information a'))->getAttribute('href');
		$srcimg4 = explode("-",$this->webDriver->findElement(
			WebDriverBy::cssSelector('div.four-column-wrap:nth-child(4) img'))->getAttribute('src'));
		$srcimg4 = $srcimg4[1];
		$title4_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('div.four-column-wrap:nth-child(4) .four-column-information h5'))->getText();
		$description4_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('div.four-column-wrap:nth-child(4) .four-column-information p'))->getText();
		$linktext4_pg = $this->webDriver->findElement(
			WebDriverBy::xPath("/html/body/div[5]/section/div/div/div[4]/div[2]/a"))->getText();
		$linkurl4_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('div.four-column-wrap:nth-child(4) .four-column-information a'))->getAttribute('href');
	/**}catch(Exception $e){
		$this->fail('->Some of the content created is not displayed in front end page');
	}**/
	//Validate column 1 fields
	$this->assertContains("$srcimg1","$srcfirstchild");
	$this->assertEquals("$title1","$title1_pg");
	$this->assertEquals("$description1","$description1_pg");
	$this->assertEquals("$linktext1","$linktext1_pg"); 
	$this->assertEquals("$linkurl1","$linkurl1_pg");
	//Validate column 2 fields
	$this->assertContains("$srcimg2","$src2child");
	$this->assertEquals("$title2","$title2_pg");
	$this->assertEquals("$description2","$description2_pg");
	$this->assertEquals("$linktext2","$linktext2_pg"); 
	$this->assertEquals("$linkurl2","$linkurl2_pg");	
	//Validate column 3 fields
	$this->assertContains("$srcimg3","$src3child");
	$this->assertEquals("$title3","$title3_pg");
	$this->assertEquals("$description3","$description3_pg");
	$this->assertEquals("$linktext3","$linktext3_pg"); 
	$this->assertEquals("$linkurl3","$linkurl3_pg");	
	//Validate column 4 fields
	$this->assertContains("$srcimg4","$src4child");
	$this->assertEquals("$title4","$title4_pg");
	$this->assertEquals("$description4","$description4_pg");
	$this->assertEquals("$linktext4","$linktext4_pg"); 
	$this->assertEquals("$linkurl4","$linkurl4_pg");	
	// I log out after test
    $this->wpLogout();

    echo "\n-> Static four column block test PASSED";
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
