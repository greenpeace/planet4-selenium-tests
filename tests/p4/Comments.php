<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../wp-core/login.php';

class P4_Comments extends P4_login {

  /**
   * @var \RemoteWebDriver
   */


  public function testComments()
  {

   	//I log in
	try{
   		$this->wpLogin();
	}catch(Exception $e){
		$this->fail('->Failed to log in, verify credentials and URL');
	}
	$cmtid = rand(1, 100);
	$comment = "This is a demo comment written and posted by an automated test - $cmtid";
	//Go to homepage
   	$this->webDriver->wait(3);
	$pages = $this->webDriver->findElement(
		WebDriverBy::id("wp-admin-bar-site-name"));
	$pages->click();
	//Validate user lands in homepage
	$this->webDriver->findElement(
		WebDriverBy::cssSelector('.home.page-template-default.page'));
	//Scroll down to news section
	$element = $this->webDriver->findElement(
		WebDriverBy::cssSelector('.article-listing.page-section'));
	$element->getLocationOnScreenOnceScrolledIntoView(); 
	//Look for post
	$post = $this->webDriver->findElement(
		WebDriverBy::cssSelector('.article-listing.page-section .article-list-section .article-list-item-body .article-list-item-headline a'));
	$post->click();
	
	//Verify user lands in post
	$this->webDriver->findElement(
		WebDriverBy::cssSelector('.post-template-default.single.single-post'));
	$this->webDriver->findElement(
		WebDriverBy::cssSelector('.comments-block'));
	$this->webDriver->findElement(
		WebDriverBy::id('commentform'));

	//If usabilia window pops up, close it
	try{
		$usabilia = $this->webDriver->findElement(WebDriverBy::xpath('/html/body/div[10]/div/iframe'));
		$this->webDriver->switchTo()->frame($usabilia);
		$this->webDriver->findElement(
			WebDriverBy::id('close'))->click();
		$this->webDriver->switchTo()->activeElement();
	}catch(Exception $e){}

	//Write comment
	$this->webDriver->findElement(
		WebDriverBy::cssSelector('#commentform .form-group #comment'))->click();
	$this->webDriver->getKeyboard()->sendKeys("$comment");
	//Scroll down
	$ttl = $this->webDriver->findElement(
		WebDriverBy::className('comments-section-title'));
	$ttl->getLocationOnScreenOnceScrolledIntoView(); 
	//Post comment
	$this->webDriver->findElement(
		WebDriverBy::cssSelector('.form-submit .btn.btn-small.btn-secondary'))->click();
	//Validate posted comment
	try{
		$cmt = $this->webDriver->findElement(
			WebDriverBy::cssSelector('.comments-block .comments-section .single-comment:last-child p:nth-child(2)'))->getText();	
	}catch(Exception $e){
		$this->fail('->Not able to see posted comment');
	}
	
	$this->assertEquals("$comment","$cmt");

	// I log out after test
    $this->wpLogout();
    echo "\n-> Comments test PASSED";
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
