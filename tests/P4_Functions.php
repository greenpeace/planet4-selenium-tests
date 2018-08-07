<?php

trait P4_Functions {

	/**
	 * Get the value of a p4 setting from planet4 settings page.
	 *
	 * @param string $option Option id.
	 *
	 * @return mixed Value of the p4 setting.
	 */
	public function getP4Option( $option ) {
		$this->wpLogin();
		$this->_driver->get( $this->getBaseUrl() . '/wp-admin/options-general.php?page=planet4_options' );
		sleep( 2 );

		switch ( $option ) {
			case 'cookies_field':
				return $this->getTextareaOption( $option );
		}
	}

	/**
	 * Get the value of a tinymce textarea instance.
	 *
	 * @param string $field Field id.
	 *
	 * @return mixed Value of a tinymce textarea field.
	 */
	public function getTextareaOption( $field ) {
		// Click on 'Visual' tab of the textarea to force instantiation of tinymce.
		$element = $this->_driver->findElement( WebDriverBy::id( $field . '-tmce' ) );
		$action  = new WebDriverActions( $this->_driver );
		$action->moveToElement( $element )->perform();
		$element->click();

		return $this->_driver->executeScript( 'return tinymce.get("' . $field . '").getContent({format: \'text\'});' );
	}

	/**
	 * Create a new wordpress page.
	 *
	 * @return mixed Value of a tinymce textarea field.
	 */
	public function create_new_page(  ) {

		//I log in
		try {
			$this->wpLogin();
		} catch ( Exception $e ) {
			$this->fail( '->Failed to log in, verify credentials and URL' );
		}

		//Go to pages and create content.
		$this->driver->wait( 10, 500 )->until( WebDriverExpectedCondition::visibilityOfElementLocated( WebDriverBy::id( 'menu-pages' ) ) );
		$pages = $this->driver->findElement( WebDriverBy::id( 'menu-pages' ) );
		$pages->click();
		try {
			$link = $this->driver->findElement( WebDriverBy::linkText( "Add New" ) );
		} catch ( Exception $e ) {
			$this->fail( "->Could not find 'Add New' button in Pages overview" );
		}
		$link->click();
	}

	public function wpLogin() {
		$u = $this->_url . "/wp-admin";
		$this->driver->get($u);
		$p4_user  = self::$_config['p4_user'];
		$p4_pass  = self::$_config['p4_password'];
		// find login form
		$this->driver->findElement(WebDriverBy::id('loginform'));
		$this->driver->wait(3);

		// Enter username by setting the value via javascript. Works for running tests on localhost,
		// while sendKeys() does not work on localhost because the characters are being sent faster than the browser can recieve.
		$this->driver->executeScript("document.getElementById('user_login').setAttribute('value', '$p4_user')");

		// Enter password by setting the value via javascript. Works for running tests on localhost,
		// while sendKeys() does not work on localhost because the characters are being sent faster than the browser can recieve.
		$this->driver->executeScript("document.getElementById('user_pass').setAttribute('value', '$p4_pass')");

		try{
			//Fill in prove you are human field
			$q = $this->driver->findElement(WebDriverBy::cssSelector('#loginform div'))->getText();
			$q = substr($q, strpos($q, ":") + 1);
			$q = substr($q, 0, -3);
			$a = eval('return '.$q.';');
			$ans = $this->driver->findElement(WebDriverBy::name('jetpack_protect_num'));
			$ans->click();
			$this->driver->getKeyboard()->sendKeys("$a");
		}catch(Exception $e){}

		//Click on log in
		$login = $this->driver->findElement(WebDriverBy::id('wp-submit'));
		$login->click();
		$this->driver->wait(3);

		// Validates user is logged in by locating dashboard
		$this->assertContains('Dashboard', $this->driver->getTitle());
	}

	public function wpLogout() {
		$usermenu = $this->driver->findElement(WebDriverBy::id('wp-admin-bar-my-account'));
		$this->driver->getMouse()->mouseMove( $usermenu->getCoordinates() );

		//Waits for hidden menu to be visible
		$this->driver->wait(10, 1000)->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('wp-admin-bar-logout')));

		//Locates log out option and clicks on it
		$logout = $this->driver->findElement(WebDriverBy::linkText('Log Out'));
		$logout->click();

		// Validates user is logged out by locating login form
		$this->driver->findElement(WebDriverBy::id('loginform'));
	}

	function find_replace( $find, $replace, $file, $case_insensitive = true ) {
		if ( ! file_exists( $file ) ) {
			return false;
		} else {
			$contents = file_get_contents( $file );
			if ( $case_insensitive ) {
				$output = str_ireplace( $find, $replace, $contents );
			} else {
				$output = str_replace( $find, $replace, $contents );
			}

			$fopen = fopen( $file, 'w' );
			if ( ! $fopen ) {
				return false;
			} else {
				$fwrite = fwrite( $fopen, $output );
				fclose( $fopen );
				if ( ! $fwrite ) {
					return false;
				} else {
					return true;
				}
			}
		}
	}

    public function clearRedisCache() {

        //I log in
        try {
            $this->wpLogin();
        } catch (Exception $e) {
            $this->fail('->Failed to log in, verify credentials and URL');
        }

        $this->driver->wait(3);

        //Validate module elements are present in block.
        try {
            $cache = $this->driver->findElement(
                WebDriverBy::className("btn-flush_cache-async"));
            // Click on link to flush cache.
            $cache->click();

            // Accept the alert.
            $this->driver->switchTo()->alert()->accept();
        } catch (Exception $e) {
            $this->fail("->Could not find elements in Control Panel block");
        }

        $this->wpLogout();
    }
}
