<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../AbstractClass.php';

class P4_Homepage extends AbstractClass {

	/**
	 * @var \RemoteWebDriver
	 */
	public function testHomepage() {
		$this->driver->get( $this->_url );
		//Validate 2-column block elements are present
		try {
			$this->driver->findElement( WebDriverBy::className( 'content-two-column-block' ) );
			$this->driver->findElement( WebDriverBy::cssSelector( '.content-two-column-block .col-md-12.col-lg-5.col-sm-12.col-xl-5:nth-child(1)' ) );
			$this->driver->findElement( WebDriverBy::cssSelector( '.content-two-column-block .col-md-12.col-lg-5.col-sm-12.col-xl-5:nth-child(2)' ) );
		} catch ( Exception $e ) {
			$this->fail( '->Failed to see some elements of 2-column block in homepage' );

		}

		//Validate article block elements are present
		try {
			$this->driver->findElement( WebDriverBy::className( 'article-listing' ) );
		} catch ( Exception $e ) {
			$this->fail( '->Failed to see some elements of article block in homepage' );
		}

		//Validate 4-column block elements are present
		try {
			$this->driver->findElement( WebDriverBy::className( 'four-column' ) );
			$this->driver->findElement( WebDriverBy::cssSelector( '.four-column .four-column-wrap:nth-child(1)' ) );
			$this->driver->findElement( WebDriverBy::cssSelector( '.four-column .four-column-wrap:nth-child(2)' ) );
			$this->driver->findElement( WebDriverBy::cssSelector( '.four-column .four-column-wrap:nth-child(3)' ) );
			$this->driver->findElement( WebDriverBy::cssSelector( '.four-column .four-column-wrap:nth-child(4)' ) );
		} catch ( Exception $e ) {
			$this->fail( '->Failed to see some elements of 4-column block in homepage' );
		}

		//Validate happy point block elements are present
		try {
			$this->driver->findElement( WebDriverBy::className( 'happy-point-block-wrap' ) );
			$element = $this->driver->findElement(
				WebDriverBy::id( 'happy-point' ) );
			//Scroll down and wait to happy point to load
			$element->getLocationOnScreenOnceScrolledIntoView();
			usleep( 2000000 );
			$this->driver->switchTo()->frame( $this->driver->findElement(
				WebDriverBy::cssSelector( '#happy-point iframe' ) ) );
			//Validate input fields are present
			$this->driver->findElement( WebDriverBy::id( "en__field_supporter_emailAddress" ) );
			$this->driver->findElement( WebDriverBy::id( "en__field_supporter_country" ) );
			$this->driver->findElement( WebDriverBy::className( "subscriber-btn" ) );
		} catch ( Exception $e ) {
			$this->fail( '->Failed to see happy point block in homepage' );
		}
		$this->driver->switchTo()->defaultContent();

		//Validate footer block elements are present
		try {
			$this->driver->findElement( WebDriverBy::className( 'site-footer' ) );
			$fb        = $this->driver->findElement(
				WebDriverBy::cssSelector( '.site-footer .footer-social-media li:nth-child(1) a' ) )->getAttribute( 'href' );
			$twt       = $this->driver->findElement(
				WebDriverBy::cssSelector( '.site-footer .footer-social-media li:nth-child(2) a' ) )->getAttribute( 'href' );
			$yt        = $this->driver->findElement(
				WebDriverBy::cssSelector( '.site-footer .footer-social-media li:nth-child(3) a' ) )->getAttribute( 'href' );
			$inst      = $this->driver->findElement(
				WebDriverBy::cssSelector( '.site-footer .footer-social-media li:nth-child(4) a' ) )->getAttribute( 'href' );
			$news      = $this->driver->findElement(
				WebDriverBy::cssSelector( '.site-footer .footer-links li:nth-child(1) a' ) )->getAttribute( 'href' );
			$about     = $this->driver->findElement(
				WebDriverBy::cssSelector( '.site-footer .footer-links li:nth-child(2) a' ) )->getAttribute( 'href' );
			$jobs      = $this->driver->findElement(
				WebDriverBy::cssSelector( '.site-footer .footer-links li:nth-child(3) a' ) )->getAttribute( 'href' );
			$press     = $this->driver->findElement(
				WebDriverBy::cssSelector( '.site-footer .footer-links li:nth-child(4) a' ) )->getAttribute( 'href' );
			$privacy   = $this->driver->findElement(
				WebDriverBy::cssSelector( '.site-footer .footer-links-secondary li:nth-child(1) a' ) )->getAttribute( 'href' );
			$copyright = $this->driver->findElement(
				WebDriverBy::cssSelector( '.site-footer .footer-links-secondary li:nth-child(2) a' ) )->getAttribute( 'href' );
			$terms     = $this->driver->findElement(
				WebDriverBy::cssSelector( '.site-footer .footer-links-secondary li:nth-child(3) a' ) )->getAttribute( 'href' );
			$community = $this->driver->findElement(
				WebDriverBy::cssSelector( '.site-footer .footer-links-secondary li:nth-child(4) a' ) )->getAttribute( 'href' );
			$search    = $this->driver->findElement(
				WebDriverBy::cssSelector( '.site-footer .footer-links-secondary li:nth-child(5) a' ) )->getAttribute( 'href' );
			$this->driver->findElement( WebDriverBy::cssSelector( '.site-footer .copyright-text' ) );
			$this->driver->findElement( WebDriverBy::cssSelector( '.site-footer .gp-year' ) );

		} catch ( Exception $e ) {
			$this->fail( '->Failed to see some elements of footer block' );
		}
		$this->assertEquals( "https://www.facebook.com/greenpeace.international", "$fb" );
		$this->assertEquals( "https://twitter.com/greenpeace", "$twt" );
		$this->assertEquals( "https://www.youtube.com/greenpeace", "$yt" );
		$this->assertEquals( "https://www.instagram.com/greenpeace/", "$inst" );
		$this->assertEquals( "https://dev.p4.greenpeace.org/international/?s=&orderby=relevant&f%5Bctype%5D%5BPost%5D=3", "$news" );
		$this->assertEquals( "https://dev.p4.greenpeace.org/international/about/", "$about" );
		$this->assertEquals( "https://www.linkedin.com/jobs/greenpeace-jobs/", "$jobs" );
		$this->assertEquals( "https://dev.p4.greenpeace.org/international/press-centre/", "$press" );
		$this->assertEquals( "https://dev.p4.greenpeace.org/international/privacy/", "$privacy" );
		$this->assertEquals( "https://dev.p4.greenpeace.org/international/copyright/", "$copyright" );
		$this->assertEquals( "https://dev.p4.greenpeace.org/international/terms/", "$terms" );
		$this->assertEquals( "https://dev.p4.greenpeace.org/international/community-policy/", "$community" );
		$this->assertEquals( "http://www.greenpeace.org/international/en/System-templates/Search-results/?adv=true", "$search" );

		echo "\n-> Homepage test PASSED";
	}
}
