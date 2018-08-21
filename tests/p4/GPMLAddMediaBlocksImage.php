<?php

use WebDriverBy as By;

// This class is needed to start the session and open/close the browser.
require_once __DIR__ . '/../wp-core/login.php';

class P4_GPMLAddMediaBlocksImage extends P4_login {

	public function testGPMLAddMediaBlocksImage() {
		// I log in.
		try {
			$this->wpLogin();
		} catch( Exception $e ) {
			$this->fail( '->Failed to log in, verify credentials and URL' );
		}

		// Go to pages and create content.
		$this->webDriver->wait( 3 );
		$pages = $this->webDriver->findElement( By::id( 'menu-pages' ) );
		$pages->click();

		try {
			$link = $this->webDriver->findElement(
				By::linkText('Add New')
			);
		} catch( Exception $e ) {
			$this->fail('->Could not find \'Add New\' button in Pages overview');
		}
		$link->click();

		// Validate button to add blocks to page is present.
		$this->assertContains(
			'Add Page Element',
			$this->webDriver->findElement( By::className( 'shortcake-add-post-element' ) )->getText()
		);

		// Enter title of page.
		$field	= $this->webDriver->findElement( By::id( 'title-prompt-text' ) );
		$field->click();
		$this->webDriver->getKeyboard()->sendKeys( 'Test automated - GPML image & Media block' );

		// Click on button to add blocks.
		$add = $this->webDriver->findElement(
			By::className('shortcake-add-post-element')
		);
		$add->click();

		// Validate blocks modal window is shown.
		$this->assertContains(
			'Insert Post Element',
			$this->webDriver->findElement( By::className( 'media-frame-title' ) )->getText()
		);

		// Select Media block.
		try {
			$media_block = $this->webDriver->findElement(
				By::cssSelector('li[data-shortcode="shortcake_media_block"]' )
			);
			$media_block->click();
		} catch( Exception $e ) {
			$this->fail('->Failed to select \'Media Block\' post element');
		}

		// Validate corresponding fields are present.
		try {
			$this->webDriver->findElement( By::id( 'attachment' ) );
		} catch( Exception $e ) {
			$this->fail( '->Fields corresponding to \'Media block\' not found' );
		}

		// Upload image.
		$this->webDriver->findElement(By::id('attachment'))->click();
		$this->webDriver->findElement(By::linkText('Upload Files'))->click();

		// Click on 'Upload From GPI Media Library' button.
		try {
			$add = $this->webDriver->findElement( By::className('switchtoml') );
		} catch( Exception $e ) {
			$this->fail( '->Could not find \'Upload From GPI Media Library\' button in media upload model' );
		}
		$add->click();

		// Wait for media content to load.
		usleep( 10000000 );

		// Wait for media library to load.
		$this->webDriver->wait( 10, 1000 )->until(
			WebDriverExpectedCondition::presenceOfElementLocated( By::cssSelector( '.ml-media-panel' ) )
		);

		$this->webDriver->manage()->timeouts()->implicitlyWait(10);

		$select_img = $this->webDriver->findElement( By::cssSelector( 'li.attachment:first-child img' ) );
		$action     = new WebDriverActions( $this->webDriver );
		$action->moveToElement( $select_img )->click()->perform();

		// Fetch media library image name.
		$ml_image_name = $this->webDriver->findElement( By::className( 'ml-url' ) )->getAttribute( 'value' );
		$ml_image_name = explode( '.', basename( $ml_image_name ) );
		$ml_image_name = $ml_image_name[0];

		$this->webDriver->executeScript( 'document.getElementById( "ml-button-insert" ).click();' );

		usleep( 5000000 );

		$this->webDriver->findElement( By::cssSelector( 'li.attachment:first-child' ) )->click();

		try {
			$this->webDriver->findElement( By::className( 'media-button-select' ) )->click();
		} catch( Exception $e ) {
			$this->fail( '->Failed to select Media blocks image' );
		}

		// Insert block.
		try {
			$insert = $this->webDriver->findElement( By::className( 'media-button-insert' ) );
			$insert->click();
		} catch( Exception $e ) {
			$this->fail( '->Failed to insert element' );
		}

		// Publish content.
		$this->webDriver->findElement( By::id( 'publish' ) )->click();

		// Wait to see successful message.
		$this->webDriver->wait( 10,  1000 )->until(
			WebDriverExpectedCondition::visibilityOfElementLocated( By::id( 'message' ) )
		);

		// Validate I see successful message.
		try {
			$this->assertContains(
				'Page published',
				$this->webDriver->findElement( By::id('message' ) )->getText()
			);
		} catch( Exception $e ) {
			$this->fail( '->Failed to publish content - no sucessful message after saving content' );
		}

		// Wait for saved changes to load.
		usleep( 1000000 );

		// Go to page to validate page contains Media Block.
		$link = $this->webDriver->findElement( By::linkText( 'View page' ) );
		$link->click();

		// If alert shows up asking to confirm leaving the page, confirm.
		try {
			$this->webDriver->switchTo()->alert()->accept();
		} catch( Exception $e ) {}

		try {
			$this->webDriver->findElement( By::className( 'block-media' ) );
			$post_image_name = $this->webDriver->findElement( By::cssSelector( '.block-media img' ) )->getattribute( 'src' );
		} catch( Exception $e ) {
			$this->fail( '->Some of the content created is not displayed in front end page' );
		}

		$this->assertContains( $ml_image_name, $post_image_name, '', true );

		// I log out after test.
		$this->wpLogout();

		echo "\n-> Add image from GP media library to Media block test PASSED";
	}
}
