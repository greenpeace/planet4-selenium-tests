<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../wp-core/login.php';

class P4_HappyPoint extends P4_login {

	/**
	 * @var \RemoteWebDriver
	 */
	public function testHappyPoint() {

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
		$this->driver->getKeyboard()->sendKeys( 'Test automated - Happy Point' );

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

		//Select block
		try {
			$ta = $this->driver->findElement(
				WebDriverBy::cssSelector( "li[data-shortcode='shortcake_happy_point']" )
			);
			$ta->click();
		} catch ( Exception $e ) {
			$this->fail( "->Failed to select 'Happy Point' block post element" );
		}

		//Validate corresponding fields are visible
		try {
			$this->driver->findElement( WebDriverBy::id( "background" ) );
			$this->driver->findElement( WebDriverBy::name( "mailing_list_iframe" ) );
		} catch ( Exception $e ) {
			$this->fail( "->Could not find tasks fields for 'Happy Point' block post element" );
		}

		//---- Fill in fields
		$this->driver->findElement( WebDriverBy::id( "background" ) )->click();
		$tab = $this->driver->findElement( WebDriverBy::linkText( 'Media Library' ) );
		$tab->click();
		//Wait for media library to load
		$this->driver->wait( 10, 1000 )->until(
			WebDriverExpectedCondition::presenceOfElementLocated(
				WebDriverBy::cssSelector( 'ul.attachments' ) )
		);
		$this->driver->manage()->timeouts()->implicitlyWait( 10 );
		//Select first image
		$srcfirstchild = explode( "-300x200", $this->driver->findElement(
			WebDriverBy::cssSelector( "li.attachment:first-child img" ) )->getAttribute( 'src' ) );
		$srcfirstchild = $srcfirstchild[0];
		$img           = $this->driver->findElement( WebDriverBy::cssSelector( "li.attachment:first-child" ) );
		$img->click();
		$this->driver->findElement( WebDriverBy::className( "media-button-select" ) )->click();

		$chckbx = $this->driver->findElement( WebDriverBy::name( "mailing_list_iframe" ) )->getAttribute( 'value' );
		if ( $chckbx != "true" ) {
			$this->driver->findElement( WebDriverBy::name( "mailing_list_iframe" ) )->click();
		}

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
		//Wait for saved changes to load
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
			$srcimg = substr( $this->driver->findElement(
				WebDriverBy::cssSelector( '.happy-point-block-wrap picture img' ) )->getAttribute( 'src' ), 0, - 4 );
			$this->driver->findElement( WebDriverBy::className( 'happy-point-block-wrap' ) );
			$element = $this->driver->findElement(
				WebDriverBy::id( 'happy-point' ) );
			//Scroll down and wait to happy point to load
			$element->getLocationOnScreenOnceScrolledIntoView();
			usleep( 2000000 );
			$this->driver->switchTo()->frame( $this->driver->findElement(
				WebDriverBy::cssSelector( '#happy-point iframe' ) ) );
			//Validate input fields are present
			$this->driver->findElement( WebDriverBy::id( "en__field_supporter_emailAddress" ) );
			$this->driver->findElement( WebDriverBy::id( "en__field_supporter_country" ) );
			$this->driver->findElement( WebDriverBy::className( "subscriber-btn" ) );
		} catch ( Exception $e ) {
			$this->fail( '->Some of the content created is not displayed in front end page' );
		}
		$this->assertContains( $srcimg, $srcfirstchild );
		$this->driver->switchTo()->defaultContent();

		// I log out after test
		$this->wpLogout();
		echo "\n-> Happy point block test PASSED";
	}
}
