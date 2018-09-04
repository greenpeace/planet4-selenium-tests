<?php

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;

class P4_CarouselSplit extends P4_login {

	/**
	 * @var \RemoteWebDriver
	 */
	public function testCarouselSplit() {

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
		$this->driver->getKeyboard()->sendKeys( 'Test automated - Carousel Split' );

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

		// Select Carousel Split block.
		try {
			$ta = $this->driver->findElement(
				WebDriverBy::cssSelector( "li[data-shortcode='shortcake_carousel_split']" )
			);
			$ta->click();
		} catch ( Exception $e ) {
			$this->fail( "->Failed to select 'Carousel Split' post element" );
		}

		// Validate corresponding fields are present.
		try {
			$this->driver->findElement( WebDriverBy::id( "multiple_images" ) );
		} catch ( Exception $e ) {
			$this->fail( "->Fields corresponding to 'Carousel Split' block not found" );
		}

		// Add images.
		$this->driver->findElement( WebDriverBy::id( 'multiple_images' ) )->click();
		$this->driver->findElement( WebDriverBy::linkText( 'Media Library' ) )->click();
		//Wait for media library to load
		$this->driver->wait( 10, 1000 )->until(
			WebDriverExpectedCondition::presenceOfElementLocated(
				WebDriverBy::cssSelector( 'ul.attachments' ) ) );
		$this->driver->manage()->timeouts()->implicitlyWait( 10 );

		//Select first image of media library
		$srcfirstchild = explode( "-", $this->driver->findElement(
			WebDriverBy::cssSelector( "li.attachment:first-child img" ) )->getAttribute( 'src' ) );
		$srcfirstchild = $srcfirstchild[1];
		$this->driver->findElement( WebDriverBy::cssSelector( "li.attachment:first-child" ) )->click();
		$this->driver->findElement( WebDriverBy::className( "media-button-select" ) )->click();


		// Insert block.
		try {
			$insert = $this->driver->findElement(
				WebDriverBy::className( 'media-button-insert' )
			);
			$insert->click();
		} catch ( Exception $e ) {
			$this->fail( '->Failed to insert element' );
		}

		// Publish content.
		$this->driver->findElement(
			WebDriverBy::id( 'publish' )
		)->click();

		// Wait to see successful message.
		$this->driver->wait( 10, 1000 )->until(
			WebDriverExpectedCondition::visibilityOfElementLocated(
				WebDriverBy::id( 'message' ) ) );
		// Validate I see successful message.
		try {
			$this->assertContains(
				'Page published', $this->driver->findElement(
				WebDriverBy::id( 'message' ) )->getText()
			);
		} catch ( Exception $e ) {
			$this->fail( '->Failed to publish content - no sucessful message after saving content' );
		}

		// Wait for saved changes to load.
		$this->driver->manage()->timeouts()->implicitlyWait( 100 );
		// Go to page to validate page contains Articles Block.
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
			$this->driver->findElement( WebDriverBy::className( "split-carousel-wrap" ) );
			$srcimg = explode( "-", $this->driver->findElement(
				WebDriverBy::cssSelector( '#carousel-wrapper .carousel-item.active img' ) )->getAttribute( 'src' ) );
			$srcimg = $srcimg[1];
		} catch ( Exception $e ) {
			$this->fail( "->Some of the content created is not displayed in front end page" );
		}
		$this->assertContains( "$srcimg", "$srcfirstchild" );
		// I log out after test
		$this->wpLogout();
		echo "\n-> Carousel split block test PASSED";
	}
}
