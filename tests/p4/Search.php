<?php

use Facebook\WebDriver\WebDriverBy as By;

class P4_Homepage extends AbstractClass {

	public function testHomepage() {
		$this->driver->get( $this->_url );

		// Validate Header and footer colors
		try {
			$color = $this->driver->findElement( By::className( 'site-footer' ) )->getCssValue( 'background-color' );
			$this->assertEquals( 'rgba(7, 67, 101, 1)', $color );
			$color = $this->driver->findElement( By::id( 'header' ) )->getCssValue( 'background-color' );
			$this->assertEquals( 'rgba(7, 67, 101, 0.8)', $color );
		} catch ( Exception $e ) {
			$this->fail( '->Failed: Header or Footer colors are wrong' );
		}

		// Validate 2-column block elements are present
		try {
			$this->driver->findElement( By::className( 'content-two-column-block' ) );
			$this->driver->findElement( By::cssSelector( '.content-two-column-block .col-md-12:nth-child(1)' ) );
			$this->driver->findElement( By::cssSelector( '.content-two-column-block .col-md-12:nth-child(2)' ) );
		} catch ( Exception $e ) {
			$this->fail( '->Failed to see some elements of 2-column block in homepage' );
		}

		// Validate article block elements are present
		try {
			$this->driver->findElement( By::className( 'article-listing' ) );
		} catch ( Exception $e ) {
			$this->fail( '->Failed to see some elements of article block in homepage' );
		}

		// Validate 4-column block elements are present
		try {
			$this->driver->findElement( By::className( 'four-column' ) );
			$this->driver->findElement( By::cssSelector( '.four-column .four-column-wrap:nth-child(1)' ) );
			$this->driver->findElement( By::cssSelector( '.four-column .four-column-wrap:nth-child(2)' ) );
			$this->driver->findElement( By::cssSelector( '.four-column .four-column-wrap:nth-child(3)' ) );
			$this->driver->findElement( By::cssSelector( '.four-column .four-column-wrap:nth-child(4)' ) );
		} catch ( Exception $e ) {
			$this->fail( '->Failed to see some elements of 4-column block in homepage' );
		}

		// Validate happy point block elements are present
		try {
			$this->driver->findElement( By::className( 'happy-point-block-wrap' ) );
			$element = $this->driver->findElement( By::id( 'happy-point' ) );
			// Scroll down and wait to happy point to load
			$element->getLocationOnScreenOnceScrolledIntoView();
			usleep( 10000000 );
			$this->driver->switchTo()->frame( $this->driver->findElement( By::cssSelector( '#happy-point iframe' ) ) );
			// Validate input fields are present
			$this->driver->findElement( By::id( 'en__field_supporter_emailAddress' ) );
			$this->driver->findElement( By::id( 'en__field_supporter_country' ) );
			$this->driver->findElement( By::className( 'subscriber-btn' ) );
		} catch ( Exception $e ) {
			$this->fail( '->Failed to see happy point block in homepage' );
		}
		$this->driver->switchTo()->defaultContent();

		// Validate footer block elements are present
		try {
			$this->driver->findElement( By::className( 'site-footer' ) );
			$fb        = $this->driver->findElement(
				By::cssSelector( '.site-footer .footer-social-media li:nth-child(1) a' ) )->getAttribute( 'href' );
			$twt       = $this->driver->findElement(
				By::cssSelector( '.site-footer .footer-social-media li:nth-child(2) a' ) )->getAttribute( 'href' );
			$yt        = $this->driver->findElement(
				By::cssSelector( '.site-footer .footer-social-media li:nth-child(3) a' ) )->getAttribute( 'href' );
			$inst      = $this->driver->findElement(
				By::cssSelector( '.site-footer .footer-social-media li:nth-child(4) a' ) )->getAttribute( 'href' );
			$news      = $this->driver->findElement(
				By::cssSelector( '.site-footer .footer-links li:nth-child(1) a' ) )->getAttribute( 'href' );
			$about     = $this->driver->findElement(
				By::cssSelector( '.site-footer .footer-links li:nth-child(2) a' ) )->getAttribute( 'href' );
			$jobs      = $this->driver->findElement(
				By::cssSelector( '.site-footer .footer-links li:nth-child(3) a' ) )->getAttribute( 'href' );
			$press     = $this->driver->findElement(
				By::cssSelector( '.site-footer .footer-links li:nth-child(4) a' ) )->getAttribute( 'href' );
			$privacy   = $this->driver->findElement(
				By::cssSelector( '.site-footer .footer-links-secondary li:nth-child(1) a' ) )->getAttribute( 'href' );
			$copyright = $this->driver->findElement(
				By::cssSelector( '.site-footer .footer-links-secondary li:nth-child(2) a' ) )->getAttribute( 'href' );
			$terms     = $this->driver->findElement(
				By::cssSelector( '.site-footer .footer-links-secondary li:nth-child(3) a' ) )->getAttribute( 'href' );
			$community = $this->driver->findElement(
				By::cssSelector( '.site-footer .footer-links-secondary li:nth-child(4) a' ) )->getAttribute( 'href' );
			$search    = $this->driver->findElement(
				By::cssSelector( '.site-footer .footer-links-secondary li:nth-child(5) a' ) )->getAttribute( 'href' );
			$this->driver->findElement( By::cssSelector( '.site-footer .copyright-text' ) );
			$this->driver->findElement( By::cssSelector( '.site-footer .gp-year' ) );

		} catch ( Exception $e ) {
			$this->fail( '->Failed to see some elements of footer block' );
		}

		$this->assertEquals( 'https://www.facebook.com/greenpeace.international', $fb );
		$this->assertEquals( 'https://twitter.com/greenpeace', $twt );
		$this->assertEquals( 'https://www.youtube.com/greenpeace', $yt );
		$this->assertEquals( 'https://www.instagram.com/greenpeace/', $inst );
		$this->assertEquals( 'https://dev.p4.greenpeace.org/international/?s=&orderby=relevant&f%5Bctype%5D%5BPost%5D=3', $news );
		$this->assertEquals( $this->_url . 'explore/about/', $about );
		$this->assertEquals( 'https://www.linkedin.com/jobs/greenpeace-jobs/', $jobs );
		$this->assertEquals( 'https://dev.p4.greenpeace.org/international/press-centre/', $press );
		$this->assertEquals( $this->_url . 'privacy/', $privacy );
		$this->assertEquals( $this->_url . 'copyright/', $copyright );
		$this->assertEquals( $this->_url . 'terms/', $terms );
		$this->assertEquals( $this->_url . 'community-policy/', $community );
		$this->assertEquals( 'http://www.greenpeace.org/international/en/System-templates/Search-results/?adv=true', $search );

		echo "\n-> Homepage test PASSED";
	}
}
