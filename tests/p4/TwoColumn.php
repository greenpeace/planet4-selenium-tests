<?php

use Facebook\WebDriver\WebDriverBy as By;
use Facebook\WebDriver\WebDriverExpectedCondition;

class P4_TwoColumn_Content extends P4_login {

	/**
	 * @var \RemoteWebDriver
	 */
	public function testTwoColumnContent() {

		//I log in
		try {
			$this->wpLogin();
		} catch ( Exception $e ) {
			$this->fail( '->Failed to log in, verify credentials and URL' );
		}

		//Go to pages and create content
		$this->driver->wait( 3 );
		$pages = $this->driver->findElement(
			By::id( "menu-pages" ) );
		$pages->click();
		try {
			$link = $this->driver->findElement(
				By::linkText( "Add New" )
			);
		} catch ( Exception $e ) {
			$this->fail( "->Could not find 'Add New' button in Pages overview" );
		}
		$link->click();

		//Validate button to add blocks to page is present
		$this->assertContains(
			'Add Page Element', $this->driver->findElement(
			By::className( 'shortcake-add-post-element' ) )->getText()
		);


		//Enter title of page
		$field = $this->driver->findElement(
			By::id( 'title-prompt-text' )
		);
		$field->click();
		$this->driver->getKeyboard()->sendKeys( 'Test automated - Two Column Content' );

		//Click on button to add blocks
		$add = $this->driver->findElement(
			By::className( "shortcake-add-post-element" )
		);
		$add->click();

		//Validate blocks modal window is shown
		$this->assertContains(
			'Insert Post Element', $this->driver->findElement(
			By::className( 'media-frame-title' ) )->getText()
		);

		//Select Two Columns block
		try {
			$ta = $this->driver->findElement(
				By::cssSelector( "li[data-shortcode='shortcake_two_columns']" )
			);
			$ta->click();
		} catch ( Exception $e ) {
			$this->fail( "->Failed to select 'Two Column Content' post element" );
		}

		//Validate elements are present
		try {
			$this->driver->findElement( By::name( "title_1" ) );
			$this->driver->findElement( By::name( "description_1" ) );
			$this->driver->findElement( By::name( "button_text_1" ) );
			$this->driver->findElement( By::name( "button_link_1" ) );
			$this->driver->findElement( By::name( "title_2" ) );
			$this->driver->findElement( By::name( "description_2" ) );
			$this->driver->findElement( By::name( "button_text_2" ) );
			$this->driver->findElement( By::name( "button_link_2" ) );
		} catch ( Exception $e ) {
			$this->fail( "->Fields corresponding to 'Two Column Content' block not found" );
		}


		//-- Fill in fields column 1
		$titl1  = 'Column 1 Block Test';
		$desc1  = 'This is content created by an automated test for testing content in column 1 block';
		$btext1 = 'See on youtube';
		$blink1 = 'www.youtube.com';
		$field  = $this->driver->findElement(
			By::name( 'title_1' )
		);
		$field->click();
		$this->driver->getKeyboard()->sendKeys( "$titl1" );

		$field = $this->driver->findElement(
			By::name( 'description_1' )
		);
		$field->click();
		$this->driver->getKeyboard()->sendKeys( "$desc1" );
		$field = $this->driver->findElement(
			By::name( 'button_text_1' )
		);
		$field->click();
		$this->driver->getKeyboard()->sendKeys( "$btext1" );
		$field = $this->driver->findElement(
			By::name( 'button_link_1' )
		);
		$field->click();
		$this->driver->getKeyboard()->sendKeys( "$blink1" );

		//-- Fill in fields column 2
		$titl2  = 'Column 2 Block Test';
		$desc2  = 'This is content created by an automated test for testing content in column 2 Block';
		$btext2 = 'See on planet';
		$blink2 = 'www.greenpeace.org';
		$field  = $this->driver->findElement(
			By::name( 'title_2' )
		);
		$field->click();
		$this->driver->getKeyboard()->sendKeys( "$titl2" );

		$field = $this->driver->findElement(
			By::name( 'description_2' )
		);
		$field->click();
		$this->driver->getKeyboard()->sendKeys( "$desc2" );
		$field = $this->driver->findElement(
			By::name( 'button_text_2' )
		);
		$field->click();
		$this->driver->getKeyboard()->sendKeys( "$btext2" );
		$field = $this->driver->findElement(
			By::name( 'button_link_2' )
		);
		$field->click();
		$this->driver->getKeyboard()->sendKeys( "$blink2" );

		//Insert block
		try {
			$insert = $this->driver->findElement(
				By::className( 'media-button-insert' )
			);
			$insert->click();
		} catch ( Exception $e ) {
			$this->fail( '->Failed to insert element' );
		}

		//Publish content
		$this->driver->findElement(
			By::id( 'publish' )
		)->click();

		//Wait to see successful message
		$this->driver->wait( 10, 1000 )->until(
			WebDriverExpectedCondition::visibilityOfElementLocated(
				By::id( 'message' ) ) );
		//Validate I see successful message
		try {
			$this->assertContains(
				'Page published', $this->driver->findElement(
				By::id( 'message' ) )->getText()
			);
		} catch ( Exception $e ) {
			$this->fail( '->Failed to publish content - no sucessful message after saving content' );
		}
		//Wait for saved changes to load
		usleep( 2000000 );
		//Go to page to validate page contains Take Action Block
		$link = $this->driver->findElement(
			By::linkText( 'View page' )
		);
		$link->click();
		//If alert shows up asking to confirm leaving the page, confirm
		try {
			$this->driver->switchTo()->alert()->accept();
		} catch ( Exception $e ) {
		}
		try {
			$this->driver->findElement( By::className( 'content-two-column-block' ) );
			$titl1_pg  = $this->driver->findElement(
				By::cssSelector( '.row > div.col-md-12:nth-child(1) h2' ) )->getText();
			$desc1_pg  = $this->driver->findElement(
				By::cssSelector( '.row > div.col-md-12:nth-child(3) p' ) )->getText();
			$btext1_pg = $this->driver->findElement(
				By::cssSelector( '.row > div.col-md-12:nth-child(5) a.btn' ) )->getText();
			$blink1_pg = $this->driver->findElement(
				By::cssSelector( '.row > div.col-md-12:nth-child(5) a.btn' ) )->getAttribute( 'href' );
			$titl2_pg  = $this->driver->findElement(
				By::cssSelector( '.row > div.col-md-12:nth-child(2) h2' ) )->getText();
			$desc2_pg  = $this->driver->findElement(
				By::cssSelector( '.row > div.col-md-12:nth-child(4) p' ) )->getText();
			$btext2_pg = $this->driver->findElement(
				By::cssSelector( '.row > div.col-md-12:nth-child(6) a.btn' ) )->getText();
			$blink2_pg = $this->driver->findElement(
				By::cssSelector( '.row > div.col-md-12:nth-child(6) a.btn' ) )->getAttribute( 'href' );
		} catch ( Exception $e ) {

			$this->fail( $e->getMessage() );
			$this->fail( '->Some of the content created is not displayed in front end page' );
		}
		$this->assertEquals( $titl1, $titl1_pg );
		$this->assertEquals( $desc1, $desc1_pg );
		$this->assertEquals( strtoupper( $btext1 ), $btext1_pg );
		$this->assertContains( $blink1, $blink1_pg );
		$this->assertEquals( $titl2, $titl2_pg );
		$this->assertEquals( $desc2, $desc2_pg );
		$this->assertEquals( strtoupper( $btext2 ), $btext2_pg );
		$this->assertContains( $blink2, $blink2_pg );

		echo "\n-> Two column block test PASSED";
	}
}
