<?php

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

class P4_Cartomap extends P4_login {

	/**
	 * @var \RemoteWebDriver
	 */
	public function testCartomap() {

		// Define variables.
		$cartomaplink = 'https://greenpeacemaps.carto.com/builder/d1359bc0-339b-40af-bfd0-98f97a574f3a/embed';
		$baseurl      = 'greenpeacemaps.carto.com';
		$urlid        = 'd1359bc0-339b-40af-bfd0-98f97a574f3a';

		//I log in
		try {
			$this->wpLogin();
		} catch ( Exception $e ) {
			$this->fail( '->Failed to log in, verify credentials and URL' );
		}

		// Go to pages and create content.
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

		// Enter title of page.
		$field = $this->driver->findElement(
			WebDriverBy::id( 'title-prompt-text' ) );
		$field->click();
		$this->driver->getKeyboard()->sendKeys( 'Test automated - Cartomap' );

		// Insert map link in body field.
		$field = $this->driver->findElement(
			WebDriverBy::id( 'content' ) );
		$field->click();
		$this->driver->getKeyboard()->sendKeys( "$cartomaplink" );
		usleep( 1000000 );

		// Publish content.
		$this->driver->findElement(
			WebDriverBy::id( 'publish' ) )->click();
		usleep( 1000000 );
		// Wait to see successful message.
		$this->driver->wait( 10, 1000 )->until(
			WebDriverExpectedCondition::visibilityOfElementLocated(
				WebDriverBy::id( 'message' ) ) );
		usleep( 2000000 );
		// Validate I see successful message.
		try {
			$this->assertContains(
				'Page published', $this->driver->findElement(
				WebDriverBy::id( 'message' ) )->getText()
			);
		} catch ( Exception $e ) {
			$this->fail( '->Failed to publish content - no sucessful message after saving content' );
		}
		// Go to page to validate page contains map.
		$link = $this->driver->findElement(
			WebDriverBy::linkText( 'View page' )
		);
		$link->click();
		// If alert shows up asking to confirm leaving the page, confirm.
		try {
			$this->driver->switchTo()->alert()->accept();
		} catch ( Exception $e ) {
		}
		// Wait for map to load.
		usleep( 2000000 );
		try {
			$maplink    = $this->driver->findElement(
				WebDriverBy::cssSelector( '.page-template-default p iframe' ) )->getAttribute( 'src' );
			$cartomap   = explode( "/", $maplink );
			$baseurl_pg = $cartomap[2];
			$urlid_pg   = $cartomap[4];
		} catch ( Exception $e ) {
			$this->fail( "->Some of the content created is not displayed in front end page" );
		}
		$this->assertEquals( $baseurl, $baseurl_pg );
		$this->assertEquals( $urlid, $urlid_pg );

		echo "\n-> Cartomap test PASSED";
	}
}
