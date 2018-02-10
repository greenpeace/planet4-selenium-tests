<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../wp-core/login.php';


class P4_CampaignThumbnail extends P4_login {

  /**
   * @var \RemoteWebDriver
   */


  public function testCampaignThumbnail()
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


	//Enter needed page fields
	$tg = 'Oceans';
	$field	= $this->webDriver->findElement(
		WebDriverBy::id('title-prompt-text'));
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys('Test automated - Campaign Thumbnail');
	$this->webDriver->findElement(WebDriverBy::name("newtag[post_tag]"))->click();
	$this->webDriver->getKeyboard()->sendKeys("$tg");
	//Sleep for 3 seconds for suggestion to appear
	usleep(3000000); 
	//Select suggestion
	$this->webDriver->getKeyboard()->pressKey(WebDriverKeys::ENTER);
	$this->webDriver->findElement(WebDriverBy::className('tagadd'))->click();

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

	//Select Campaign Thumbnail block
	try{
	$ta = $this->webDriver->findElement(
		WebDriverBy::cssSelector("li[data-shortcode='shortcake_campaign_thumbnail']")
	);
	$ta -> click();
	}catch(Exception $e){
		$this->fail("->Failed to select 'Campaign Thumbnail' post element");
	}

	//Validate corresponding fields are present
	try{
		$this->webDriver->findElement(WebDriverBy::name("title"));
	}catch(Exception $e){
		$this->fail("->Fields corresponding to 'Campaign Thumbnail' block not found");
	}

	//Fill in field
	$titl = 'Test title';
	$this->webDriver->findElement(WebDriverBy::name('title'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$titl");

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
	usleep(2000000);
	//Go to page to validate page contains added block
	$link = $this->webDriver->findElement(
		WebDriverBy::linkText('View page'));	
	$link->click();
	//If alert shows up asking to confirm leaving the page, confirm
	try{
		$this->webDriver->switchTo()->alert()->accept();
	}catch(Exception $e){}
	//Validate elements corresponding to block are present
	try{
		$this->webDriver->findElement(WebDriverBy::className('campaign-thumbnail-block'));
		$this->webDriver->findElement(WebDriverBy::className('thumbnail-largeview-container'));
		$subtg = substr(($this->webDriver->findElement(
			WebDriverBy::cssSelector('.thumbnail-largeview-container a'))->getText()), 1, strlen("$tg"));
	}catch(Exception $e){
		$this->fail('->Failed to see some content in front end page');
	}
	$this->assertEquals(
		"$titl",$this->webDriver->findElement(
		WebDriverBy::cssSelector('.campaign-thumbnail-block h2'))->getText());
	$this->assertEquals("$tg", "$subtg");

	// I log out after test
    $this->wpLogout();
    echo "\n-> Campaign thumbnail block test PASSED";
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
