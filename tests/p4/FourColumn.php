<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../wp-core/login.php';

class P4_FourColumn extends P4_login {

  /**
   * @var \RemoteWebDriver
   */


  public function testFourColumn()
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
	$this->webDriver->getKeyboard()->sendKeys('Test automated - Content 4 Column');

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

	//Select Content 4 column block
	try{
	$ta = $this->webDriver->findElement(
		WebDriverBy::cssSelector("li[data-shortcode='shortcake_content_four_column']")
	);
	$ta -> click();
	}catch(Exception $e){
		$this->fail("->Failed to select 'Content Four Column' post element");
	}

	//Validate corresponding fields are present
	try{
		$this->webDriver->findElement(WebDriverBy::name("title"));
		$this->webDriver->findElement(WebDriverBy::name("p4_post_types"));
		$this->webDriver->findElement(WebDriverBy::name("select_tag"));
	}catch(Exception $e){
		$this->fail("->Fields corresponding to 'Content Four Column' block not found");
	}

	//----- FILL IN FIELDS
	//Define test content
	$titl = "Content 4 column Test title";
	$posttype = "Publication";
	$tg = "ArcticSunrise";
	//Fill in fields
	$this->webDriver->findElement(WebDriverBy::name('title'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$titl");
	//Fill in post type
	$this->webDriver->findElement(WebDriverBy::className('select2-container'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$posttype");
	//Sleep for 3 seconds
	usleep(3000000); 
	//Select suggestion
	$this->webDriver->getKeyboard()->pressKey(WebDriverKeys::ENTER);
	//Fill in tag
	$this->webDriver->findElement(WebDriverBy::xPath("//*[@id='__wp-uploader-id-0']/div[4]/div/div/form/div/div[3]/div/span/span[1]/span/ul/li"))->click();
	$this->webDriver->getKeyboard()->sendKeys("$tg");
	//Sleep for 3 seconds
	usleep(3000000); 
	//Select suggestion
	$this->webDriver->getKeyboard()->pressKey(WebDriverKeys::ENTER);
	
	//Insert block
	try{
		$insert = $this->webDriver->findElement(
			WebDriverBy::className('media-button-insert')
		);
		$insert -> click();
	}catch(Exception $e){
		$this->fail('->Failed to insert element');
	}

	$this->webDriver->manage()->timeouts()->implicitlyWait(5);

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
	//Wait 3 secs for saved changes to load
	usleep(3000000);
	//Go to page to validate page contains added block
	$link = $this->webDriver->findElement(
		WebDriverBy::linkText('View page')
	);	
	$link->click();	
	//If alert shows up asking to confirm leaving the page, confirm
	try{
		$this->webDriver->switchTo()->alert()->accept();
	}catch(Exception $e){}

	/**
	try{
		$this->webDriver->findElement(WebDriverBy::className('four-coloum'));
		$this->assertEquals("$title1",$this->webDriver->findElement(
		WebDriverBy::cssSelector('div.four-coloum-information:nth-child(1) h5'))->getText());
		$this->assertEquals("$description1",$this->webDriver->findElement(
		WebDriverBy::cssSelector('div.four-coloum-information:nth-child(1) p'))->getText());
	}catch(Exception $e){
		$this->fail('->Some of the content created is not displayed in front end page');
	}
	**/

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
