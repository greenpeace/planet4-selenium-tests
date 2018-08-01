<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../wp-core/login.php';

class P4_MediaBlock extends P4_login {

	/**
	 * @var \RemoteWebDriver
	 */
	public function testMediaBlock() {

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
		$this->driver->getKeyboard()->sendKeys( 'Test automated - Media block' );

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

		//Select Media block
		try {
			$ta = $this->driver->findElement(
				WebDriverBy::cssSelector( "li[data-shortcode='shortcake_media_block']" )
			);
			$ta->click();
		} catch ( Exception $e ) {
			$this->fail( "->Failed to select 'Media Block' post element" );
		}

		//Validate corresponding fields are present
		try {
			$this->driver->findElement( WebDriverBy::id( "attachment" ) );
		} catch ( Exception $e ) {
			$this->fail( "->Fields corresponding to 'Media block' not found" );
		}

		//Upload image
		$this->driver->findElement( WebDriverBy::id( 'attachment' ) )->click();
		$this->driver->findElement( WebDriverBy::linkText( 'Media Library' ) )->click();
		//Wait for media library to load
		$this->driver->wait( 10, 1000 )->until(
			WebDriverExpectedCondition::presenceOfElementLocated(
				WebDriverBy::cssSelector( 'ul.attachments' ) ) );
		$this->driver->manage()->timeouts()->implicitlyWait( 10 );
		//Select first image of media library
		$tmp           = explode( "-", $this->driver->findElement(
			WebDriverBy::cssSelector( "li.attachment:first-child img" ) )->getAttribute( 'src' ) );
		$srcfirstchild = $tmp[1];
		$this->driver->findElement( WebDriverBy::cssSelector( "li.attachment:first-child" ) )->click();
		$this->driver->findElement( WebDriverBy::className( "media-button-select" ) )->click();

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
		//Wait for saved changes to load
		usleep( 2000000 );
		//Go to page to validate page contains Media Block
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
			$this->driver->findElement( WebDriverBy::className( 'block-media' ) );
			$tmp    = explode( "-", $this->driver->findElement(
				WebDriverBy::cssSelector( '.block-media img' ) )->getattribute( 'src' ) );
			$srcimg = $tmp[1];
		} catch ( Exception $e ) {
			$this->fail( "->Some of the content created is not displayed in front end page" );
		}
		$this->assertContains( "$srcimg", "$srcfirstchild" );

		echo "\n-> Media block test PASSED";
	}
}
