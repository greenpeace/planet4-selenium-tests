<?php


class P4_Redirect extends AbstractClass {

	/**
	 * @var \RemoteWebDriver
	 */

	//This test is hardcoded for planet 4 dev environment

	public function testRedirectModule() {
		//Url to be checked
		$u = "http://dev.p4.greenpeace.org/international/Global/international/briefings/other/Statement-of-concern.pdf";
		$this->driver->get( $u );
		usleep( 2000000 );
		$new_u = $this->driver->getCurrentURL();
		$this->assertContains( "/archive-international/Global/international/briefings/other/Statement-of-concern.pdf", $new_u );
		echo "\n-> Redirects Module test PASSED";
	}
}
