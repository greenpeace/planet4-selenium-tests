<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../wp-core/login.php';

class P4_TwoColumn_Content extends P4_login {

  /**
   * @var \RemoteWebDriver
   */


  public function testTwoColumnContent()
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
	$this->webDriver->getKeyboard()->sendKeys('Test automated - Two Column Content');

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

	//Select Two Columns block
	try{
		$ta = $this->webDriver->findElement(
		WebDriverBy::cssSelector("li[data-shortcode='shortcake_two_columns']")
		);
		$ta -> click();
	}catch(Exception $e){
		$this->fail("->Failed to select 'Two Column Content' post element");
	}

	//Validate elements are present
	try{
		$this->webDriver->findElement(WebDriverBy::name("title_1"));
		$this->webDriver->findElement(WebDriverBy::name("description_1"));
		$this->webDriver->findElement(WebDriverBy::name("button_text_1"));
		$this->webDriver->findElement(WebDriverBy::name("button_link_1"));
		$this->webDriver->findElement(WebDriverBy::name("title_2"));
		$this->webDriver->findElement(WebDriverBy::name("description_2"));
		$this->webDriver->findElement(WebDriverBy::name("button_text_2"));
		$this->webDriver->findElement(WebDriverBy::name("button_link_2"));
	}catch(Exception $e){
		$this->fail("->Fields corresponding to 'Two Column Content' block not found");
	}


	//Enter Block Title and Description
	$field = $this->webDriver->findElement(
	WebDriverBy::name('title_1')
	);
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys('Column 1 Block Test');
	
	$field = $this->webDriver->findElement(
	WebDriverBy::name('description_1')
	);
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys('This is content created by an automated test for testing content in column 1 block');


	$field = $this->webDriver->findElement(
	WebDriverBy::name('button_text_1')
	);
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys('See on youtube');

	$field = $this->webDriver->findElement(
	WebDriverBy::name('button_link_1')
	);
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys('www.youtube.com');

    $field = $this->webDriver->findElement(
	WebDriverBy::name('title_2')
    );
    $field->click();
    $this->webDriver->getKeyboard()->sendKeys('Column 2 Block Test');

    $field = $this->webDriver->findElement(
	WebDriverBy::name('description_2')
    );
    $field->click();
    $this->webDriver->getKeyboard()->sendKeys('This is content created by an automated test for testing content in column 2 Block');

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

	//Go to page to validate page contains Take Action Block
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
