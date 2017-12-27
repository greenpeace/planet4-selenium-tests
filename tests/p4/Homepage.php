<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../AbstractClass.php';

class P4_Homepage extends AbstractClass {

  /**
   * @var \RemoteWebDriver
   */

  public function testHomepage()
  {
  	$this->webDriver->get($this->_url);
	//Validate 2-column block elements are present
	try{
		$this->webDriver->findElement(WebDriverBy::className('content-two-column-block'));
		$this->webDriver->findElement(WebDriverBy::cssSelector('.content-two-column-block .col-md-12.col-lg-5.col-sm-12.col-xl-5:nth-child(1)'));
		$this->webDriver->findElement(WebDriverBy::cssSelector('.content-two-column-block .col-md-12.col-lg-5.col-sm-12.col-xl-5:nth-child(2)'));
	}catch(Exception $e){
		$this->fail('->Failed to see some elements of 2-column block');
		
	}

	//Validate article block elements are present
	try{
		$this->webDriver->findElement(WebDriverBy::className('article-listing'));
	}catch(Exception $e){
		$this->fail('->Failed to see some elements of article block');
	}

	//Validate 4-column block elements are present
	try{
		$this->webDriver->findElement(WebDriverBy::className('four-column'));
		$this->webDriver->findElement(WebDriverBy::cssSelector('.four-column .four-column-wrap:nth-child(1)'));
		$this->webDriver->findElement(WebDriverBy::cssSelector('.four-column .four-column-wrap:nth-child(2)'));
		$this->webDriver->findElement(WebDriverBy::cssSelector('.four-column .four-column-wrap:nth-child(3)'));
		$this->webDriver->findElement(WebDriverBy::cssSelector('.four-column .four-column-wrap:nth-child(4)'));		
	}catch(Exception $e){
		$this->fail('->Failed to see some elements of 4-column block');
	}

	//Validate happy point block elements are present
	try{
		$this->webDriver->findElement(WebDriverBy::className('happy-point-block-wrap'));
		$this->webDriver->findElement(WebDriverBy::cssSelector('.happy-point-block-wrap .col-md-10.pt-20 iframe'));		
		
	}catch(Exception $e){
		$this->fail('->Failed to see some elements of happy point block');
	}

	//Validate footer block elements are present
	try{
		$this->webDriver->findElement(WebDriverBy::className('site-footer'));
		$fb = $this->webDriver->findElement(
			WebDriverBy::cssSelector('.site-footer .footer-social-media li:nth-child(1) a'))->getAttribute('href');
		$twt = $this->webDriver->findElement(
			WebDriverBy::cssSelector('.site-footer .footer-social-media li:nth-child(2) a'))->getAttribute('href');
		$yt = $this->webDriver->findElement(
			WebDriverBy::cssSelector('.site-footer .footer-social-media li:nth-child(3) a'))->getAttribute('href');
		$inst = $this->webDriver->findElement(
			WebDriverBy::cssSelector('.site-footer .footer-social-media li:nth-child(4) a'))->getAttribute('href');
		$news = $this->webDriver->findElement(
			WebDriverBy::cssSelector('.site-footer .footer-links li:nth-child(1) a'))->getAttribute('href');
		$about = $this->webDriver->findElement(
			WebDriverBy::cssSelector('.site-footer .footer-links li:nth-child(2) a'))->getAttribute('href');
		$jobs = $this->webDriver->findElement(
			WebDriverBy::cssSelector('.site-footer .footer-links li:nth-child(3) a'))->getAttribute('href');
		$press = $this->webDriver->findElement(
			WebDriverBy::cssSelector('.site-footer .footer-links li:nth-child(4) a'))->getAttribute('href');
		$privacy = $this->webDriver->findElement(
			WebDriverBy::cssSelector('.site-footer .footer-links-secondary li:nth-child(1) a'))->getAttribute('href');
		$community = $this->webDriver->findElement(
			WebDriverBy::cssSelector('.site-footer .footer-links-secondary li:nth-child(2) a'))->getAttribute('href');
		$search = $this->webDriver->findElement(
			WebDriverBy::cssSelector('.site-footer .footer-links-secondary li:nth-child(3) a'))->getAttribute('href');
		$this->webDriver->findElement(WebDriverBy::cssSelector('.site-footer .copyright-text'));
		$this->webDriver->findElement(WebDriverBy::cssSelector('.site-footer .gp-year'));
		
	}catch(Exception $e){
		$this->fail('->Failed to see some elements of footer block');
	}
	$this->assertEquals("https://www.facebook.com/greenpeace.international","$fb");
	$this->assertEquals("https://www.twitter.com/greenpeace","$twt");
	$this->assertEquals("https://www.youtube.com/greenpeace","$yt");
	$this->assertEquals("https://www.instagram.com/greenpeace/","$inst");
	$this->assertEquals("https://dev.p4.greenpeace.org/international/?s=&orderby=relevant&f%5Bctype%5D%5BPost%5D=3","$news");
	$this->assertEquals("https://dev.p4.greenpeace.org/international/explore/about/","$about");
	$this->assertEquals("https://www.linkedin.com/jobs/greenpeace-jobs/","$jobs");
	$this->assertEquals("https://dev.p4.greenpeace.org/international/press-centre/","$press");
	$this->assertEquals("https://dev.p4.greenpeace.org/international/explore/about/privacy/","$privacy");
	$this->assertEquals("https://dev.p4.greenpeace.org/explore/about/community-policy/","$community");
	$this->assertEquals("http://www.greenpeace.org/international/en/System-templates/Search-results/?adv=true","$search");
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
