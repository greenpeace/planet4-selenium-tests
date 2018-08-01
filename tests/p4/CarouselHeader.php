<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../wp-core/login.php';

class P4_CarouselHeader extends P4_login {

	/**
	 * @var \RemoteWebDriver
	 */
	public function testCarouselHEader() {

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
		$this->driver->getKeyboard()->sendKeys( 'Test automated - Carousel Header' );

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

		//Select Carousel Header block
		try {
			$ta = $this->driver->findElement(
				WebDriverBy::cssSelector( "li[data-shortcode='shortcake_carousel_header']" )
			);
			$ta->click();
		} catch ( Exception $e ) {
			$this->fail( "->Failed to select 'Carousel Header' post element" );
		}

		//Validate corresponding fields are present
		try {
			$this->driver->findElement( WebDriverBy::id( "image_1" ) );
			$this->driver->findElement( WebDriverBy::name( "header_1" ) );
			$this->driver->findElement( WebDriverBy::name( "subheader_1" ) );
			$this->driver->findElement( WebDriverBy::name( "description_1" ) );
			$this->driver->findElement( WebDriverBy::name( "link_text_1" ) );
			$this->driver->findElement( WebDriverBy::name( "link_url_1" ) );
			$this->driver->findElement( WebDriverBy::id( "image_2" ) );
			$this->driver->findElement( WebDriverBy::name( "header_2" ) );
			$this->driver->findElement( WebDriverBy::name( "subheader_2" ) );
			$this->driver->findElement( WebDriverBy::name( "description_2" ) );
			$this->driver->findElement( WebDriverBy::name( "link_text_2" ) );
			$this->driver->findElement( WebDriverBy::name( "link_url_2" ) );
			$this->driver->findElement( WebDriverBy::id( "image_3" ) );
			$this->driver->findElement( WebDriverBy::name( "header_3" ) );
			$this->driver->findElement( WebDriverBy::name( "subheader_3" ) );
			$this->driver->findElement( WebDriverBy::name( "description_3" ) );
			$this->driver->findElement( WebDriverBy::name( "link_text_3" ) );
			$this->driver->findElement( WebDriverBy::name( "link_url_3" ) );
			$this->driver->findElement( WebDriverBy::id( "image_4" ) );
			$this->driver->findElement( WebDriverBy::name( "header_4" ) );
			$this->driver->findElement( WebDriverBy::name( "subheader_4" ) );
			$this->driver->findElement( WebDriverBy::name( "description_4" ) );
			$this->driver->findElement( WebDriverBy::name( "link_text_4" ) );
			$this->driver->findElement( WebDriverBy::name( "link_url_4" ) );
		} catch ( Exception $e ) {
			$this->fail( "->Fields corresponding to 'Carousel Header' block not found" );
		}


		//---Fill in fields for slide 1
		$titl1    = 'Header 1 Test';
		$subtitl1 = 'Subheader 1 Test';
		$desc1    = 'This is test content created by an automated test for testing content in slide 1 of carousel header block';
		$blink1   = 'Detox Germany';
		$burl1    = 'http://www.detox-outdoor.org/de-CH/';
		$this->driver->findElement( WebDriverBy::id( 'image_1' ) )->click();
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
		//Get info needed to upload image 2
		$img2      = $this->driver->findElement(
			WebDriverBy::cssSelector( "li.attachment:nth-child(2)" ) )->getAttribute( 'data-id' );
		$tmp       = explode( "-", $this->driver->findElement(
			WebDriverBy::cssSelector( "li.attachment:nth-child(2) img" ) )->getAttribute( 'src' ) );
		$src2child = $tmp[1];
		$this->driver->findElement( WebDriverBy::cssSelector( "li.attachment:first-child" ) )->click();
		$this->driver->findElement( WebDriverBy::className( "media-button-select" ) )->click();
		//Fill in rest of fields
		$this->driver->findElement( WebDriverBy::name( 'header_1' ) )->click();
		$this->driver->getKeyboard()->sendKeys( "$titl1" );
		$this->driver->findElement( WebDriverBy::name( 'subheader_1' ) )->click();
		$this->driver->getKeyboard()->sendKeys( "$subtitl1" );
		$this->driver->findElement( WebDriverBy::name( 'description_1' ) )->click();
		$this->driver->getKeyboard()->sendKeys( "$desc1" );
		$this->driver->findElement( WebDriverBy::name( 'link_text_1' ) )->click();
		$this->driver->getKeyboard()->sendKeys( "$blink1" );
		$this->driver->findElement( WebDriverBy::name( 'link_url_1' ) )->click();
		$this->driver->getKeyboard()->sendKeys( "$burl1" );

		//---Fill in fields for slide 2
		$titl2    = 'Header 2 Test';
		$subtitl2 = 'Subheader 2 Test';
		$desc2    = 'This is test content created by an automated test for testing content in slide 2 of carousel header block';
		$blink2   = 'Detox Italy';
		$burl2    = 'http://www.detox-outdoor.org/it-IT/';
		//Fill in rest of fields
		$this->driver->findElement( WebDriverBy::name( 'header_2' ) )->click();
		$this->driver->getKeyboard()->sendKeys( "$titl2" );
		$this->driver->findElement( WebDriverBy::name( 'subheader_2' ) )->click();
		$this->driver->getKeyboard()->sendKeys( "$subtitl2" );
		$this->driver->findElement( WebDriverBy::name( 'description_2' ) )->click();
		$this->driver->getKeyboard()->sendKeys( "$desc2" );
		$this->driver->findElement( WebDriverBy::name( 'link_text_2' ) )->click();
		$this->driver->getKeyboard()->sendKeys( "$blink2" );
		$this->driver->findElement( WebDriverBy::name( 'link_url_2' ) )->click();
		$this->driver->getKeyboard()->sendKeys( "$burl2" );

		//Insert block
		try {
			$insert = $this->driver->findElement(
				WebDriverBy::className( 'media-button-insert' )
			);
			$insert->click();
		} catch ( Exception $e ) {
			$this->fail( '->Failed to insert element' );
		}

		//Edit WYSIWYG text to add image 2 and 3
		$this->driver->findElement( WebDriverBy::id( "content-html" ) )->click();
		$this->driver->findElement( WebDriverBy::id( "content" ) )->click();
		$this->driver->getKeyboard()->pressKey( WebDriverKeys::ARROW_RIGHT );
		$this->driver->getKeyboard()->pressKey( WebDriverKeys::BACKSPACE );
		$this->driver->getKeyboard()->pressKey( WebDriverKeys::BACKSPACE );
		$this->driver->getKeyboard()->sendKeys( "image_2=$img2 /]" );

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
		//Go to page to validate page contains Articles Block
		$link = $this->driver->findElement(
			WebDriverBy::linkText( 'View page' )
		);
		$link->click();
		//If alert shows up asking to confirm leaving the page, confirm
		try {
			$this->driver->switchTo()->alert()->accept();
		} catch ( Exception $e ) {
		}
		//Validate elements from first slide
		try {
			$this->driver->findElement( WebDriverBy::className( "carousel-header" ) );
			//Get image source and remove format extension so that we can compare it to the thumbnail src
			$tmp         = explode( "-", $this->driver->findElement(
				WebDriverBy::cssSelector( '#carousel-wrapper-header .carousel-item.active img' ) )->getAttribute( 'src' ) );
			$srcimg      = $tmp[1];
			$tmp         = explode( "-", $this->driver->findElement(
				WebDriverBy::cssSelector( '#carousel-wrapper-header .carousel-item:nth-child(2) img' ) )->getAttribute( 'src' ) );
			$srcimg2     = $tmp[1];
			$titl1_pg    = $this->driver->findElement(
				WebDriverBy::cssSelector( ".carousel-item:nth-child(1) .carousel-caption .main-header h2" ) )->getText();
			$subtitl1_pg = $this->driver->findElement(
				WebDriverBy::cssSelector( ".carousel-item:nth-child(1) .carousel-caption .main-header h3" ) )->getText();
			$desc1_pg    = $this->driver->findElement(
				WebDriverBy::cssSelector( ".carousel-item:nth-child(1) .carousel-caption .main-header p" ) )->getText();
			$blink1_pg   = $this->driver->findElement(
				WebDriverBy::cssSelector( ".carousel-item:nth-child(1) .carousel-caption .main-header .action-button" ) )->getText();
			$burl1_pg    = $this->driver->findElement(
				WebDriverBy::cssSelector( ".carousel-item:nth-child(1) .carousel-caption .main-header .action-button a" ) )->getAttribute( 'href' );
			//Change to next slide
			$this->driver->findElement(
				WebDriverBy::className( "carousel-control-next-icon" ) )->click();
			$titl2_pg    = $this->driver->findElement(
				WebDriverBy::cssSelector( ".carousel-item:nth-child(2) .carousel-caption .main-header h2" ) )->getText();
			$subtitl2_pg = $this->driver->findElement(
				WebDriverBy::cssSelector( ".carousel-item:nth-child(2) .carousel-caption .main-header h3" ) )->getText();
			$desc2_pg    = $this->driver->findElement(
				WebDriverBy::cssSelector( ".carousel-item:nth-child(2) .carousel-caption .main-header p" ) )->getText();
			$blink2_pg   = $this->driver->findElement(
				WebDriverBy::cssSelector( ".carousel-item:nth-child(2) .carousel-caption .main-header .action-button" ) )->getText();
			$burl2_pg    = $this->driver->findElement(
				WebDriverBy::cssSelector( ".carousel-item:nth-child(2) .carousel-caption .main-header .action-button a" ) )->getAttribute( 'href' );
		} catch ( Exception $e ) {
			$this->fail( "->Some of the content created is not displayed in front end page" );
		}

		$this->assertEquals( "$titl1", "$titl1_pg" );
		$this->assertEquals( "$subtitl1", "$subtitl1_pg" );
		$this->assertEquals( "$desc1", "$desc1_pg" );
		$this->assertContains( "$srcfirstchild", "$srcimg" );
		$this->assertEquals( strtoupper( $blink1 ), "$blink1_pg" );
		$this->assertEquals( "$burl1", "$burl1_pg" );
		$this->assertEquals( "$titl2", "$titl2_pg" );
		$this->assertEquals( "$subtitl2", "$subtitl2_pg" );
		$this->assertEquals( "$desc2", "$desc2_pg" );
		$this->assertContains( "$src2child", "$srcimg2" );
		$this->assertEquals( strtoupper( $blink2 ), "$blink2_pg" );
		$this->assertEquals( "$burl2", "$burl2_pg" );

		echo "\n-> Carousel header block test PASSED";
	}

}
