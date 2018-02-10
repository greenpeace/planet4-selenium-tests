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
	'Add Page Element',$this->webDriver->findElement(
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


	//-- Fill in fields column 1
	$titl1 = 'Column 1 Block Test';
	$desc1 = 'This is content created by an automated test for testing content in column 1 block';
	$btext1 = 'See on youtube';
	$blink1 = 'www.youtube.com';
	$field = $this->webDriver->findElement(
	WebDriverBy::name('title_1')
	);
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys("$titl1");
	
	$field = $this->webDriver->findElement(
	WebDriverBy::name('description_1')
	);
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys("$desc1");
	$field = $this->webDriver->findElement(
	WebDriverBy::name('button_text_1')
	);
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys("$btext1");
	$field = $this->webDriver->findElement(
	WebDriverBy::name('button_link_1')
	);
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys("$blink1");
	
	//-- Fill in fields column 2
	$titl2 = 'Column 2 Block Test';
	$desc2 = 'This is content created by an automated test for testing content in column 2 Block';
	$btext2 = 'See on planet';
	$blink2 = 'www.greenpeace.org';
    $field = $this->webDriver->findElement(
	WebDriverBy::name('title_2')
    );
    $field->click();
    $this->webDriver->getKeyboard()->sendKeys("$titl2");

    $field = $this->webDriver->findElement(
	WebDriverBy::name('description_2')
    );
    $field->click();
    $this->webDriver->getKeyboard()->sendKeys("$desc2");
    $field = $this->webDriver->findElement(
	WebDriverBy::name('button_text_2')
	);
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys("$btext2");
	$field = $this->webDriver->findElement(
	WebDriverBy::name('button_link_2')
	);
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys("$blink2");

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
	//Go to page to validate page contains Take Action Block
	$link = $this->webDriver->findElement(
	WebDriverBy::linkText('View page')
	);	
	$link->click();
	//If alert shows up asking to confirm leaving the page, confirm
	try{
		$this->webDriver->switchTo()->alert()->accept();
	}catch(Exception $e){}
	try{
		$this->webDriver->findElement(WebDriverBy::className('content-two-column-block'));
		$titl1_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('.col-md-12.col-lg-5.col-sm-12.col-xl-5:nth-child(1) .heading h2'))->getText();
		$desc1_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('.col-md-12.col-lg-5.col-sm-12.col-xl-5:nth-child(1) p'))->getText();
		$btext1_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('.col-md-12.col-lg-5.col-sm-12.col-xl-5:nth-child(1) a.btn'))->getText();
		$blink1_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('.col-md-12.col-lg-5.col-sm-12.col-xl-5:nth-child(1) a.btn'))->getAttribute('href');
		$titl2_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('.col-md-12.col-lg-5.col-sm-12.col-xl-5:nth-child(2) .heading h2'))->getText();
		$desc2_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('.col-md-12.col-lg-5.col-sm-12.col-xl-5:nth-child(2) p'))->getText();
		$btext2_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('.col-md-12.col-lg-5.col-sm-12.col-xl-5:nth-child(2) a.btn'))->getText();
		$blink2_pg = $this->webDriver->findElement(
			WebDriverBy::cssSelector('.col-md-12.col-lg-5.col-sm-12.col-xl-5:nth-child(2) a.btn'))->getAttribute('href');
	}catch(Exception $e){
		$this->fail('->Some of the content created is not displayed in front end page');
	}
	$this->assertEquals("$titl1","$titl1_pg");
	$this->assertEquals("$desc1","$desc1_pg");
	$this->assertEquals(strtoupper($btext1),"$btext1_pg");
	$this->assertContains("$blink1","$blink1_pg");
	$this->assertEquals("$titl2","$titl2_pg");
	$this->assertEquals("$desc2","$desc2_pg");
	$this->assertEquals(strtoupper($btext2),"$btext2_pg");
	$this->assertContains("$blink2","$blink2_pg");
	// I log out after test
	$this->wpLogout();
	echo "\n-> Two column block test PASSED";
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
