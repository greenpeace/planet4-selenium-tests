<?php

use WebDriverBy as By;

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../AbstractClass.php';

class P4_Homepage extends AbstractClass {

	public function testHomepage() {
		$this->webDriver->get($this->_url);

		// Validate Header and footer colors
		try {
			$color = $this->webDriver->findElement(By::className('site-footer'))->getCssValue('background-color');
			$this->assertEquals('rgba(7, 67, 101, 1)', $color);
			$color = $this->webDriver->findElement(By::id('header'))->getCssValue('background-color');
			$this->assertEquals('rgba(7, 67, 101, 0.8)', $color);
		} catch(Exception $e) {
			$this->fail('->Failed: Header or Footer colors are wrong');
		}

		// Validate 2-column block elements are present
		try {
			$this->webDriver->findElement(By::className('content-two-column-block'));
			$this->webDriver->findElement(By::cssSelector('.content-two-column-block .col-md-12:nth-child(1)'));
			$this->webDriver->findElement(By::cssSelector('.content-two-column-block .col-md-12:nth-child(2)'));
		} catch(Exception $e) {
			$this->fail('->Failed to see some elements of 2-column block in homepage');
		}

		// Validate article block elements are present
		try {
			$this->webDriver->findElement(By::className('article-listing'));
		} catch(Exception $e) {
			$this->fail('->Failed to see some elements of article block in homepage');
		}

		// Validate 4-column block elements are present
		try {
			$this->webDriver->findElement(By::className('four-column'));
			$this->webDriver->findElement(By::cssSelector('.four-column .four-column-wrap:nth-child(1)'));
			$this->webDriver->findElement(By::cssSelector('.four-column .four-column-wrap:nth-child(2)'));
			$this->webDriver->findElement(By::cssSelector('.four-column .four-column-wrap:nth-child(3)'));
			$this->webDriver->findElement(By::cssSelector('.four-column .four-column-wrap:nth-child(4)'));
		} catch(Exception $e) {
			$this->fail('->Failed to see some elements of 4-column block in homepage');
		}

		// Validate happy point block elements are present
		try {
			$this->webDriver->findElement(By::className('happy-point-block-wrap'));
			$element = $this->webDriver->findElement(By::id('happy-point'));
			// Scroll down and wait to happy point to load
			$element->getLocationOnScreenOnceScrolledIntoView();
			usleep(10000000);
			$this->webDriver->switchTo()->frame($this->webDriver->findElement(By::cssSelector('#happy-point iframe')));
			// Validate input fields are present
			$this->webDriver->findElement(By::id('en__field_supporter_emailAddress'));
			$this->webDriver->findElement(By::id('en__field_supporter_country'));
			$this->webDriver->findElement(By::className('subscriber-btn'));
		} catch(Exception $e) {
			$this->fail('->Failed to see happy point block in homepage');
		}
		$this->webDriver->switchTo()->defaultContent();

		// Validate footer block elements are present
		try {
			$this->webDriver->findElement(By::className('site-footer'));
			$fb = $this->webDriver->findElement(
				By::cssSelector('.site-footer .footer-social-media li:nth-child(1) a'))->getAttribute('href');
			$twt = $this->webDriver->findElement(
				By::cssSelector('.site-footer .footer-social-media li:nth-child(2) a'))->getAttribute('href');
			$yt = $this->webDriver->findElement(
				By::cssSelector('.site-footer .footer-social-media li:nth-child(3) a'))->getAttribute('href');
			$inst = $this->webDriver->findElement(
				By::cssSelector('.site-footer .footer-social-media li:nth-child(4) a'))->getAttribute('href');
			$news = $this->webDriver->findElement(
				By::cssSelector('.site-footer .footer-links li:nth-child(1) a'))->getAttribute('href');
			$about = $this->webDriver->findElement(
				By::cssSelector('.site-footer .footer-links li:nth-child(2) a'))->getAttribute('href');
			$jobs = $this->webDriver->findElement(
				By::cssSelector('.site-footer .footer-links li:nth-child(3) a'))->getAttribute('href');
			$press = $this->webDriver->findElement(
				By::cssSelector('.site-footer .footer-links li:nth-child(4) a'))->getAttribute('href');
			$privacy = $this->webDriver->findElement(
				By::cssSelector('.site-footer .footer-links-secondary li:nth-child(1) a'))->getAttribute('href');
			$copyright = $this->webDriver->findElement(
				By::cssSelector('.site-footer .footer-links-secondary li:nth-child(2) a'))->getAttribute('href');
			$terms = $this->webDriver->findElement(
				By::cssSelector('.site-footer .footer-links-secondary li:nth-child(3) a'))->getAttribute('href');
			$community = $this->webDriver->findElement(
				By::cssSelector('.site-footer .footer-links-secondary li:nth-child(4) a'))->getAttribute('href');
			$search = $this->webDriver->findElement(
				By::cssSelector('.site-footer .footer-links-secondary li:nth-child(5) a'))->getAttribute('href');
			$this->webDriver->findElement(By::cssSelector('.site-footer .copyright-text'));
			$this->webDriver->findElement(By::cssSelector('.site-footer .gp-year'));

		} catch(Exception $e) {
			$this->fail('->Failed to see some elements of footer block');
		}

		$this->assertEquals('https://www.facebook.com/greenpeace.international', $fb);
		$this->assertEquals('https://twitter.com/greenpeace', $twt);
		$this->assertEquals('https://www.youtube.com/greenpeace', $yt);
		$this->assertEquals('https://www.instagram.com/greenpeace/', $inst);
		$this->assertEquals('https://dev.p4.greenpeace.org/international/?s=&orderby=relevant&f%5Bctype%5D%5BPost%5D=3', $news);
		$this->assertEquals($this->_url . 'explore/about/', $about);
		$this->assertEquals('https://www.linkedin.com/jobs/greenpeace-jobs/', $jobs);
		$this->assertEquals('https://dev.p4.greenpeace.org/international/press-centre/', $press);
		$this->assertEquals($this->_url . 'privacy/', $privacy);
		$this->assertEquals($this->_url . 'copyright/', $copyright);
		$this->assertEquals($this->_url . 'terms/', $terms);
		$this->assertEquals($this->_url . 'community-policy/', $community);
		$this->assertEquals('http://www.greenpeace.org/international/en/System-templates/Search-results/?adv=true', $search);

		echo "\n-> Homepage test PASSED";
  	}


	protected function assertElementNotFound($by) {
		$this->webDriver->takeScreenshot('reports/screenshots/'.__CLASS__.'.png');
		$els = $this->webDriver->findElements($by);
		if (count($els)) {
			$this->fail('Unexpectedly element was found');
		}
		// increment assertion counter
		$this->assertTrue(true);
	}
}
?>
