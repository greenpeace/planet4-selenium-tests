<?php

use WebDriverBy as By;

// This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../AbstractClass.php';

class P4_Actpage extends AbstractClass {

  	public function testActpage() {
		$this->webDriver->get($this->_url);

		$this->webDriver->findElement(By::className('act-nav-link'))->click();

		$this->assertEquals($this->webDriver->getCurrentURL(), $this->_url . 'act/');

		// Validate header block is present in page
		try {
			$this->webDriver->findElement(By::className('page-header'));
			$this->webDriver->findElement(By::className('page-header-title'));
			$this->webDriver->findElement(By::className('page-header-subtitle'));
			$this->webDriver->findElement(By::className('page-header-content'));
		} catch(Exception $e){
			$this->fail('->Failed to see header block in act page');
		}

		// Validate header title font
		try {
			$font = $this->webDriver->findElement(By::className('page-header-title'))->getCssValue('font-family');
			$this->assertEquals('Roboto, sans-serif', $font);
			$padding = $this->webDriver->findElement(By::className('page-header'))->getCssValue('padding');
			$this->assertEquals('144px 0px 60px', $padding);
		} catch(Exception $e){
			$this->fail('->Failed: Page header title has wrong font');
		}

		// Validate covers block is present in page
		try {
			$this->webDriver->findElement(By::className('covers-block'));
			$this->webDriver->findElement(By::className('cover-card'));
		} catch(Exception $e){
			$this->fail('->Failed to see covers block in act page');
		}

		// Validate happy point block is present in page
		try {
			$this->webDriver->findElement(By::className('happy-point-block-wrap'));
			$element = $this->webDriver->findElement(
				By::id('happy-point'));
			// Scroll down and wait to happy point to load
			$element->getLocationOnScreenOnceScrolledIntoView();
			usleep(2000000);
			$this->webDriver->switchTo()->frame($this->webDriver->findElement(By::cssSelector('#happy-point iframe')));
			// Validate input fields are present
			$this->webDriver->findElement(By::id('en__field_supporter_emailAddress'));
			$this->webDriver->findElement(By::id('en__field_supporter_country'));
			$this->webDriver->findElement(By::className('subscriber-btn'));
		} catch(Exception $e) {
			$this->fail('->Failed to see happy point block in act page');
		}

		$this->webDriver->switchTo()->defaultContent();

		// Validate footer block is present in page
		try {
			$this->webDriver->findElement(By::className('site-footer'));
			$this->webDriver->findElement(By::className('footer-social-media'));
			$this->webDriver->findElement(By::className('footer-links'));
			$this->webDriver->findElement(By::className('footer-links-secondary'));
		} catch(Exception $e) {
			$this->fail('->Failed to see footer block in act page');
		}
		echo "\n-> Act Page test PASSED";
	}

	protected function assertElementNotFound($by) {
		$this->webDriver->takeScreenshot('reports/screenshots/' . __CLASS__ . '.png');
		$els = $this->webDriver->findElements($by);
		if (count($els)) {
			$this->fail('Unexpectedly element was found');
		}
		// Increment assertion counter
		$this->assertTrue(true);
	}
}
?>
