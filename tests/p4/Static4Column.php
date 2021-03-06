<?php

use Facebook\WebDriver\WebDriverBy as By;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverKeys;

class P4_Static4Column extends P4_login {

	public function testStatic4Column() {

		try {
			$this->wpLogin();
		} catch ( Exception $e ) {
			$this->fail( '->Failed to log in, verify credentials and URL' );
		}

		// Go to pages and create content
		$this->driver->wait( 3 );
		$pages = $this->driver->findElement( By::id( 'menu-pages' ) );
		$pages->click();
		try {
			$link = $this->driver->findElement(
				By::linkText( 'Add New' )
			);
		} catch ( Exception $e ) {
			$this->fail( '->Could not find "Add New" button in Pages overview' );
		}
		$link->click();

		// Validate button to add blocks to page is present
		$this->assertContains(
			'Add Page Element', $this->driver->findElement(
			By::className( 'shortcake-add-post-element' ) )->getText()
		);

		// Enter title of page
		$field = $this->driver->findElement( By::id( 'title-prompt-text' ) );
		$field->click();
		$this->driver->getKeyboard()->sendKeys( 'Test automated - Static 4 Column' );

		// Click on button to add blocks
		$add = $this->driver->findElement( By::className( 'shortcake-add-post-element' ) );
		$add->click();

		// Validate blocks modal window is shown
		$this->assertContains( 'Insert Post Element', $this->driver->findElement( By::className( 'media-frame-title' ) )->getText() );

		// Select Static 4 column block
		try {
			$ta = $this->driver->findElement( By::cssSelector( 'li[data-shortcode="shortcake_static_four_column"]' ) );
			$ta->click();
		} catch ( Exception $e ) {
			$this->fail( '->Failed to select "Static Four Column" post element' );
		}

		// Validate corresponding fields are present
		try {
			$this->driver->findElement( By::id( 'attachment_1' ) );
			$this->driver->findElement( By::name( 'title_1' ) );
			$this->driver->findElement( By::name( 'description_1' ) );
			$this->driver->findElement( By::name( 'link_text_1' ) );
			$this->driver->findElement( By::name( 'link_url_1' ) );
			$this->driver->findElement( By::id( 'attachment_2' ) );
			$this->driver->findElement( By::name( 'link_url_2' ) );
			$this->driver->findElement( By::name( 'link_text_2' ) );
			$this->driver->findElement( By::name( 'description_2' ) );
			$this->driver->findElement( By::name( 'title_2' ) );
			$this->driver->findElement( By::id( 'attachment_3' ) );
			$this->driver->findElement( By::name( 'title_3' ) );
			$this->driver->findElement( By::name( 'description_3' ) );
			$this->driver->findElement( By::name( 'link_text_3' ) );
			$this->driver->findElement( By::name( 'link_url_3' ) );
			$this->driver->findElement( By::id( 'attachment_4' ) );
			$this->driver->findElement( By::name( 'title_4' ) );
			$this->driver->findElement( By::name( 'description_4' ) );
			$this->driver->findElement( By::name( 'link_text_4' ) );
			$this->driver->findElement( By::name( 'link_url_4' ) );
		} catch ( Exception $e ) {
			$this->fail( '->Fields corresponding to "Static Four Column" block not found' );
		}

		// ----- FILL IN FIELDS FOR COLUMN 1
		// Define test content
		$title1       = 'Column 1 Test';
		$description1 = 'This is test content created by an automated test for testing content in column 1 of static 4 column block';
		$linktext1    = 'Detox Germany';
		$linkurl1     = 'http://www.detox-outdoor.org/de-CH/';
		$this->driver->findElement( By::id( 'attachment_1' ) )->click();
		$this->driver->findElement( By::linkText( 'Media Library' ) )->click();
		// Wait for media library to load
		$this->driver->wait( 10, 1000 )->until(
			WebDriverExpectedCondition::presenceOfElementLocated(
				By::cssSelector( 'ul.attachments' ) ) );
		$this->driver->manage()->timeouts()->implicitlyWait( 10 );
		// Select first image of media library
		$srcfirstchild = explode( '-', $this->driver->findElement(
			By::cssSelector( 'li.attachment:first-child img' ) )->getAttribute( 'src' ) );
		$srcfirstchild = $srcfirstchild[1];
		$this->driver->findElement( By::cssSelector( 'li.attachment:first-child' ) )->click();
		// Get info needed to upload image 2, 3 and 4
		$img2      = $this->driver->findElement(
			By::cssSelector( 'li.attachment:nth-child(2)' ) )->getAttribute( 'data-id' );
		$src2child = explode( '-', $this->driver->findElement(
			By::cssSelector( 'li.attachment:nth-child(2) img' ) )->getAttribute( 'src' ) );
		$src2child = $src2child[1];
		$img3      = $this->driver->findElement(
			By::cssSelector( 'li.attachment:nth-child(3)' ) )->getAttribute( 'data-id' );
		$src3child = explode( '-', $this->driver->findElement(
			By::cssSelector( 'li.attachment:nth-child(3) img' ) )->getAttribute( 'src' ) );
		$src3child = $src3child[1];
		$img4      = $this->driver->findElement(
			By::cssSelector( 'li.attachment:nth-child(4)' ) )->getAttribute( 'data-id' );
		$src4child = explode( '-', $this->driver->findElement(
			By::cssSelector( 'li.attachment:nth-child(4) img' ) )->getAttribute( 'src' ) );
		$src4child = $src4child[1];

		// Add image
		$this->driver->findElement( By::className( 'media-button-select' ) )->click();
		// Fill in rest of fields
		$this->driver->findElement( By::name( 'title_1' ) )->click();
		$this->driver->getKeyboard()->sendKeys( $title1 );
		$this->driver->findElement( By::name( 'description_1' ) )->click();
		$this->driver->getKeyboard()->sendKeys( $description1 );
		$this->driver->findElement( By::name( 'link_text_1' ) )->click();
		$this->driver->getKeyboard()->sendKeys( $linktext1 );
		$this->driver->findElement( By::name( 'link_url_1' ) )->click();
		$this->driver->getKeyboard()->sendKeys( $linkurl1 );

		// ----- FILL IN FIELDS FOR COLUMN 2
		// Define test content
		$title2       = 'Column 2 Test';
		$description2 = 'This is test content created by an automated test for testing content in column 2 of static 4 column block';
		$linktext2    = 'Detox Italy';
		$linkurl2     = 'http://www.detox-outdoor.org/it-IT';
		// Fill in rest of fields
		$this->driver->findElement( By::name( 'title_2' ) )->click();
		$this->driver->getKeyboard()->sendKeys( $title2 );
		$this->driver->findElement( By::name( 'description_2' ) )->click();
		$this->driver->getKeyboard()->sendKeys( $description2 );
		$this->driver->findElement( By::name( 'link_text_2' ) )->click();
		$this->driver->getKeyboard()->sendKeys( $linktext2 );
		$this->driver->findElement( By::name( 'link_url_2' ) )->click();
		$this->driver->getKeyboard()->sendKeys( $linkurl2 );

		// ----- FILL IN FIELDS FOR COLUMN 3
		// Define test content
		$title3       = 'Column 3 Test';
		$description3 = 'This is test content created by an automated test for testing content in column 3 of static 4 column block';
		$linktext3    = 'Detox France';
		$linkurl3     = 'http://www.detox-outdoor.org/fr-CH';
		// Fill in rest of fields
		$this->driver->findElement( By::name( 'title_3' ) )->click();
		$this->driver->getKeyboard()->sendKeys( $title3 );
		$this->driver->findElement( By::name( 'description_3' ) )->click();
		$this->driver->getKeyboard()->sendKeys( $description3 );
		$this->driver->findElement( By::name( 'link_text_3' ) )->click();
		$this->driver->getKeyboard()->sendKeys( $linktext3 );
		$this->driver->findElement( By::name( 'link_url_3' ) )->click();
		$this->driver->getKeyboard()->sendKeys( $linkurl3 );

		// ----- FILL IN FIELDS FOR COLUMN 4
		// Define test content
		$title4       = 'Column 4 Test';
		$description4 = 'This is test content created by an automated test for testing content in column 4 of static 4 column block';
		$linktext4    = 'Detox Finland';
		$linkurl4     = 'http://www.detox-outdoor.org/fi';
		// Fill in rest of fields
		$this->driver->findElement( By::name( 'title_4' ) )->click();
		$this->driver->getKeyboard()->sendKeys( $title4 );
		$this->driver->findElement( By::name( 'description_4' ) )->click();
		$this->driver->getKeyboard()->sendKeys( $description4 );
		$this->driver->findElement( By::name( 'link_text_4' ) )->click();
		$this->driver->getKeyboard()->sendKeys( $linktext4 );
		$this->driver->findElement( By::name( 'link_url_4' ) )->click();
		$this->driver->getKeyboard()->sendKeys( $linkurl4 );

		// Insert block
		try {
			$insert = $this->driver->findElement(
				By::className( 'media-button-insert' )
			);
			$insert->click();
		} catch ( Exception $e ) {
			$this->fail( '->Failed to insert element' );
		}

		// Edit WYSIWYG text to add images 2, 3 and 4
		$this->driver->findElement( By::id( 'content-html' ) )->click();
		$this->driver->findElement( By::id( 'content' ) )->click();
		$this->driver->action()->keyDown( null, WebDriverKeys::CONTROL )->perform();
		$this->driver->getKeyboard()->pressKey( WebDriverKeys::END );
		$this->driver->action()->keyUp( null, WebDriverKeys::CONTROL )->perform();
		$this->driver->getKeyboard()->pressKey( WebDriverKeys::BACKSPACE );
		$this->driver->getKeyboard()->pressKey( WebDriverKeys::BACKSPACE );
		$this->driver->getKeyboard()->sendKeys( 'attachment_2=' . $img2 . ' attachment_3=' . $img3 . ' attachment_4=' . $img4 . '/]' );

		// Publish content
		$this->driver->findElement(
			By::id( 'publish' )
		)->click();
		// Wait to see successful message
		$this->driver->wait( 10, 1000 )->until(
			WebDriverExpectedCondition::visibilityOfElementLocated(
				By::id( 'message' ) ) );
		// Validate I see successful message
		try {
			$this->assertContains(
				'Page published', $this->driver->findElement(
				By::id( 'message' ) )->getText()
			);
		} catch ( Exception $e ) {
			$this->fail( '->Failed to publish content - no sucessful message after saving content' );
		}
		// Wait for saved changes to load
		usleep( 2000000 );
		// Go to page to validate page contains added block
		$link = $this->driver->findElement(
			By::linkText( 'View page' )
		);
		$link->click();
		// If alert shows up asking to confirm leaving the page, confirm
		try {
			$this->driver->switchTo()->alert()->accept();
		} catch ( Exception $e ) {
		}

		$this->driver->findElement( By::className( 'four-column' ) );
		// Get info of posted images
		$srcimg1         = explode( '-', $this->driver->findElement(
			By::cssSelector( 'div.four-column-wrap:nth-child(1) img' ) )->getAttribute( 'src' ) );
		$srcimg1         = $srcimg1[1];
		$title1_pg       = $this->driver->findElement(
			By::cssSelector( 'div.four-column-wrap:nth-child(1) .four-column-information h5' ) )->getText();
		$description1_pg = $this->driver->findElement(
			By::cssSelector( 'div.four-column-wrap:nth-child(1) .four-column-information p' ) )->getText();
		$linktext1_pg    = $this->driver->findElement(
			By::xPath( '//section["four-column"]/div/div/div[1]/div[2]/a' ) )->getText();
		$linkurl1_pg     = $this->driver->findElement(
			By::cssSelector( 'div.four-column-wrap:nth-child(1) .four-column-information a' ) )->getAttribute( 'href' );
		$srcimg2         = explode( '-', $this->driver->findElement(
			By::cssSelector( 'div.four-column-wrap:nth-child(2) img' ) )->getAttribute( 'src' ) );
		$srcimg2         = $srcimg2[1];
		$title2_pg       = $this->driver->findElement(
			By::cssSelector( 'div.four-column-wrap:nth-child(2) .four-column-information h5' ) )->getText();
		$description2_pg = $this->driver->findElement(
			By::cssSelector( 'div.four-column-wrap:nth-child(2) .four-column-information p' ) )->getText();
		$linktext2_pg    = $this->driver->findElement(
			By::xPath( '//section["four-column"]/div/div/div[2]/div[2]/a' ) )->getText();
		$linkurl2_pg     = $this->driver->findElement(
			By::cssSelector( 'div.four-column-wrap:nth-child(2) .four-column-information a' ) )->getAttribute( 'href' );
		$srcimg3         = explode( '-', $this->driver->findElement(
			By::cssSelector( 'div.four-column-wrap:nth-child(3) img' ) )->getAttribute( 'src' ) );
		$srcimg3         = $srcimg3[1];
		$title3_pg       = $this->driver->findElement(
			By::cssSelector( 'div.four-column-wrap:nth-child(3) .four-column-information h5' ) )->getText();
		$description3_pg = $this->driver->findElement(
			By::cssSelector( 'div.four-column-wrap:nth-child(3) .four-column-information p' ) )->getText();
		$linktext3_pg    = $this->driver->findElement(
			By::xPath( '//section["four-column"]/div/div/div[3]/div[2]/a' ) )->getText();
		$linkurl3_pg     = $this->driver->findElement(
			By::cssSelector( 'div.four-column-wrap:nth-child(3) .four-column-information a' ) )->getAttribute( 'href' );
		$srcimg4         = explode( '-', $this->driver->findElement(
			By::cssSelector( 'div.four-column-wrap:nth-child(4) img' ) )->getAttribute( 'src' ) );
		$srcimg4         = $srcimg4[1];
		$title4_pg       = $this->driver->findElement(
			By::cssSelector( 'div.four-column-wrap:nth-child(4) .four-column-information h5' ) )->getText();
		$description4_pg = $this->driver->findElement(
			By::cssSelector( 'div.four-column-wrap:nth-child(4) .four-column-information p' ) )->getText();
		$linktext4_pg    = $this->driver->findElement(
			By::xPath( '//section["four-column"]/div/div/div[4]/div[2]/a' ) )->getText();
		$linkurl4_pg     = $this->driver->findElement(
			By::cssSelector( 'div.four-column-wrap:nth-child(4) .four-column-information a' ) )->getAttribute( 'href' );

		// Validate column 1 fields
		$this->assertContains( $srcimg1, $srcfirstchild );
		$this->assertEquals( $title1, $title1_pg );
		$this->assertEquals( $description1, $description1_pg );
		$this->assertEquals( $linktext1, $linktext1_pg );
		$this->assertEquals( $linkurl1, $linkurl1_pg );
		// Validate column 2 fields
		$this->assertContains( $srcimg2, $src2child );
		$this->assertEquals( $title2, $title2_pg );
		$this->assertEquals( $description2, $description2_pg );
		$this->assertEquals( $linktext2, $linktext2_pg );
		$this->assertEquals( $linkurl2, $linkurl2_pg );
		// Validate column 3 fields
		$this->assertContains( $srcimg3, $src3child );
		$this->assertEquals( $title3, $title3_pg );
		$this->assertEquals( $description3, $description3_pg );
		$this->assertEquals( $linktext3, $linktext3_pg );
		$this->assertEquals( $linkurl3, $linkurl3_pg );
		// Validate column 4 fields
		$this->assertContains( $srcimg4, $src4child );
		$this->assertEquals( $title4, $title4_pg );
		$this->assertEquals( $description4, $description4_pg );
		$this->assertEquals( $linktext4, $linktext4_pg );
		$this->assertEquals( $linkurl4, $linkurl4_pg );

		echo "\n-> Static four column block test PASSED";
	}
}
