<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../AbstractClass.php';

class P4_Search extends AbstractClass {

	/**
	 * @var \RemoteWebDriver
	 */
	public function testSearch() {
		$str = "Test automated";
		$fld = $this->driver->findElement( WebDriverBy::cssSelector( '#search_form input.form-control' ) );
		$fld->click();
		$this->driver->getKeyboard()->sendKeys( "$str" );
		$this->driver->findElement( WebDriverBy::cssSelector( '#search_form .top-nav-search-btn' ) )->click();
		$ttl = explode( " -", $this->driver->getTitle() );
		$ttl = $ttl[0];
		$this->assertEquals( "Search Results $str", "$ttl" );
		$t = $this->driver->findElement(
			WebDriverBy::cssSelector( 'h2.result-statement' ) )->getText();
		$this->assertContains( "results for '$str'", "$t" );
		$res = explode( " ", "$t" );
		if ( $res[0] == "0" ) {
			//If no results shown throw error
			$this->fail( "->Could not find any results, check if there is content containing string: '$str'" );
		}
		try {
			$ttl = $this->driver->findElement(
				WebDriverBy::cssSelector( 'li.search-result-list-item:first-child a.search-result-item-headline' ) )->getText();
			$this->assertContains( "$str", "$ttl" );
		} catch ( Exception $e ) {
			$this->fail( '->Search results do not match string entered' );
		}
		echo "\n-> Search test PASSED";
	}
}
