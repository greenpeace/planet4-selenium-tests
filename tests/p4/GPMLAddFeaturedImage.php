<?php

use WebDriverBy as By;

// This class is needed to start the session and open/close the browser.
require_once __DIR__ . '/../wp-core/login.php';

class P4_GPMLAddFeaturedImage extends P4_login {

	public function testGPMLAddFeaturedImage() {

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
			$link = $this->webDriver->findElement( By::linkText('Add New') );
		} catch( Exception $e ) {
			$this->fail( '->Could not find \'Add New\' button in Pages overview' );
		}
		$link->click();

		//Validate 'Set featured image' link is present on page.
		$this->assertContains(
			'Set featured image',
			$this->webDriver->findElement( By::id( 'set-post-thumbnail' ) )->getText()
		);

		//Enter title of page
		$field	= $this->webDriver->findElement( By::id('title-prompt-text') );
		$field->click();
		$this->webDriver->getKeyboard()->sendKeys( 'Test automated - GP Media Library - Add featured image' );

		// Click on 'Set featured image' link on add new page.
		$add = $this->webDriver->findElement( By::id('set-post-thumbnail') );
		$add->click();

		// Click on 'Upload Files' tab of media model.
		$add = $this->webDriver->findElement( By::linkText('Upload Files') );
		$add->click();

		// Validate Media modal window is shown.
		$this->assertContains(
			'Featured Image',
			$this->webDriver->findElement( By::className('media-frame-title' ) )->getText()
		);

		// Click on 'Upload From GPI Media Library' button.
		try {
			$add = $this->webDriver->findElement( By::className( 'switchtoml' ) );
		} catch( Exception $e ) {
			$this->fail( '->Could not find \'Upload From GPI Media Library\' button in media upload model' );
		}
		$add->click();

		// Wait for media content to load.
		try {
			$this->webDriver->wait( 30, 2000 )->until(
				function () {
					return $this->webDriver->executeScript( 'return jQuery.active == 0;' );
				}
			);
		} catch ( TimeOutException $e ) {
			$this->fail( '->Could not load GP media library before timeout expires' );
		} catch ( Exception $e ) {
			$this->fail( '->General Exception' );
		}

		// Wait for media library panel to load.
		$this->waitUntilVisible( '.ml-media-panel', 20 , 500 );

		// Select first media image.
		$ml_image_name = $this->webDriver->findElement( By::cssSelector( 'li.attachment:first-child img' ) )->getAttribute( 'src' );
		$ml_image_name = explode( '.', basename( $ml_image_name ) );
		$ml_image_name = $ml_image_name[0];

		$img = $this->webDriver->findElement( By::cssSelector( 'li.attachment:first-child' ) );
		$img->click();

		$this->webDriver->findElement( By::className( 'ml-button-insert' ) )->click();

		$this->webDriver->wait( 30, 2000 )->until(
			function () {
				return $this->webDriver->executeScript( 'return jQuery.active == 0;' );
			}
		);

		$img = $this->webDriver->findElement( By::cssSelector( 'li.attachment:first-child' ) );
		$img->click();

		try {
			$this->webDriver->findElement( By::className( 'media-button-select' ) )->click();
		} catch( Exception $e ) {
			$this->fail( '->Failed to select featured image' );
		}

		usleep(1500000 );

		// Scroll up the page.
		$this->webDriver->executeScript( 'window.scrollTo(0, -250);' );

		// Publish content.
		$this->webDriver->findElement( By::id( 'publish' ) )->click();

		// Wait to see successful message.
		$this->waitUntilVisible( '#message', 30 , 2000 , '->Failed to save changes' );

		//Validate I see successful message
		try {
			$this->assertContains(
				'Page published',
				$this->webDriver->findElement( By::id( 'message' ) )->getText()
			);
		} catch( Exception $e ) {
			$this->fail( '->Failed to publish content - no sucessful message after saving content' );
		}

		// Go to page to validate page contains added block.
		$link = $this->webDriver->findElement( By::linkText( 'View page' ) );
		$link->click();
		// If alert shows up asking to confirm leaving the page, confirm.

		try {
			$this->webDriver->switchTo()->alert()->accept();
		} catch( Exception $e ){ }

		try {
			$post_image_name = $this->webDriver->findElement( By::xpath('//meta[@name="twitter:image"]') )->getAttribute( 'content' );
		} catch( Exception $e ) {
			$this->fail( '->Featured image not found in metadata page' );
		}

		$this->assertContains( $ml_image_name, $post_image_name, '', true );

		// I log out after test.
		$this->wpLogout();

		echo "\n-> GP Media library Add featured image test PASSED";
	}
}
