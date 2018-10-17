<?php

use Facebook\WebDriver\WebDriverBy as By;


class P4_GPMLAddMedia extends P4_login {

	public function testGPMLAddMedia() {

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

		// Validate button to add blocks to page is present.
		$this->assertContains(
			'Add Media',
			$this->driver->findElement( By::className( 'add_media' ) )->getText()
		);

		// Enter title of page.
		$field = $this->driver->findElement( By::id( 'title-prompt-text' ) );
		$field->click();
		$this->driver->getKeyboard()->sendKeys( 'Test automated - GP media library - Add media' );

		// Click on button to add media.
		$add = $this->driver->findElement( By::className( 'add_media' ) );
		$add->click();

		// Click on 'GPI Media Library' link in media popup.
		$add = $this->driver->findElement( By::linkText( 'GPI Media Library' ) );
		$add->click();

		// Validate Media modal window is shown.
		$this->assertContains(
			'GPI Media Library',
			$this->driver->findElement( By::className( 'media-frame-title' ) )->getText()
		);

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

		// Switch to Iframe elements.
		$this->driver->switchTo()->frame(
			$this->driver->findElement( By::cssSelector( '.media-iframe iframe' ) )
		);

		usleep( 1000000 );

		// Wait for media library to load.
		$this->waitUntilVisible( '.ml-media-list', 40, 1000 );

		// Select first media image.
		$ml_image_name = $this->driver->findElement( By::cssSelector( 'li.attachment:first-child img' ) )->getAttribute( 'src' );
		$ml_image_name = explode( '.', basename( $ml_image_name ) );
		$ml_image_name = $ml_image_name[0];

		$img = $this->driver->findElement( By::cssSelector( 'li.attachment:first-child' ) );
		$img->click();
		$this->driver->findElement( By::className( 'ml-button-insert' ) )->click();

		// Switch back to default content.
		$this->driver->switchTo()->defaultContent();

		usleep( 5000000 );

		// Publish content.
		try {
			$this->driver->findElement( By::id( 'publish' ) )->click();
		} catch ( Exception $e ) {
			$this->fail( '->Failed to click publish button' );
		}

		// Wait to see successful message.
		$this->waitUntilVisible( '#message', 30, 2000, '->Failed to save changes' );

		// Validate I see successful message.
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
			$post_image_name = $this->driver->findElement( By::cssSelector( '.wp-caption img' ) )->getAttribute( 'src' );
			//Validate media is present on page or not.
			$this->driver->findElement( By::className( 'wp-caption' ) );
			$this->driver->findElement( By::className( 'wp-caption-text' ) );
		} catch ( Exception $e ) {
			$this->fail( '->Some of the content created is not displayed in front end page' );
		}

		$this->assertContains( $ml_image_name, $post_image_name, '', true );

		// I log out after test.
		$this->wpLogout();

		echo "\n-> GP Media library Add new media test PASSED";
	}
}
