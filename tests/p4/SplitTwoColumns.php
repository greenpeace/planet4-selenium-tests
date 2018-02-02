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
		WebDriverBy::cssSelector("li[data-shortcode='shortcake_split_two_columns']"));
		$ta -> click();
		usleep(2000000);
	}catch(Exception $e){
		$this->fail("->Failed to select 'Split Two Column' post element");
	}

	//Validate elements are present
	try{
		$this->webDriver->findElement(WebDriverBy::name("select_issue"));
		$this->webDriver->findElement(WebDriverBy::name("title"));
		$this->webDriver->findElement(WebDriverBy::name("issue_description"));
		$this->webDriver->findElement(WebDriverBy::name("button_text"));
		$this->webDriver->findElement(WebDriverBy::name("button_link"));
		$this->webDriver->findElement(WebDriverBy::id("issue_image"));
		$this->webDriver->findElement(WebDriverBy::name("focus_issue_image"));
		$this->webDriver->findElement(WebDriverBy::name("select_tag"));
		$this->webDriver->findElement(WebDriverBy::name("tag_description"));
	}catch(Exception $e){
		$this->fail("->Fields corresponding to 'Split Two Columns' block not found");
	}

	//Fill in fields
	$titl = "Test title";
	$desc = 'This is test content generated by an automatic test';
	$field = $this->webDriver->findElement(
	WebDriverBy::name("select_issue")
	);
	$field->click();
	$issue = strtolower($this->webDriver->findElement(WebDriverBy::xPath("//*[@name='select_issue']/option[1]"))->getText());
	$this->webDriver->findElement(WebDriverBy::xPath("//*[@name='select_issue']/option[1]"))->click();
	$field = $this->webDriver->findElement(
		WebDriverBy::name("title"));
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys("$titl");
	$field = $this->webDriver->findElement(
		WebDriverBy::name('issue_description'));
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys("$desc");
	$field = $this->webDriver->findElement(
		WebDriverBy::name('select_tag'));
	$field->click();
	$tg = $this->webDriver->findElement(WebDriverBy::cssSelector(".shortcode-ui-attribute-select_tag option[value='19']"))->getText();
	$this->webDriver->findElement(WebDriverBy::cssSelector(".shortcode-ui-attribute-select_tag option[value='19']"))->click();
	
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
		WebDriverBy::linkText('View page')
	);	
	$link->click();

	//If alert shows up asking to confirm leaving the page, confirm
	try{
		$this->webDriver->switchTo()->alert()->accept();
	}catch(Exception $e){}
	try{
		$this->webDriver->findElement(WebDriverBy::className('split-two-column'));
		$issue_pg = strtolower($this->webDriver->findElement(
			WebDriverBy::cssSelector('.split-two-column-item.item--left .split-two-column-item-content a'))->getAttribute('href'));
		$titl_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('.split-two-column-item.item--left h2.split-two-column-item-title'))->getText();
		$desc_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('.split-two-column-item.item--left p.split-two-column-item-subtitle'))->getText();
		$subtg = explode("#",$this->webDriver->findElement(
			WebDriverBy::cssSelector('.split-two-column-item.item--right a.split-two-column-item-tag'))->getText());		
		$btn_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('.split-two-column-item.item--right a.split-two-column-item-button'))->getText();
	}catch(Exception $e){
		$this->fail('->Some of the content created is not displayed in front end page');
	}
	$this->assertContains("$issue","$issue_pg");
	$this->assertEquals("$titl","$titl_pg");
	$this->assertEquals("$tg","$subtg[1]");
	$this->assertEquals("$desc","$desc_pg");
	$this->assertEquals("GET INVOLVED","$btn_pg");
	
	// I log out after test
	$this->wpLogout();

	echo "\n-> Split two column block test PASSED";
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
