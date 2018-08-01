<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../wp-core/login.php';

class P4_FourColumn extends P4_login {

	/**
	 * @var \RemoteWebDriver
	 */
	public function testFourColumn() {

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

		// Validate button to add blocks to page is present.
		$this->assertContains(
			'Add Page Element', $this->driver->findElement(
			WebDriverBy::className( 'shortcake-add-post-element' ) )->getText()
		);


		// Enter title of page.
		$field = $this->driver->findElement(
			WebDriverBy::id( 'title-prompt-text' )
		);
		$field->click();
		$this->driver->getKeyboard()->sendKeys( 'Test automated - Content 4 Column' );

		// Click on button to add blocks.
		$add = $this->driver->findElement(
			WebDriverBy::className( "shortcake-add-post-element" )
		);
		$add->click();

		// Validate blocks modal window is shown.
		$this->assertContains(
			'Insert Post Element', $this->driver->findElement(
			WebDriverBy::className( 'media-frame-title' ) )->getText()
		);

		// Select Content 4 column block.
		try {
			$ta = $this->driver->findElement(
				WebDriverBy::cssSelector( "li[data-shortcode='shortcake_content_four_column']" )
			);
			$ta->click();
		} catch ( Exception $e ) {
			$this->fail( "->Failed to select 'Content Four Column' post element" );
		}

		// Validate corresponding fields are present.
		try {
			$this->driver->findElement( WebDriverBy::name( 'title' ) );
			$this->driver->findElement( WebDriverBy::name( 'select_tag' ) );
			$this->driver->findElement( WebDriverBy::name( 'p4_page_type_press_release' ) );
			$this->driver->findElement( WebDriverBy::name( 'p4_page_type_publication' ) );
			$this->driver->findElement( WebDriverBy::name( 'p4_page_type_story' ) );
		} catch ( Exception $e ) {
			$this->fail( $e->getMessage() );
			$this->fail( "->Fields corresponding to 'Content Four Column' block not found" );
		}

		//----- FILL IN FIELDS
		//Define test content
		$titl = "Content 4 column Test title";
		$tg   = "ArcticSunrise";
		//Fill in fields
		$this->driver->findElement( WebDriverBy::name( 'title' ) )->click();
		$this->driver->getKeyboard()->sendKeys( "$titl" );
		//Fill in tag
		$this->driver->findElement( WebDriverBy::className( "select2-container" ) )->click();
		$this->driver->getKeyboard()->sendKeys( "$tg" );
		//Sleep for 3 seconds
		usleep( 3000000 );
		//Select suggestion
		$this->driver->getKeyboard()->pressKey( WebDriverKeys::ENTER );
		//Select post type
		$this->driver->findElement( WebDriverBy::name( 'p4_page_type_story' ) )->click();

		//Insert block
		try {
			$insert = $this->driver->findElement(
				WebDriverBy::className( 'media-button-insert' )
			);
			$insert->click();
		} catch ( Exception $e ) {
			$this->fail( '->Failed to insert element' );
		}

		$this->driver->manage()->timeouts()->implicitlyWait( 5 );

		//Publish content
		$this->driver->findElement(
			WebDriverBy::id( 'publish' )
		)->click();
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
		//Wait 2 secs for saved changes to load
		usleep( 2000000 );
		//Go to page to validate page contains added block
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
			$this->driver->findElement( WebDriverBy::className( 'four-column-content' ) );
			$titl_pg = $this->driver->findElement(
				WebDriverBy::cssSelector( '.four-column-content h2.page-section-header' ) )->getText();
			$this->driver->findElement( WebDriverBy::className( 'publications-slider' ) );
		} catch ( Exception $e ) {
			$this->fail( '->Some of the content created is not displayed in front end page' );
		}
		$this->assertEquals( $titl, $titl_pg );

		echo "\n-> Four column block test PASSED";
	}
}
