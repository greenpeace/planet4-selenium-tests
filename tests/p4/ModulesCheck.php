<?php

use Facebook\WebDriver\WebDriverBy;

class P4_ModulesCheck extends P4_login {

	/**
	 * @var \RemoteWebDriver
	 */
	public function testModulesCheck3() {

		//I log in
		try {
			$this->wpLogin();
		} catch ( Exception $e ) {
			$this->fail( '->Failed to log in, verify credentials and URL' );
		}

		//Go to pages and create content
		$this->driver->wait( 3 );

		//Validate control panel block is present
		try {
			$this->driver->findElement(
				WebDriverBy::id( "planet4_control_panel" ) );
		} catch ( Exception $e ) {
			$this->fail( "->Could not find Control Panel block in Dashboard" );
		}

		//Validate module elements are present in block
		try {
			$cache = $this->driver->findElement(
				WebDriverBy::className( "btn-check_cache-async" ) );
			$en    = $this->driver->findElement(
				WebDriverBy::className( "btn-check_engaging_networks-async" ) );
		} catch ( Exception $e ) {
			$this->fail( "->Could not find elements in Control Panel block" );
		}
		//Click on link to check cache module
		$cache->click();
		usleep( 3000000 );
		try {
			$msg = $this->driver->findElement(
				WebDriverBy::xPath( "//*[@id='planet4_control_panel']/div/div[1]/div/div[2]/span" ) )->getText();
			$this->assertEquals( "Planet 4 is connected to Redis.", "$msg" );
		} catch ( Exception $e ) {
			$this->fail( "->Failed to validate cache module" );
		}

		/**    MODULE DISABLED FROM STAGING TEMPORARILY
		 * //Click on link to check engaging networks module
		 * $en->click();
		 * usleep(3000000);
		 * try{
		 * $msg = $this->webDriver->findElement(
		 * WebDriverBy::xPath("//*[@id='planet4_control_panel']/div/div[2]/div/div/span"))->getText();
		 * $this->assertEquals("Success", "$msg");
		 * }catch(Exception $e){
		 * $this->fail("->Failed to validate engaging networks module");
		 * }
		 **/
		echo "\n-> Modules check test PASSED";

	}
}

