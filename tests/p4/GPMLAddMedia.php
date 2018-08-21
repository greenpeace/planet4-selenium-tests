<?php

use WebDriverBy as By;

// This class is needed to start the session and open/close the browser.
require_once __DIR__ . '/../wp-core/login.php';

class P4_GPMLAddMedia extends P4_login {

	public function testGPMLAddMedia() {

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
			$link = $this->webDriver->findElement( By::linkText( 'Add New' ) );
		} catch( Exception $e ) {
			$this->fail( '->Could not find \'Add New\' button in Pages overview' );
		}
		$link->click();

		//Validate button to add blocks to page is present.
		$this->assertContains(
			'Add Media',
			$this->webDriver->findElement( By::className( 'add_media' ) )->getText()
		);

		//Enter title of page
		$field	= $this->webDriver->findElement( By::id('title-prompt-text') );
		$field->click();
		$this->webDriver->getKeyboard()->sendKeys( 'Test automated - GP media library - Add media' );

		// Click on button to add media.
		$add = $this->webDriver->findElement( By::className( 'add_media' ) );
		$add->click();

		// Click on 'GPI Media Library' link in media popup.
		$add = $this->webDriver->findElement( By::linkText( 'GPI Media Library' ) );
		$add->click();

		// Validate Media modal window is shown.
		$this->assertContains(
			'GPI Media Library',
			$this->webDriver->findElement( By::className('media-frame-title' ) )->getText()
		);

		// Wait for media content to load.
		$this->webDriver->wait( 30, 2000 )->until(
			function () {
				return $this->webDriver->executeScript( 'return jQuery.active == 0;' );
			}
		);

		// Switch to Iframe elements.
		$this->webDriver->switchTo()->frame(
			$this->webDriver->findElement( By::cssSelector( '.media-iframe iframe' ) )
		);

		// Wait for media library to load.
		$this->webDriver->wait(10, 1000)->until(
			WebDriverExpectedCondition::presenceOfElementLocated( By::cssSelector( '.ml-media-list' ) )
		);

		// Select first media image.
		$ml_image_name = $this->webDriver->findElement( By::cssSelector( 'li.attachment:first-child img' ) )->getAttribute( 'src' );
		$ml_image_name = explode( '.', basename( $ml_image_name ) );
		$ml_image_name = $ml_image_name[0];

		$img = $this->webDriver->findElement( By::cssSelector( 'li.attachment:first-child' ) );
		$img->click();
		$this->webDriver->findElement( By::className( 'ml-button-insert' ) )->click();

		// Switch back to default content.
		$this->webDriver->switchTo()->defaultContent();

		usleep( 5000000 );

		// Publish content.
		$this->webDriver->findElement( By::id('publish') )->click();

		// Wait to see successful message.
		$this->webDriver->wait( 10, 1000 )->until(
			WebDriverExpectedCondition::visibilityOfElementLocated( By::id( 'message' ) )
		);
		//Validate I see successful message
		try {
			$this->assertContains(
				'Page published',
				$this->webDriver->findElement( By::id( 'message' ) )->getText()
			);
		} catch( Exception $e ) {
			$this->fail( '->Failed to publish content - no sucessful message after saving content' );
		}

		// Wait for saved changes to load.
		$this->webDriver->wait( 30, 2000 )->until(
			function () {
				return $this->webDriver->executeScript( 'return jQuery.active == 0;' );
			}
		);

		// Go to page to validate page contains added block.
		$link = $this->webDriver->findElement( By::linkText( 'View page' ) );
		$link->click();
		// If alert shows up asking to confirm leaving the page, confirm.

		try {
			$this->webDriver->switchTo()->alert()->accept();
		} catch( Exception $e ){ }

		try {
			$post_image_name = $this->webDriver->findElement( By::cssSelector( '.wp-caption img' ) )->getAttribute( 'src' );
		    //Validate media is present on page or not.
			$this->webDriver->findElement( By::className( 'wp-caption' ) );
			$this->webDriver->findElement( By::className( 'wp-caption-text' ) );
		} catch( Exception $e ) {
			$this->fail( '->Some of the content created is not displayed in front end page' );
		}

		$this->assertContains( $ml_image_name, $post_image_name, '', true );

		// I log out after test.
		$this->wpLogout();

		echo "\n-> GP Media library Add new media test PASSED";
	}
}
