<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../wp-core/login.php';

class P4_Varnish extends P4_login {

	/**
	 * @var \RemoteWebDriver
	 */

	public function testVarnish() {
		//I log in
		try {
			$this->wpLogin();
		} catch ( Exception $e ) {
			$this->fail( '->Failed to log in, verify credentials and URL' );
		}
		//Go to pages and create content
		$this->driver->wait( 3 );
		$pages = $this->driver->findElement(
			WebDriverBy::id( "menu-pages" ) );
		$pages->click();
		try {
			$link = $this->driver->findElement(
				WebDriverBy::linkText( "Add New" )
			);
		} catch ( Exception $e ) {
			$this->fail( "->Could not find 'Add New' button in Pages overview" );
		}
		$link->click();

		//Enter title ad description of page
		$field = $this->driver->findElement(
			WebDriverBy::id( 'title-prompt-text' ) );
		$field->click();
		$testid    = rand( 543210, 987650 );
		$testtitle = "Test automated - Varnish $testid";
		$this->driver->getKeyboard()->sendKeys( "$testtitle" );
		$field = $this->driver->findElement(
			WebDriverBy::id( 'content' ) );
		$field->click();
		$this->driver->getKeyboard()->sendKeys( "This is demo content generated by an automated test" );

		usleep( 2000000 );

		//Publish content
		$btn = $this->driver->findElement(
			WebDriverBy::id( 'publish' ) );
		$btn->click();

		//Wait to see successful message
		usleep( 2000000 );
		//Validate I see successful message
		try {
			$this->assertContains(
				'Page published', $this->driver->findElement(
				WebDriverBy::id( 'message' ) )->getText()
			);
		} catch ( Exception $e ) {
			$this->fail( '->Failed to publish content - no sucessful message after saving content' );
		}

		//Wait for saved changes to load
		usleep( 2000000 );
		//Go to page to validate page contains added block
		$link = $this->driver->findElement(
			WebDriverBy::linkText( 'View page' ) );
		$link->click();
		//If alert shows up asking to confirm leaving the page, confirm
		try {
			$this->driver->switchTo()->alert()->accept();
		} catch ( Exception $e ) {
		}
		usleep( 2000000 );
		//Go to search and look for page
		try {
			$fld = $this->driver->findElement( WebDriverBy::cssSelector( '#search_form input.form-control' ) );
			$fld->click();
			$this->driver->getKeyboard()->sendKeys( "$testid" );
			$this->driver->findElement( WebDriverBy::cssSelector( '#search_form .top-nav-search-btn' ) )->click();
			$ttl = explode( " -", $this->driver->getTitle() );
			$ttl = $ttl[0];
			$this->assertEquals( "Search Results $testid", "$ttl" );
		} catch ( Exception $e ) {
			$this->fail( '->Failed to search for created content' );
		}
		$res = explode( " ", $this->driver->findElement(
			WebDriverBy::cssSelector( 'h2.result-statement' ) )->getText() );
		if ( $res[0] == "0" ) {
			//If no results shown wait for 2 minutes and refresh
			usleep( 123000000 );
			$this->driver->navigate()->refresh();
			//$this->webDriver->navigator->refresh();
		}
		try {
			$ttl = $this->driver->findElement(
				WebDriverBy::cssSelector( 'li.search-result-list-item:first-child a.search-result-item-headline' ) )->getText();
			$this->assertContains( "Varnish $testid", "$ttl" );
		} catch ( Exception $e ) {
			$this->fail( '->Unable to see created content after 2 minutes' );
		}
		// I log out after test
		$this->wpLogout();
	}
}
