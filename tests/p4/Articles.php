<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../wp-core/login.php';

class P4_Articles extends P4_login {

  /**
   * @var \RemoteWebDriver
   */


  public function testArticles()
  {

  	$page_title = 'Test automated - Articles';
  	//Verify if user is already logged in
  	$this->webDriver->getTitle();
   	//I log in

	//try{
   		$this->wpLogin();
	//}catch(Exception $e){
	//	$this->fail('->Failed to log in, verify credentials and URL');
	///}

	//Go to pages to create content  
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

	//Validate button to add Blocks to page is present
	$this->assertContains(
	'Add Post Element',$this->webDriver->findElement(
	WebDriverBy::className('shortcake-add-post-element'))->getText()
	);


	//Enter title
	$field	= $this->webDriver->findElement(
	WebDriverBy::id('title-prompt-text')
	);
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys($page_title);

	
	//Click on Add Post Element button to select block
	$add = $this->webDriver->findElement(
	WebDriverBy::className("shortcake-add-post-element")
	);
	$add->click();

	//Validate blocks modal window is opened
	$this->assertContains(
	'Insert Post Element',$this->webDriver->findElement(
	WebDriverBy::className('media-frame-title'))->getText()
	);

	//Select Articles block
	try{
	$ta = $this->webDriver->findElement(
	WebDriverBy::cssSelector("li[data-shortcode='shortcake_articles']")
	);
	$ta -> click();
	}catch(Exception $e){
		$this->fail("->Failed to select 'Articles' post element");
	}

	//Validate block elements are present
	try{
		$this->webDriver->findElement(WebDriverBy::name("article_heading"));
		$this->webDriver->findElement(WebDriverBy::name("article_count"));
	}catch(Exception $e){
		$this->fail('->Failed to see all related fields when adding block');
	}

	//Enter Block Title and Number of articles
	$block_title = 'Articles Block Test';
	$field = $this->webDriver->findElement(
	WebDriverBy::name('article_heading')
	);
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys($block_title);
	
	$field = $this->webDriver->findElement(
	WebDriverBy::name('article_count')
	);
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys('1');

	//Insert block
	try{
		$insert = $this->webDriver->findElement(
		WebDriverBy::className('media-button-insert')
		);
		$insert -> click();
	}catch(Exception $e){
		$this->fail('->Failed to insert element');
	}
	//Wait for content to load
	usleep(2000000);
	//Publish content
	$this->webDriver->findElement(
	WebDriverBy::id('publish')
	)->click();
	//Wait for content to load
	usleep(2000000);

	try{
		//Validate I see successful message
		$this->assertContains(
		'Page published',$this->webDriver->findElement(
		WebDriverBy::id('message'))->getText()
		);
	}catch(Exception $e){
		$this->fail('->Failed to publish content - no sucessful message after saving content');
        }

    //Wait for saved changes to load
	usleep(2000000);
	//Go to page to validate page contains Articles Block
	$link = $this->webDriver->findElement(
	WebDriverBy::linkText('View page')
	);	
	$link->click();
	//If alert shows up asking to confirm leaving the page, confirm
	try{
		$this->webDriver->switchTo()->alert()->accept();
	}catch(Exception $e){}

	//Validate elements are present
	try{
		$this->webDriver->findElement(WebDriverBy::className("article-listing"));
		$this->webDriver->findElement(WebDriverBy::className("article-list-section"));
		$block_title_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector(".article-listing h3.page-section-header"))->getText();
	}catch(Exception $e){
		$this->fail('->Failed to see some of the created content on front end page');
	}
	$this->assertContains("$block_title", "$block_title_pg");

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
