<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../wp-core/login.php';

use WebDriverBy as By;

class P4_Carousel extends P4_login {

	/**
	 * @var \RemoteWebDriver
	 */
	public function testCarousel() {

		//I log in
		try {
			$this->wpLogin();
		} catch ( Exception $e ) {
			$this->fail( '->Failed to log in, verify credentials and URL' );
		}

		//Go to pages and create content
		$this->webDriver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(By::id('menu-pages')));
		$pages = $this->webDriver->findElement( By::id( 'menu-pages' ) );
		$pages->click();
		try {
			$link = $this->webDriver->findElement( By::linkText( 'Add New' ) );
		} catch ( Exception $e ) {
			$this->fail( '->Could not find \'Add New\' button in Pages overview' );
		}
		$link->click();
		//Validate button to add blocks to page is present
		$this->assertContains(
			'Add Page Element', $this->webDriver->findElement(
			By::className( 'shortcake-add-post-element' ) )->getText()
		);


		//Enter title of page
		$field = $this->webDriver->findElement(
			By::id( 'title-prompt-text' )
		);
		$field->click();
		$this->webDriver->getKeyboard()->sendKeys( 'Test automated - Carousel' );

		//Click on button to add blocks
		$add = $this->webDriver->findElement(
			By::className( 'shortcake-add-post-element' )
		);
		$add->click();

		//Validate blocks modal window is shown
		$this->assertContains(
			'Insert Post Element', $this->webDriver->findElement(
			By::className( 'media-frame-title' ) )->getText()
		);

		//Select Carousel block
		try {
			$ta = $this->webDriver->findElement(
				By::cssSelector( 'li[data-shortcode=\'shortcake_carousel\']' )
			);
			$ta->click();
		} catch ( Exception $e ) {
			$this->fail( "->Failed to select 'Carousel' post element" );
		}

		//Enter Block Title
		$titl  = 'Carousel Block Test';
		$field = $this->webDriver->findElement(
			By::name( 'carousel_block_title' )
		);
		$field->click();
		$this->webDriver->getKeyboard()->sendKeys( $titl );

		//Upload Carousel Images
		$btn = $this->webDriver->findElement(
			By::id( 'multiple_image' )
		);
		$btn->click();

		$tab = $this->webDriver->findElement(
			By::linkText( 'Media Library' )
		);
		$tab->click();

		//Wait for media library to load
		$this->webDriver->wait( 10, 1000 )->until(
			WebDriverExpectedCondition::presenceOfElementLocated(
				By::cssSelector( 'ul.attachments' ) ) );
		$this->webDriver->manage()->timeouts()->implicitlyWait( 10 );


		//Select first image of media library
		$elements = $this->webDriver->findElements( By::cssSelector( 'li.attachment img' ) );

		$images_original = [];
		foreach ( $elements as $element ) {
			$image_path        = pathinfo( $element->getAttribute( 'src' ), PATHINFO_FILENAME );
			$images_original[] = substr( $image_path, 0, strrpos( $image_path, '-' ) );
		}


		$this->webDriver->findElement( By::cssSelector( 'li.attachment:first-child' ) )->click();
		$thirdimg = $this->webDriver->findElement( By::cssSelector( 'li.attachment:nth-child(3)' ) );
		//Press shift key while clicking on third image so that 3 images are selected
		$this->webDriver->action()->keyDown( null, WebDriverKeys::SHIFT )->click( $thirdimg )->keyUp( null, WebDriverKeys::SHIFT )->perform();
		$this->webDriver->findElement( By::className( 'media-button-select' ) )->click();

		//Insert block
		try {
			$insert = $this->webDriver->findElement( By::className( 'media-button-insert' ) );
			$insert->click();
		} catch ( Exception $e ) {
			$this->fail( '->Failed to insert element' );
		}

		//Publish content
		$this->webDriver->findElement( By::id( 'publish' ) )->click();

		//Wait to see successful message
		$this->webDriver->wait( 10, 1000 )->until(
			WebDriverExpectedCondition::visibilityOfElementLocated(
				By::id( 'message' ) ) );
		//Validate I see successful message
		try {
			$this->assertContains(
				'Page published', $this->webDriver->findElement(
				By::id( 'message' ) )->getText()
			);
		} catch ( Exception $e ) {
			$this->fail( '->Failed to publish content - no sucessful message after saving content' );
		}
		//Wait for saved changes to load
		usleep( 2000000 );
		//Go to page to validate page contains Articles Block
		$link = $this->webDriver->findElement(
			By::linkText( 'View page' )
		);
		$link->click();
		//If alert shows up asking to confirm leaving the page, confirm
		try {
			$this->webDriver->switchTo()->alert()->accept();
		} catch ( Exception $e ) {
		}


		try {
			$this->webDriver->findElement( By::className( 'carousel-wrap' ) );
			$this->webDriver->findElement( By::className( 'slide' ) );
			$titl_pg = $this->webDriver->findElement(
				By::cssSelector( '.carousel.slide h1' ) )->getText();

			$elements = $this->webDriver->findElements( By::cssSelector( '.carousel-inner .carousel-item img' ) );

			$images = [];
			foreach ( $elements as $element ) {
				$images[] = pathinfo( $element->getAttribute( 'src' ), PATHINFO_FILENAME );
			}
		} catch ( Exception $e ) {
			$this->fail( '->Some of the content created is not displayed in front end page' );
		}

		// Assert block title.
		$this->assertEquals( $titl, $titl_pg );

		// Assert image urls rendered are the same as the selected ones.
		foreach ( $images as $key => $image ) {
			$this->assertContains( $images_original[ $key ], $image );
		}

		// I log out after test
		$this->wpLogout();
		echo "\n-> Carousel block test PASSED";
	}

}
