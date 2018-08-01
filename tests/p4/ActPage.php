<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../AbstractClass.php';

use WebDriverBy as By;

class P4_Actpage extends AbstractClass {

	/**
	 * @var \RemoteWebDriver
	 */
	public function testActpage() {
		$this->driver->get( $this->_url );
		$this->driver->findElement( By::className( 'act-nav-link' ) )->click();

		$this->assertEquals( $this->driver->getCurrentURL(), 'https://dev.p4.greenpeace.org/international/act/' );

		// Validate header block is present in page.
		try {
			$this->driver->findElement( By::className( 'page-header' ) );
			$this->driver->findElement( By::className( 'page-header-title' ) );
			$this->driver->findElement( By::className( 'page-header-subtitle' ) );
			$this->driver->findElement( By::className( 'page-header-content' ) );
		} catch ( Exception $e ) {
			$this->fail( '->Failed to see header block in act page' );
		}

		// Validate covers block is present in page.
		try {
			$this->driver->findElement( By::className( 'covers-block' ) );
			$this->driver->findElement( By::className( 'cover-card' ) );
		} catch ( Exception $e ) {
			$this->fail( '->Failed to see covers block in act page' );
		}

		// Validate happy point block is present in page.
		try {
			$this->driver->findElement( By::className( 'happy-point-block-wrap' ) );
			$element = $this->driver->findElement( By::id( 'happy-point' ) );

			// Scroll down and wait to happy point to load.
			$element->getLocationOnScreenOnceScrolledIntoView();
			usleep( 2000000 );
			$this->driver->switchTo()->frame( $this->driver->findElement( By::cssSelector( '#happy-point iframe' ) ) );

			// Validate input fields are present
			$this->driver->findElement( By::id( 'en__field_supporter_emailAddress' ) );
			$this->driver->findElement( By::id( 'en__field_supporter_country' ) );
			$this->driver->findElement( By::className( 'subscriber-btn' ) );
		} catch ( Exception $e ) {
			$this->fail( '->Failed to see happy point block in act page' );
		}
		$this->driver->switchTo()->defaultContent();

		// Validate footer block is present in page.
		try {
			$this->driver->findElement( By::className( 'site-footer' ) );
			$this->driver->findElement( By::className( 'footer-social-media' ) );
			$this->driver->findElement( By::className( 'footer-links' ) );
			$this->driver->findElement( By::className( 'footer-links-secondary' ) );
		} catch ( Exception $e ) {
			$this->fail( '->Failed to see footer block in act page' );
		}
		echo "\n-> Act Page test PASSED";
	}
}
