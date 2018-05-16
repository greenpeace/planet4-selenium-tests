<?php

use WebDriverBy as By;

// This class is needed to start the session and open/close the browser.
require_once __DIR__ . '/../wp-core/login.php';

/**
 * Class P4_Cookies
 */
class P4_Cookies extends P4_login {

	use P4_Functions;

	public function testCookies() {
		$driver       = $this->webDriver;
		$cookies_text = $this->getP4Option( 'cookies_field' );
		// Validate banner is visible and contains link to more info.
		$driver->get( $this->getBaseUrl() );

		try {
			$driver->findElement( By::id( 'set-cookie' ) );
			$val = $driver->findElement( By::id( 'set-cookie' ) )
			              ->getCSSValue( 'display' );
			if ( 'block' !== $val) {
				$this->fail( '->Failed due to cookie banner not visible' );
			}
			$site_text = $driver->findElement( By::cssSelector( '#set-cookie .row p' ) )
			                    ->getText();
			$this->assertContains( $cookies_text, $site_text );
		} catch ( Exception $e ) {
			$this->fail( '->Failed due to cookie banner not visible' );
		}

		// Validate button to close banner.
		try {
			$driver->findElement( By::id( 'hidecookie' ) )
			       ->click();
			usleep( 1500000 );
		} catch ( Exception $e ) {
			$this->fail( '->Failed when trying to click on button to close cookie banner' );
		}

		// Validate banner is closed.
		$val = $driver->findElement( By::id( 'set-cookie' ) )
		              ->getCSSValue( 'display' );
		if ( 'none' !== $val ) {
			$this->fail( '->Failed due to cookie banner not hidden' );
		}
		echo "\n-> Cookies banner test PASSED";
	}


	/**
	 * @param $by
	 */
	protected function assertElementNotFound( $by ) {
		$this->webDriver->takeScreenshot( 'reports/screenshots/' . __CLASS__ . '.png' );
		$els = $this->webDriver->findElements( $by );
		if ( count( $els ) ) {
			$this->fail( 'Unexpectedly element was found' );
		}
		// increment assertion counter.
		$this->assertTrue( true );

	}

}

