<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../wp-core/login.php';

class P4_Split_TwoColumns extends P4_login {

  /**
   * @var \RemoteWebDriver
   */


  public function testSplitTwoColumns()
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
	$this->webDriver->getKeyboard()->sendKeys('Test automated - Split Two Column');

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

	//Select Split Two Columns block
	try{
		$ta = $this->webDriver->findElement(
		WebDriverBy::cssSelector("li[data-shortcode='shortcake_split_two_columns']")
		);
		$ta -> click();
	}catch(Exception $e){
		$this->fail("->Failed to select 'Split Two Column' post element");
	}

	//Validate elements are present
	try{
		$this->webDriver->findElement(WebDriverBy::name("select_issue"));
		$this->webDriver->findElement(WebDriverBy::className("select2-selection__rendered"));
		$this->webDriver->findElement(WebDriverBy::name("description"));
		$this->webDriver->findElement(WebDriverBy::name("button_text"));
		$this->webDriver->findElement(WebDriverBy::name("button_link"));
	}catch(Exception $e){
		$this->fail("->Fields corresponding to 'Split Two Columns' block not found");
	}

	//Fill in fields
	$desc = 'This is test content generated by an automatic test';
	$tag = 'ArcticSunrise';
	$field = $this->webDriver->findElement(
	WebDriverBy::name("select_issue")
	);
	$field->click();
	$issue = $this->webDriver->findElement(WebDriverBy::xPath("//*[@name='select_issue']/option[5]"))->getText();
	$this->webDriver->findElement(WebDriverBy::xPath("//*[@name='select_issue']/option[5]"))->click();
	$field = $this->webDriver->findElement(
	WebDriverBy::className("select2-selection__rendered")
	);
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys("$tag");
	/** Currently not working
	//Wait for suggestion to appear
	$this->webDriver->wait(20, 1000)->until(
		WebDriverExpectedCondition::visibilityOfElementLocated(
		WebDriverBy::xPath("//*[@name='select_tag']/option")));
	//$this->webDriver->manage()->timeouts()->implicitlyWait(5000);
	//Click on suggestion
	$this->webDriver->findElement(WebDriverBy::name("select_tag"))->click();
	**/
	$field = $this->webDriver->findElement(
	WebDriverBy::name('description')
	);
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys("$desc");

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
	$this->webDriver->manage()->timeouts()->implicitlyWait(10000);
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
		$this->webDriver->findElement(WebDriverBy::id('split_two_column'));
		$this->webDriver->findElement(WebDriverBy::className('split-two-column-skewed-left'));
		$this->webDriver->findElement(WebDriverBy::className('split-two-column-skewed-right'));
		$this->assertEquals("$issue",$this->webDriver->findElement(
			WebDriverBy::cssSelector('.split-two-column-skewed-left .part-left h2'))->getText());
		$this->assertEquals("$desc",$this->webDriver->findElement(
			WebDriverBy::cssSelector('.split-two-column-skewed-right .part-right h5'))->getText());
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
