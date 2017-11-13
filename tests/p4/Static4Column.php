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

	//-----Fill in fields for column 1
	$this->webDriver->findElement(WebDriverBy::id('attachment_1'))->click();
	$this->webDriver->findElement(WebDriverBy::linkText('Media Library'))->click();
	//Wait for media library to load
	$this->webDriver->wait(10, 1000)->until(
		WebDriverExpectedCondition::presenceOfElementLocated(
		WebDriverBy::cssSelector('ul.attachments')));
	$this->webDriver->manage()->timeouts()->implicitlyWait(10);
	//Select first image of media library
	$this->webDriver->findElement(WebDriverBy::cssSelector("li.attachment:first-child"))->click();
	$this->webDriver->findElement(WebDriverBy::className("media-button-select"))->click();
	//Fill in rest of fields
	$this->webDriver->findElement(WebDriverBy::name('title_1'))->click();
	$this->webDriver->getKeyboard()->sendKeys('Column 1 Test');
	$this->webDriver->findElement(WebDriverBy::name('description_1'))->click();
	$this->webDriver->getKeyboard()->sendKeys('This is test content created by an automated test for testing content in column 1 of static 4 column block');
	$this->webDriver->findElement(WebDriverBy::name('link_text_1'))->click();
	$this->webDriver->getKeyboard()->sendKeys('Detox Germany');
	$this->webDriver->findElement(WebDriverBy::name('link_url_1'))->click();
	$this->webDriver->getKeyboard()->sendKeys('http://www.detox-outdoor.org/de-CH/');
	

	//-----Fill in fields for column 2
	
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
	$this->webDriver->getKeyboard()->sendKeys('Column 2 Test');
	$this->webDriver->findElement(WebDriverBy::name('description_2'))->click();
	$this->webDriver->getKeyboard()->sendKeys('This is test content created by an automated test for testing content in column 2 of static 4 column block');
	$this->webDriver->findElement(WebDriverBy::name('link_text_2'))->click();
	$this->webDriver->getKeyboard()->sendKeys('Detox Italy');
	$this->webDriver->findElement(WebDriverBy::name('link_url_2'))->click();
	$this->webDriver->getKeyboard()->sendKeys('http://www.detox-outdoor.org/it-IT');

	//-----Fill in fields for column 3

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
	$this->webDriver->getKeyboard()->sendKeys('Column 3 Test');
	$this->webDriver->findElement(WebDriverBy::name('description_3'))->click();
	$this->webDriver->getKeyboard()->sendKeys('This is test content created by an automated test for testing content in column 3 of static 4 column block');
	$this->webDriver->findElement(WebDriverBy::name('link_text_3'))->click();
	$this->webDriver->getKeyboard()->sendKeys('Detox France');
	$this->webDriver->findElement(WebDriverBy::name('link_url_3'))->click();
	$this->webDriver->getKeyboard()->sendKeys('http://www.detox-outdoor.org/fr-CH');

	//-----Fill in fields for column 4
	
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
	$this->webDriver->getKeyboard()->sendKeys('Column 4 Test');
	$this->webDriver->findElement(WebDriverBy::name('description_4'))->click();
	$this->webDriver->getKeyboard()->sendKeys('This is test content created by an automated test for testing content in column 4 of static 4 column block');
	$this->webDriver->findElement(WebDriverBy::name('link_text_4'))->click();
	$this->webDriver->getKeyboard()->sendKeys('Detox Finland');
	$this->webDriver->findElement(WebDriverBy::name('link_url_4'))->click();
	$this->webDriver->getKeyboard()->sendKeys('http://www.detox-outdoor.org/fi');


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
	
	try{
		//Validate I see successful message
		$this->assertContains(
			'Page published',$this->webDriver->findElement(
			WebDriverBy::id('message'))->getText()
		);
	}catch(Exception $e){
		$this->fail('->Failed to publish content - no sucessful message after saving content');
	}

	//Go to page to validate page contains added block
	$link = $this->webDriver->findElement(
		WebDriverBy::linkText('View page')
	);	
	$link->click();	

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
