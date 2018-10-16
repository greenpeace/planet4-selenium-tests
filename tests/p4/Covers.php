<?php

use Facebook\WebDriver\WebDriverBy as By;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverKeys;

class P4_Covers extends P4_login {

	/**
	 * @var \RemoteWebDriver
	 */
	public function testCovers() {

		$covercards = [];
		//I log in
		try {
			$this->wpLogin();
		} catch ( Exception $e ) {
			$this->fail( '->Failed to log in, verify credentials and URL' );
		}

		//Go to pages and create content
		$this->driver->wait( 10, 500 )->until( WebDriverExpectedCondition::visibilityOfElementLocated( By::id( 'menu-pages' ) ) );
		$pages = $this->driver->findElement( By::id( 'menu-pages' ) );
		$pages->click();
		try {
			$link = $this->driver->findElement( By::linkText( 'Add New' ) );
		} catch ( Exception $e ) {
			$this->fail( '->Could not find \'Add New\' button in Pages overview' );
		}
		$link->click();

		//Validate button to add blocks to page is present
		$this->assertContains(
			'Add Page Element', $this->driver->findElement(
			By::className( 'shortcake-add-post-element' ) )->getText()
		);

		//Enter title of page
		$field = $this->driver->findElement( By::id( 'titlewrap' ) );
		$field->click();
		$this->driver->getKeyboard()->sendKeys( 'Test automated - Covers' );

		//Click on button to add blocks
		$add = $this->driver->findElement(
			By::className( 'shortcake-add-post-element' )
		);
		$add->click();

		//Validate blocks modal window is shown
		$this->assertContains(
			'Insert Post Element', $this->driver->findElement(
			By::className( 'media-frame-title' ) )->getText()
		);

		//Select TakeAction block
		try {
			$ta = $this->driver->findElement(
				By::cssSelector( 'li[data-shortcode=\'shortcake_covers\']' )
			);
			$ta->click();
		} catch ( Exception $e ) {
			$this->fail( '->Failed to select \'Take Action Covers\' post element' );
		}

		//Fill in Block fields
		$title = 'Take Action Block Test';
		$desc = 'This is content created by an automated test for testing take action covers block';
		$tg1  = 'ArcticSunrise';
		$tg2  = 'Oceans';

		$field = $this->driver->findElement( By::name( 'title' ) );
		$field->click();
		$this->driver->getKeyboard()->sendKeys( $title );

		$field = $this->driver->findElement( By::name( 'description' ) );
		$field->click();
		$this->driver->getKeyboard()->sendKeys( $desc );

		$field = $this->driver->findElement( By::className( 'select2-selection__rendered' ) );
		$field->click();
		$this->driver->getKeyboard()->sendKeys( $tg1 );

		// Wait until select options are visible.
		$this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(By::className('select2-result-selectable')));

		// Select suggestion.
		$this->driver->getKeyboard()->pressKey( WebDriverKeys::ENTER );
		$field = $this->driver->findElement( By::className( 'select2-selection__rendered' ) );
		$field->click();
		$this->driver->getKeyboard()->sendKeys( $tg2 );
		//Sleep for 3 seconds
		$this->driver->wait(10, 500)->until(WebDriverExpectedCondition::visibilityOfElementLocated(By::className('select2-result-selectable')));
		//Select suggestion
		$this->driver->getKeyboard()->pressKey( WebDriverKeys::ENTER );


		//Insert block
		try {
			$insert = $this->driver->findElement( By::className( 'media-button-insert' ) );
			$insert->click();
		} catch ( Exception $e ) {
			$this->fail( '->Failed to insert element' );
		}


		usleep( 3000000 );
		//Publish content
		$this->driver->findElement( By::id( 'publish' ) )->click();

		//Wait to see successful message
		$this->driver->wait( 10, 1000 )->until(
			WebDriverExpectedCondition::visibilityOfElementLocated(
				By::id( 'message' ) ) );
		//Validate I see successful message
		try {
			$this->assertContains( 'Page published', $this->driver->findElement( By::id( 'message' ) )->getText() );
		} catch ( Exception $e ) {
			$this->fail( '->Failed to publish content - no sucessful message after saving content' );
		}

		//Wait for saved changes to load
		usleep( 2000000 );
		//Go to page to validate page contains Articles Block
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
			$this->driver->findElement( By::className( 'covers-block' ) );
			$titl_pg = $this->driver->findElement(
				By::cssSelector( 'h2.page-section-header' ) )->getText();
			$desc_pg = $this->driver->findElement(
				By::cssSelector( 'p.page-section-description' ) )->getText();

			//Count how many cover cards were shown.
			$covercardsnum = count( $this->driver->findElements(
				By::cssSelector( '.covers-block .container .row .cover-card:enabled' ) ) );

			//Get all the tags for each cover card and saves them in array.
			for ( $i = 1; $i <= $covercardsnum; $i++ ) {
				$path = ".covers-block .container .row .cover-card:nth-child($i) a.cover-card-tag";
				$covercards[] = $this->driver->findElements( By::cssSelector( $path ) ) ;
			}
			$btntxt = $this->driver->findElement( By::cssSelector( '.cover-card:first-child .cover-card-btn' ) )->getText();
		} catch ( Exception $e ) {
			$this->fail( $e->getMessage() );
			$this->fail( '->Some of the content created is not displayed in front end page' );
		}
		$this->assertEquals( $title, $titl_pg );
		$this->assertEquals( $desc, $desc_pg );
		//Check if tags of each card contains specific tag.
		$ispresent = false;
		foreach ( $covercards as $covercard ) {
			foreach ( $covercard as $tag ) {
				$tag = $tag->getText();
				//Remove hashtag
				$tag = explode( '#', $tag );
				if ( $tag[1] == $tg1 ) {
					$ispresent = true;
				}
				if ( $tag[1] == $tg2 ) {
					$ispresent = true;
				}
			}
			if ( ! $ispresent ) {
				$this->fail( '->Specified tags are not shown in one or more of the cover cards' );
			}
		}

		echo "\n-> Covers block test PASSED";
	}
}
