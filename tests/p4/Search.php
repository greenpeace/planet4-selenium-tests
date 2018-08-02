<?php

use WebDriverBy as By;

// This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../AbstractClass.php';

class P4_Search extends AbstractClass {

	public function testSearch() {
		$str = 'Test automated';
		$fld = $this->webDriver->findElement(By::cssSelector('#search_form input.form-control'));
		$fld->click();
		$this->webDriver->getKeyboard()->sendKeys($str);
		$this->webDriver->findElement(By::cssSelector('#search_form .top-nav-search-btn'))->click();
		$ttl = explode(' -', $this->webDriver->getTitle());
		$ttl= $ttl[0];
		$this->assertEquals('Search Results ' . $str, $ttl);
		$t = $this->webDriver->findElement(By::cssSelector('h2.result-statement'))->getText();
		$this->assertContains('results for \'' . $str . '\'', $t);
		$res = explode(' ', $t);
		if ($res[0] == '0') {
			// If no results shown throw error
			$this->fail('->Could not find any results, check if there is content containing string: ' . $str);
		}
		try {
			$ttl = $this->webDriver->findElement(
				By::cssSelector('li.search-result-list-item:first-child a.search-result-item-headline'))->getText();
			$this->assertContains($str, $ttl);
		} catch(Exception $e) {
			$this->fail('->Search results do not match string entered');
		}
		echo "\n-> Search test PASSED";
	}

	protected function assertElementNotFound($by) {
		$this->webDriver->takeScreenshot('reports/screenshots/' . __CLASS__. '.png');
		$els = $this->webDriver->findElements($by);
		if (count($els)) {
			$this->fail('Unexpectedly element was found');
		}
		// Increment assertion counter
		$this->assertTrue(true);

	}
}
?>
