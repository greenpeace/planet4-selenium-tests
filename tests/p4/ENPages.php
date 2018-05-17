<?php

// This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../wp-core/login.php';

class P4_ENPages extends P4_login {

	public function testENPages() {

		// I log in
		$this->wpLogin();

		// Find EN menu link
		$this->webDriver->wait( 3 );
		try {
			$ENlink = $this->webDriver->findElement( WebDriverBy::linktext( 'EngagingNetworks' ) );
		} catch( Exception $e ) {
			$this->fail( '->Could not find menu link for Engaging Networks plugin page' );
		}
		$ENlink->click();

		$selected_subtype = $selected_status = '';

		// Select subtype
		try {
			$this->webDriver->findElement( WebDriverBy::id( 'p4en_pages_subtype' ) )->click();
		} catch( NoSuchElementException $e ) {
			$this->fail( '->Could not find the - Select Subtype - dropdown field' );
		}

		try {
			$select_subtype = $this->webDriver->findElement( WebDriverBy::xpath( "//*[@id='p4en_pages_subtype']/option[8]" ) );
			$select_subtype->click();
			$selected_subtype = $select_subtype->getText();
		} catch( NoSuchElementException $e ) {
			$this->fail( '->Could not find the -' . $selected_subtype . '- option in the - Select Subtype - dropdown field' );
		}

		// Select status
		try {
			$this->webDriver->findElement( WebDriverBy::id( 'p4en_pages_status' ) )->click();
		} catch( NoSuchElementException $e ) {
			$this->fail( '->Could not find the - Select Status - dropdown field' );
		}

		try {
			$select_status = $this->webDriver->findElement( WebDriverBy::xpath( "//*[@id='p4en_pages_status']/option[4]" ) );
			$select_status->click();
			$selected_status = $select_status->getText();
		} catch( Exception $e ) {
			$this->fail( '->Could not find the -' . $selected_status . '- option in the - Select Status - dropdown field' );
		}

		// Click on Save Changes to filter results
		$this->webDriver->findElement( WebDriverBy::id( 'p4en_pages_datatable_settings_save_button' ) )->click();
		sleep( 3 );

		// Fail if test error is shown
		try {
			if( $this->webDriver->findElement( WebDriverBy::cssSelector( '.p4en_error_message' ) ) ) {
				$this->fail( '->Error message shown in EN Pages Data Table page, check API settings are correct' );
			}
		} catch( Exception $e ) {
			unset( $e );
		}

		// Search for specific page.
		$search = $this->webDriver->findElement( WebDriverBy::xpath( "//*[@id='en_pages_table_filter']/label/input" ) );
		$search->click();
		$this->webDriver->getKeyboard()->sendKeys( 'testing' );

		// Validate results
		sleep( 1 );
		try {
			$results = $this->webDriver->findElement( WebDriverBy::xpath( "//*[@id='en_pages_table']/tbody/tr" ) )->getText();
			$this->assertContains( $selected_subtype, $results );
			$this->assertContains( $selected_status, $results );

		} catch( NoSuchElementException $e ) {
			$this->fail( '->Could not find a row inside the Pages table' );
		} catch( Exception $e ) {
			$this->fail( '->Could not find matching words in search results. Make sure there are existing forms that match filter' );
		}

		// Get the page title as it exists within the table.
		$p4en_page_title = $this->webDriver->findElement( WebDriverBy::cssSelector( '.p4en_page_name' ) )->getText();

		// Try to edit the first page in the table.
		try {
			$edit_action = $this->webDriver->findElement( WebDriverBy::cssSelector( '.do_edit' ) );
			$edit_action->click();
		} catch( NoSuchElementException $e ) {
			$this->fail( '->Could not find the Edit Action link' );
		}

		$args = [
			'p4en_page_title' => $p4en_page_title
		];
		$this->checkNavigationToEN( $args );

		// I log out after test
		$this->wpLogout();
	}

	/**
	 * @param array $args Associative array with values needed.
	 */
	protected function checkNavigationToEN( $args ) {
		// Get all opened browser tabs.
		$tabs = $this->_driver->getWindowHandles();
		// Switch to the newly opened EN platform tab.
		$this->_driver->switchTo()->window( end( $tabs ) );

		sleep( 5 );
		$this->webDriver->executeScript("document.getElementById('enLoginUsername').setAttribute('value', '" . self::$_config['en_email'] . "')");
		$this->webDriver->executeScript("document.getElementById('enLoginPassword').setAttribute('value', '" . self::$_config['en_password'] . "')");

		$en_login = $this->webDriver->findElement(WebDriverBy::cssSelector( '.button--login' ) );
		$en_login->click();
		sleep( 10 );

		// Check if we navigated to the corresponding page within the EN platform.
		try {
			$en_page_title = $this->webDriver->findElement( WebDriverBy::cssSelector( '.enOverlay__popup__title' ) )->getText();
			$this->assertContains( $args['p4en_page_title'], $en_page_title );
		} catch( NoSuchElementException $e ) {
			$this->fail( '->Could not navigate to the Edit page screen within the EN platform' );
		}

		// Switch back to the P4 tab.
		$this->_driver->switchTo()->window( reset( $tabs ) );
	}
}
