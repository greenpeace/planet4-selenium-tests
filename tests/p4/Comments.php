<?php

use Facebook\WebDriver\WebDriverBy as By;

class P4_Comments extends P4_login {

	/**
	 * @var \RemoteWebDriver
	 */
	public function testComments() {

		//I log in
		try {
			$this->wpLogin();
		} catch ( Exception $e ) {
			$this->fail( '->Failed to log in, verify credentials and URL' );
		}
		$cmtid   = rand( 1, 100 );
		$comment = "This is a demo comment written and posted by an automated test - $cmtid";

		// Go to homepage.
		$this->driver->wait( 3 );
		$pages = $this->driver->findElement(
			By::id( 'wp-admin-bar-site-name' )
		);
		$pages->click();

		// Validate user lands in homepage.
		$this->driver->findElement(
			By::cssSelector( '.home.page-template-default.page' )
		);

		// Scroll down to news section.
		$element = $this->driver->findElement(
			By::cssSelector( '.article-listing.page-section .article-list-section .article-list-item-body' )
		);
		$element->getLocationOnScreenOnceScrolledIntoView();

		// Look for post.
		$post = $this->driver->findElement(
			By::cssSelector( '.article-listing.page-section .article-list-section .article-list-item-body .article-list-item-headline a' )
		);
		$post->click();

		// Wait for page to load.
		usleep( 2000000 );

		//Verify user lands in post.
		$this->driver->findElement( By::cssSelector( '.post-template-default.single.single-post' ) );
		$this->driver->findElement( By::cssSelector( '.comments-block' ) );
		$this->driver->findElement( By::id( 'commentform' ) );

		//If usabilia window pops up, close it.
		try {
			$usabilia = $this->driver->findElement( By::xpath( '/html/body/div[10]/div/iframe' ) );
			$this->driver->switchTo()->frame( $usabilia );
			$this->driver->findElement( By::id( 'close' ) )->click();
			$this->driver->switchTo()->activeElement();
		} catch ( Exception $e ) {
		}

		// Write comment.
		$this->driver->findElement( By::cssSelector( '#commentform .form-group #comment' ) )->click();
		$this->driver->getKeyboard()->sendKeys( $comment );

		// Scroll down.
		$ttl = $this->driver->findElement( By::className( 'comments-section-title' ) );
		$ttl->getLocationOnScreenOnceScrolledIntoView();

		//Post comment.
		$this->driver->findElement( By::cssSelector( '.form-submit .btn.btn-small.btn-secondary' ) )->click();
		//Validate posted comment
		try {
			$cmt = $this->driver->findElement(
				By::cssSelector( '.comments-block .comments-section .single-comment:last-child p:nth-child(2)' ) )->getText();
		} catch ( Exception $e ) {
			$this->fail( '->Not able to see posted comment' );
		}

		$this->assertEquals( $comment, $cmt );

		echo "\n-> Comments test PASSED";
	}
}

