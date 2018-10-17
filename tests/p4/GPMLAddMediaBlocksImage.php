<?php

use Facebook\WebDriver\WebDriverBy as By;
use Facebook\WebDriver\Interactions\WebDriverActions;
use Facebook\WebDriver\WebDriverExpectedCondition;

class P4_GPMLAddMediaBlocksImage extends P4_login {

	public function testGPMLAddMediaBlocksImage() {
		// I log in.
		try {
			$this->wpLogin();
		} catch ( Exception $e ) {
			$this->fail( '->Failed to log in, verify credentials and URL' );
		}

		// Go to pages and create content.
		$this->driver->wait( 3 );
		$pages = $this->driver->findElement( By::id( 'menu-pages' ) );
		$pages->click();

		try {
			$link = $this->driver->findElement(
				By::linkText( 'Add New' )
			);
		} catch ( Exception $e ) {
			$this->fail( '->Could not find \'Add New\' button in Pages overview' );
		}
		$link->click();

		// Validate button to add blocks to page is present.
		$this->assertContains(
			'Add Page Element',
			$this->driver->findElement( By::className( 'shortcake-add-post-element' ) )->getText()
		);

		// Enter title of page.
		$field = $this->driver->findElement( By::id( 'title-prompt-text' ) );
		$field->click();
		$this->driver->getKeyboard()->sendKeys( 'Test automated - GPML image & Media block' );

		// Click on button to add blocks.
		$add = $this->driver->findElement( By::className( 'shortcake-add-post-element' ) );
		$add->click();

		// Validate blocks modal window is shown.
		$this->assertContains(
			'Insert Post Element',
			$this->driver->findElement( By::className( 'media-frame-title' ) )->getText()
		);

		// Select Media block.
		try {
			$media_block = $this->driver->findElement(
				By::cssSelector( 'li[data-shortcode="shortcake_media_block"]' )
			);
			$media_block->click();
		} catch ( Exception $e ) {
			$this->fail( '->Failed to select \'Media Block\' post element' );
		}

		// Validate corresponding fields are present.
		try {
			$this->driver->findElement( By::id( 'attachment' ) );
		} catch ( Exception $e ) {
			$this->fail( '->Fields corresponding to \'Media block\' not found' );
		}

		// Upload image.
		$this->driver->findElement( By::id( 'attachment' ) )->click();
		$this->driver->findElement( By::linkText( 'Upload Files' ) )->click();

		// Click on 'Upload From GPI Media Library' button.
		try {
			$add = $this->driver->findElement( By::className( 'switchtoml' ) );
		} catch ( Exception $e ) {
			$this->fail( '->Could not find \'Upload From GPI Media Library\' button in media upload model' );
		}
		$add->click();

		// Wait for media content to load.
		try {
			$this->driver->wait( 30, 2000 )->until(
				function () {
					return $this->driver->executeScript( 'return jQuery.active == 0;' );
				}
			);
		} catch ( TimeOutException $e ) {
			$this->fail( '->Could not load GP media library before timeout expires' );
		} catch ( Exception $e ) {
			$this->fail( '->General Exception' );
		}

		// Wait for media library to load.
		$this->waitUntilVisible( '.ml-media-panel', 10, 500 );

		$this->driver->manage()->timeouts()->implicitlyWait( 10 );

		$select_img = $this->driver->findElement( By::cssSelector( 'li.attachment:first-child img' ) );
		$action     = new WebDriverActions( $this->driver );
		$action->moveToElement( $select_img )->click()->perform();

		// Fetch media library image name.
		$ml_image_name = $this->driver->findElement( By::className( 'ml-url' ) )->getAttribute( 'value' );
		$ml_image_name = explode( '.', basename( $ml_image_name ) );
		$ml_image_name = $ml_image_name[0];

		$this->driver->executeScript( 'document.getElementById( "ml-button-insert" ).click();' );

		$this->waitUntilVisible( '.attachment-filters', 30, 2000 );

		$this->driver->findElement( By::cssSelector( 'li.attachment:first-child' ) )->click();

		try {
			$this->driver->findElement( By::className( 'media-button-select' ) )->click();
		} catch ( Exception $e ) {
			$this->fail( '->Failed to select Media blocks image' );
		}

		// Insert block.
		try {
			$insert = $this->driver->findElement( By::className( 'media-button-insert' ) );
			$insert->click();
		} catch ( Exception $e ) {
			$this->fail( '->Failed to insert element' );
		}

		// Publish content.
		$this->driver->findElement( By::id( 'publish' ) )->click();

		// Wait to see successful message.
		$this->driver->wait( 10, 1000 )->until(
			WebDriverExpectedCondition::visibilityOfElementLocated( By::id( 'message' ) )
		);

		// Validate I see successful message.
		try {
			$this->assertContains(
				'Page published',
				$this->driver->findElement( By::id( 'message' ) )->getText()
			);
		} catch ( Exception $e ) {
			$this->fail( '->Failed to publish content - no sucessful message after saving content' );
		}

		// Wait for saved changes to load.
		$this->waitUntilVisible( '#message', 30, 2000, '->Failed to save changes' );

		// Go to page to validate page contains Media Block.
		$link = $this->driver->findElement( By::linkText( 'View page' ) );
		$link->click();

		// If alert shows up asking to confirm leaving the page, confirm.
		try {
			$this->driver->switchTo()->alert()->accept();
		} catch ( Exception $e ) {
		}

		try {
			$this->driver->findElement( By::className( 'block-media' ) );
			$post_image_name = $this->driver->findElement( By::cssSelector( '.block-media img' ) )->getattribute( 'src' );
		} catch ( Exception $e ) {
			$this->fail( '->Some of the content created is not displayed in front end page' );
		}

		$this->assertContains( $ml_image_name, $post_image_name, '', true );

		// I log out after test.
		$this->wpLogout();

		echo "\n-> Add image from GP media library to Media block test PASSED";
	}
}
