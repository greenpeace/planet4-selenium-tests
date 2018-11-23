<?php

use Facebook\WebDriver\WebDriverBy as By;

class P4_GPMLAddFeaturedImage extends P4_login {

	public function testGPMLAddFeaturedImage() {

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
			$link = $this->driver->findElement( By::linkText( 'Add New' ) );
		} catch ( Exception $e ) {
			$this->fail( '->Could not find \'Add New\' button in Pages overview' );
		}
		$link->click();

		//Validate 'Set featured image' link is present on page.
		$this->assertContains(
			'Set featured image',
			$this->driver->findElement( By::id( 'set-post-thumbnail' ) )->getText()
		);

		//Enter title of page
		$field = $this->driver->findElement( By::id( 'title-prompt-text' ) );
		$field->click();
		$this->driver->getKeyboard()->sendKeys( 'Test automated - GP Media Library - Add featured image' );

		// Click on 'Set featured image' link on add new page.
		$add = $this->driver->findElement( By::id( 'set-post-thumbnail' ) );
		$add->click();

		// Click on 'Upload Files' tab of media model.
		$add = $this->driver->findElement( By::linkText( 'Upload Files' ) );
		$add->click();

		// Validate Media modal window is shown.
		$this->assertContains(
			'Featured Image',
			$this->driver->findElement( By::className( 'media-frame-title' ) )->getText()
		);

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

		// Wait for media library panel to load.
		$this->waitUntilVisible( '.ml-media-panel', 20, 500 );

		// Select first media image.
		$ml_image_name = $this->driver->findElement( By::cssSelector( 'li.attachment:first-child img' ) )->getAttribute( 'src' );
		$ml_image_name = explode( '.', basename( $ml_image_name ) );
		$ml_image_name = $ml_image_name[0];

		$img = $this->driver->findElement( By::cssSelector( 'li.attachment:first-child' ) );
		$img->click();

		$this->driver->findElement( By::className( 'ml-button-insert' ) )->click();

		$this->driver->wait( 30, 2000 )->until(
			function () {
				return $this->driver->executeScript( 'return jQuery.active == 0;' );
			}
		);

		$img = $this->driver->findElement( By::cssSelector( 'li.attachment:first-child' ) );
		$img->click();

		try {
			$this->driver->findElement( By::className( 'media-button-select' ) )->click();
		} catch ( Exception $e ) {
			$this->fail( '->Failed to select featured image' );
		}

		usleep( 1500000 );

		// Scroll up the page.
		$this->driver->executeScript( 'window.scrollTo(0, -250);' );

		// Publish content.
		$this->driver->findElement( By::id( 'publish' ) )->click();

		// Wait to see successful message.
		$this->waitUntilVisible( '#message', 30, 2000, '->Failed to save changes' );

		//Validate I see successful message
		try {
			$this->assertContains(
				'Page published',
				$this->driver->findElement( By::id( 'message' ) )->getText()
			);
		} catch ( Exception $e ) {
			$this->fail( '->Failed to publish content - no sucessful message after saving content' );
		}

		// Go to page to validate page contains added block.
		$link = $this->driver->findElement( By::linkText( 'View page' ) );
		$link->click();
		// If alert shows up asking to confirm leaving the page, confirm.

		try {
			$this->driver->switchTo()->alert()->accept();
		} catch ( Exception $e ) {
		}

		try {
			$post_image_name = $this->driver->findElement( By::xpath( '//meta[@name="twitter:image"]' ) )->getAttribute( 'content' );
		} catch ( Exception $e ) {
			$this->fail( '->Featured image not found in metadata page' );
		}

		$this->assertContains( $ml_image_name, $post_image_name, '', true );

		// I log out after test.
		$this->wpLogout();

		echo "\n-> GP Media library Add featured image test PASSED";
	}
}
