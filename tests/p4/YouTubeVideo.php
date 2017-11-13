<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../wp-core/login.php';

class P4_YouTubeVideo extends P4_login {

  /**
   * @var \RemoteWebDriver
   */


  public function testYouTubeVideo()
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
	$this->webDriver->getKeyboard()->sendKeys('YouTube Video test - automated');

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

	//Select YouTube video block
	try{
	$ta = $this->webDriver->findElement(
		WebDriverBy::cssSelector("li[data-shortcode='shortcake_media_video']")
	);
	$ta -> click();
	}catch(Exception $e){
		$this->fail("->Failed to select 'YouTube video' block post element");
	}

	//Validate corresponding fields are present
	try{
		$this->webDriver->findElement(WebDriverBy::name("video_title"));
		$this->webDriver->findElement(WebDriverBy::name("youtube_id"));
	}catch(Exception $e){
		$this->fail("->Fields corresponding to 'YouTube video' not found");
	}

	//Fill in fields
	$this->webDriver->findElement(WebDriverBy::name('video_title'))->click();
	$this->webDriver->getKeyboard()->sendKeys('Test video');
	$this->webDriver->findElement(WebDriverBy::name('youtube_id'))->click();
	$this->webDriver->getKeyboard()->sendKeys('wNN-Yl-SBTM');

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
