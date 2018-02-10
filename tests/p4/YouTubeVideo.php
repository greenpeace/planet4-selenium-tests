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
		'Add Page Element',$this->webDriver->findElement(
		WebDriverBy::className('shortcake-add-post-element'))->getText()
	);

	//Enter title of page
	$field	= $this->webDriver->findElement(
		WebDriverBy::id('title-prompt-text'));
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys('Test automated - YouTube Video');

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
	$titl = 'Test video';
	$ytid = 'wNN-Yl-SBTM';
	$this->webDriver->findElement(WebDriverBy::name('video_title'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$titl");
	$this->webDriver->findElement(WebDriverBy::name('youtube_id'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$ytid");

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
	usleep(3000000);
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
	usleep(3000000);
	//Go to page to validate page contains added block
	$link = $this->webDriver->findElement(
		WebDriverBy::linkText('View page'));	
	$link->click();	
	//If alert shows up asking to confirm leaving the page, confirm
	try{
		$this->webDriver->switchTo()->alert()->accept();
	}catch(Exception $e){}
	try{
		$this->webDriver->findElement(WebDriverBy::className('video-block'));
		$titl_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('.video-block h2'))->getText();
		$ytid_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('.video-block .video-section iframe'))->getAttribute('src');
	}catch(Exception $e){
		$this->fail('->Some of the content created is not displayed in front end page');
	}
	$this->assertEquals("$titl","$titl_pg");
	$this->assertContains("$ytid","$ytid_pg");

	// I log out after test
    $this->wpLogout();
    echo "\n-> YouTube video block test PASSED";
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
