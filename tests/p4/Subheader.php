<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../wp-core/login.php';

class P4_Subheader extends P4_login {

	/**
	 * @var \RemoteWebDriver
	 */
	public function testSubheader() {

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

		//Validate button to add blocks to page is present
		$this->assertContains(
			'Add Page Element', $this->driver->findElement(
			WebDriverBy::className( 'shortcake-add-post-element' ) )->getText()
		);

		//Enter title of page
		$field = $this->driver->findElement(
			WebDriverBy::id( 'title-prompt-text' )
		);
		$field->click();
		$this->driver->getKeyboard()->sendKeys( 'Test automated - Subheader' );

		//Click on button to add blocks
		$add = $this->driver->findElement(
			WebDriverBy::className( "shortcake-add-post-element" )
		);
		$add->click();

		//Validate blocks modal window is shown
		$this->assertContains(
			'Insert Post Element', $this->driver->findElement(
			WebDriverBy::className( 'media-frame-title' ) )->getText()
		);

		//Select Subheader block
		try {
			$ta = $this->driver->findElement(
				WebDriverBy::cssSelector( "li[data-shortcode='shortcake_subheader']" )
			);
			$ta->click();
		} catch ( Exception $e ) {
			$this->fail( "->Failed to select 'Subheader' post element" );
		}

		//Enter Block Title and Description
		$titl  = 'Subheader Block Test';
		$desc  = 'This is content created by an automated test for testing subheader block';
		$field = $this->driver->findElement(
			WebDriverBy::name( 'title' )
		);
		$field->click();
		$this->driver->getKeyboard()->sendKeys( "$titl" );

		$field = $this->driver->findElement(
			WebDriverBy::name( 'description' )
		);
		$field->click();
		$this->driver->getKeyboard()->sendKeys( "$desc" );

		//Insert block
		try {
			$insert = $this->driver->findElement(
				WebDriverBy::className( 'media-button-insert' )
			);
			$insert->click();
		} catch ( Exception $e ) {
			$this->fail( '->Failed to insert element' );
		}

		//Publish content
		$this->driver->findElement(
			WebDriverBy::id( 'publish' )
		)->click();

		//Wait for saved changes to load
		usleep( 2000000 );

		//Wait to see successful message
		$this->driver->wait( 10, 1000 )->until(
			WebDriverExpectedCondition::visibilityOfElementLocated(
				WebDriverBy::id( 'message' ) ) );
		//Validate I see successful message
		try {
			$this->assertContains(
				'Page published', $this->driver->findElement(
				WebDriverBy::id( 'message' ) )->getText()
			);
		} catch ( Exception $e ) {
			$this->fail( '->Failed to publish content - no sucessful message after saving content' );
		}
		//Go to page to validate page contains Subheader Block
		$link = $this->driver->findElement(
			WebDriverBy::linkText( 'View page' )
		);
		$link->click();
		//If alert shows up asking to confirm leaving the page, confirm
		try {
			$this->driver->switchTo()->alert()->accept();
		} catch ( Exception $e ) {
		}
		try {
			$this->driver->findElement( WebDriverBy::className( 'subheader' ) );
			$this->assertEquals( "$titl", $this->driver->findElement(
				WebDriverBy::cssSelector( '.subheader .container h2' ) )->getText() );
			/**$this->assertEquals("$desc",$this->webDriver->findElement(
			 * WebDriverBy::cssSelector('.subheader .container h2'))->getText());**/
		} catch ( Exception $e ) {
			$this->fail( '->Some of the content created is not displayed in front end page' );
		}
		// I log out after test
		$this->wpLogout();

		echo "\n-> Subheader block test PASSED";
	}
}
