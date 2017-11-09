<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../wp-core/login.php';

class P4_Tasks extends P4_login {

  /**
   * @var \RemoteWebDriver
   */


  public function testTasks()
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
	$this->webDriver->getKeyboard()->sendKeys('Take Action tasks test - automated');

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
	WebDriverBy::cssSelector("li[data-shortcode='shortcake_tasks']")
	);
	$ta -> click();
	}catch(Exception $e){
		$this->fail("->Failed to select 'Take Action Tasks' post element");
	}

	//Validate tasks fields are visible
	try{
		//Fields task 1
		$this->webDriver->findElement(WebDriverBy::name("title_1"));
		$this->webDriver->findElement(WebDriverBy::name("description_1"));
		$this->webDriver->findElement(WebDriverBy::id("attachment_1"));
		$this->webDriver->findElement(WebDriverBy::name("button_text_1"));
		$this->webDriver->findElement(WebDriverBy::name("button_link_1"));
		// Fields task 2
		$this->webDriver->findElement(WebDriverBy::name("title_2"));
	    $this->webDriver->findElement(WebDriverBy::name("description_2"));
	    $this->webDriver->findElement(WebDriverBy::id("attachment_2"));
	    $this->webDriver->findElement(WebDriverBy::name("button_text_2"));
	    $this->webDriver->findElement(WebDriverBy::name("button_link_2"));
		//Fields task 3
		$this->webDriver->findElement(WebDriverBy::name("title_3"));
	    $this->webDriver->findElement(WebDriverBy::name("description_3"));
	    $this->webDriver->findElement(WebDriverBy::id("attachment_3"));
	    $this->webDriver->findElement(WebDriverBy::name("button_text_3"));
	    $this->webDriver->findElement(WebDriverBy::name("button_link_3"));
		//Fields 4
		$this->webDriver->findElement(WebDriverBy::name("title_4"));
	    $this->webDriver->findElement(WebDriverBy::name("description_4"));
	    $this->webDriver->findElement(WebDriverBy::id("attachment_4"));
	    $this->webDriver->findElement(WebDriverBy::name("button_text_4"));
	    $this->webDriver->findElement(WebDriverBy::name("button_link_4"));
	}catch(Exception $e){
		$this->fail("->Could not find tasks fields for 'Take Action Tasks' post element");
	}

	//Enter Block Title and Description
	$field = $this->webDriver->findElement(
	WebDriverBy::name('tasks_title')
	);
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys('Take Action Tasks Test');
	
	$field = $this->webDriver->findElement(
	WebDriverBy::name('tasks_description')
	);
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys('This is content created by an automated test for testing take action tasks block');

	//Add only 1 task
	$field = $this->webDriver->findElement(
	WebDriverBy::name('title_1')
	);
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys('Test Task 1');
	$field = $this->webDriver->findElement(
	WebDriverBy::name('description_1')
	);
	$field->click();
 	$this->webDriver->getKeyboard()->sendKeys('This is the content for task 1 which is generated by an automated test');
	$this->webDriver->findElement(WebDriverBy::id("attachment_1"))->click();
	$tab = $this->webDriver->findElement(WebDriverBy::linkText('Media Library'));
	$tab->click();
	
	//Wait for media library to load
	$this->webDriver->wait(10, 1000)->until(
  	WebDriverExpectedCondition::presenceOfElementLocated(
	WebDriverBy::cssSelector('ul.attachments'))
	);

	$this->webDriver->manage()->timeouts()->implicitlyWait(10);

	//Select first image
	$img = $this->webDriver->findElement(WebDriverBy::cssSelector("li.attachment:first-child"));
	$img->click();
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
