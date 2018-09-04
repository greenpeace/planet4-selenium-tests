<?php

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\Interactions\WebDriverActions;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverAction;
use Facebook\WebDriver\Remote\LocalFileDetector;

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
	public function create_new_page() {

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
		$this->driver->get( $u );
		$p4_user = self::$_config['p4_user'];
		$p4_pass = self::$_config['p4_password'];
		// find login form
		$this->driver->findElement( WebDriverBy::id( 'loginform' ) );
		$this->driver->wait( 3 );

		// Enter username by setting the value via javascript. Works for running tests on localhost,
		// while sendKeys() does not work on localhost because the characters are being sent faster than the browser can recieve.
		$this->driver->executeScript( "document.getElementById('user_login').setAttribute('value', '$p4_user')" );

		// Enter password by setting the value via javascript. Works for running tests on localhost,
		// while sendKeys() does not work on localhost because the characters are being sent faster than the browser can recieve.
		$this->driver->executeScript( "document.getElementById('user_pass').setAttribute('value', '$p4_pass')" );

		try {
			//Fill in prove you are human field
			$q   = $this->driver->findElement( WebDriverBy::cssSelector( '#loginform div' ) )->getText();
			$q   = substr( $q, strpos( $q, ":" ) + 1 );
			$q   = substr( $q, 0, - 3 );
			$a   = eval( 'return ' . $q . ';' );
			$ans = $this->driver->findElement( WebDriverBy::name( 'jetpack_protect_num' ) );
			$ans->click();
			$this->driver->getKeyboard()->sendKeys( "$a" );
		} catch ( Exception $e ) {
		}

		//Click on log in
		$login = $this->driver->findElement( WebDriverBy::id( 'wp-submit' ) );
		$login->click();
		$this->driver->wait( 3 );

		// Validates user is logged in by locating dashboard
		$this->assertContains( 'Dashboard', $this->driver->getTitle() );
	}

	public function wpLogout() {
		$usermenu = $this->driver->findElement( WebDriverBy::id( 'wp-admin-bar-my-account' ) );
		$this->driver->getMouse()->mouseMove( $usermenu->getCoordinates() );

		//Waits for hidden menu to be visible
		$this->driver->wait( 10, 1000 )->until( WebDriverExpectedCondition::visibilityOfElementLocated( WebDriverBy::id( 'wp-admin-bar-logout' ) ) );

		//Locates log out option and clicks on it
		$logout = $this->driver->findElement( WebDriverBy::linkText( 'Log Out' ) );
		$logout->click();

		// Validates user is logged out by locating login form
		$this->driver->findElement( WebDriverBy::id( 'loginform' ) );
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
		} catch ( Exception $e ) {
			$this->fail( '->Failed to log in, verify credentials and URL' );
		}

		$this->driver->wait( 3 );

		//Validate module elements are present in block.
		try {
			$cache = $this->driver->findElement(
				WebDriverBy::className( "btn-flush_cache-async" ) );
			// Click on link to flush cache.
			$cache->click();

			// Accept the alert.
			$this->driver->switchTo()->alert()->accept();
		} catch ( Exception $e ) {
			$this->fail( "->Could not find elements in Control Panel block" );
		}

		$this->wpLogout();
	}


	public function createTags() {
		$pages = $this->driver->findElement( WebDriverBy::id( 'menu-posts' ) );
		$pages->click();

		try {
			$link = $this->driver->findElement( WebDriverBy::linkText( 'Tags' )
			);
		} catch ( Exception $e ) {
			$this->fail( '->Could not find \'Add New\' button in Pages overview' );
		}
		$link->click();

		$tags = [ "ArcticSunrise", "Oceans" ];
		foreach ( $tags as $tag ) {
			$ans = $this->driver->findElement( WebDriverBy::id( 'tag-name' ) );
			$ans->click()->clear();
			$this->driver->getKeyboard()->sendKeys( $tag );

			$this->driver->findElement( WebDriverBy::id( "insert_image_tag_button" ) )->click();
			$tab = $this->driver->findElement( WebDriverBy::linkText( 'Media Library' ) );
			$tab->click();
			//Wait for media library to load
			$this->driver->wait( 10, 1000 )->until(
				WebDriverExpectedCondition::presenceOfElementLocated(
					WebDriverBy::cssSelector( 'ul.attachments' ) )
			);
			$this->driver->manage()->timeouts()->implicitlyWait( 10 );
			$img = $this->driver->findElement( WebDriverBy::cssSelector( "li.attachment:first-child" ) );
			$img->click();
			$this->driver->findElement( WebDriverBy::className( "media-button-select" ) )->click();

			$pages = $this->driver->findElement( WebDriverBy::id( 'submit' ) );
			$pages->click();
			sleep( 1 );

		}

	}

	public function createCategories() {
		$pages = $this->driver->findElement( WebDriverBy::id( 'menu-posts' ) );
		$pages->click();

		try {
			$link = $this->driver->findElement( WebDriverBy::linkText( 'Categories' )
			);
		} catch ( Exception $e ) {
			$this->fail( '->Could not find \'Add New\' button in Pages overview' );
		}
		$link->click();

		$tags = [
			"Issues" => [
				'',
				'Imagine a world where forests flourish and oceans are full of life. Where energy is as clean as a mountain stream. Where everyone has security, dignity and joy. We can’t build this future alone, but we can build it together.'
			],
			"Energy" => [
				'Issues',
				'We challenge the power of fossil fuel corporations, bolster support for renewable and citizen-powered energy and seek to hold big polluters to account.'
			],
			"Nature" => [
				'Issues',
				'Focusing on great global forests and oceans we aim to preserve, protect and restore the most valuable ecosystems for the climate and for biodiversity.'
			],
		];
		foreach ( $tags as $tag => $tag_values ) {
			$ans = $this->driver->findElement( WebDriverBy::id( 'tag-name' ) );
			$ans->click()->clear();
			$this->driver->getKeyboard()->sendKeys( $tag );
			$ans = $this->driver->findElement( WebDriverBy::id( 'tag-description' ) );
			$ans->click()->clear();
			$this->driver->getKeyboard()->sendKeys( $tag_values[1] );

			if ( $tag_values[0] !== '' ) {

				$this->driver->findElement( WebDriverBy::xpath( "//select[@name='parent']/option[text()='" . $tag_values[0] . "']" ) )->click();
			}

			$pages = $this->driver->findElement( WebDriverBy::id( 'submit' ) );
			$pages->click();
			sleep( 2 );
		}
	}

	public function createCustomTaxonomyTerms() {
		$pages = $this->driver->findElement( WebDriverBy::id( 'menu-posts' ) );
		$pages->click();

		try {
			$link = $this->driver->findElement( WebDriverBy::linkText( 'Page Types' )
			);
		} catch ( Exception $e ) {
			$this->fail( '->Could not find \'Add New\' button in Pages overview' );
		}
		$link->click();

		$tags = [
			"Press Release" => [
				'',
				'Imagine a world where forests flourish and oceans are full of life. Where energy is as clean as a mountain stream. Where everyone has security, dignity and joy. We can’t build this future alone, but we can build it together.'
			],
			"Publication"   => [
				'Issues',
				'We challenge the power of fossil fuel corporations, bolster support for renewable and citizen-powered energy and seek to hold big polluters to account.'
			],
			"Story"         => [
				'Issues',
				'Focusing on great global forests and oceans we aim to preserve, protect and restore the most valuable ecosystems for the climate and for biodiversity.'
			],
		];
		foreach ( $tags as $tag => $values ) {
			$ans = $this->driver->findElement( WebDriverBy::id( 'tag-name' ) );
			$ans->click()->clear();
			$this->driver->getKeyboard()->sendKeys( $tag );
			$ans = $this->driver->findElement( WebDriverBy::id( 'tag-description' ) );
			$ans->click()->clear();
			$this->driver->getKeyboard()->sendKeys( $tag );

			$this->driver->findElement( WebDriverBy::id( 'submit' ) )->click();
			sleep( 1 );
		}


	}

	public function setPermalinks() {
		$pages = $this->driver->findElement( WebDriverBy::id( 'menu-settings' ) );
		$pages->click();

		try {
			$link = $this->driver->findElement( WebDriverBy::linkText( 'Permalinks' )
			);
		} catch ( Exception $e ) {
			$this->fail( '->Could not find \'Add New\' button in Pages overview' );
		}
		$link->click();

		$this->driver->findElement( WebDriverBy::id( 'custom_selection' ) )->click();
		$this->driver->findElement( WebDriverBy::id( 'permalink_structure' ) )->clear()->click();
		$this->driver->getKeyboard()->sendKeys( '/%p4_page_type%/%post_id%/%postname%/' );
		$this->driver->findElement( WebDriverBy::id( 'submit' ) )->click();
	}

	public function setPlanet4Options() {
		$pages = $this->driver->findElement( WebDriverBy::id( 'menu-settings' ) );
		$pages->click();

		try {
			$link = $this->driver->findElement( WebDriverBy::linkText( 'Planet4' )
			);
		} catch ( Exception $e ) {
			$this->fail( '->Could not find \'Add New\' button in Pages overview' );
		}
		$link->click();

		$this->driver->findElement( WebDriverBy::id( 'website_navigation_title' ) )->clear()->click();
		$this->driver->getKeyboard()->sendKeys( 'Planet4 Test Site' );
		$this->driver->findElement( WebDriverBy::xpath( "//select[@name='act_page']/option[text()='ACT']" ) )->click();
		$this->driver->findElement( WebDriverBy::xpath( "//select[@name='explore_page']/option[text()='EXPLORE']" ) )->click();
		$this->driver->findElement( WebDriverBy::xpath( "//select[@name='issues_parent_category']/option[text()='Issues']" ) )->click();
		$this->driver->findElement( WebDriverBy::id( 'copyright_line1' ) )->clear()->click();
		$this->driver->getKeyboard()->sendKeys( 'Copyright Text 1' );
		$this->driver->findElement( WebDriverBy::id( 'copyright_line2' ) )->clear()->click();
		$this->driver->getKeyboard()->sendKeys( 'Copyright Text 2' );
		$this->driver->findElement( WebDriverBy::id( 'google_tag_manager_identifier' ) )->clear()->click();
		$this->driver->getKeyboard()->sendKeys( 'GTM-' );
		$this->driver->findElement( WebDriverBy::id( 'engaging_network_form_id' ) )->clear()->click();
		$this->driver->getKeyboard()->sendKeys( 'https://act.greenpeace.org/page/16381/subscribe/1' );
		$this->driver->findElement( WebDriverBy::id( 'cookies_field' ) )->clear()->click();
		$this->driver->getKeyboard()->sendKeys( 'We use cookies to enhance your experience. By clicking “Got it!” you agree to our <strong><a href="http://www.greenpeace.org/international/privacy/">Privacy &amp; Cookies Policy</a></strong>. You can <a href="https://www.greenpeace.org/international/privacy/#change-your-cookies-preferences">change your cookies settings anytime</a>.' );
		$this->driver->findElement( WebDriverBy::id( 'articles_block_title' ) )->clear()->click();
		$this->driver->getKeyboard()->sendKeys( 'Latest Articles' );
		$this->driver->findElement( WebDriverBy::id( 'articles_block_button_title' ) )->clear()->click();
		$this->driver->getKeyboard()->sendKeys( 'Read More' );
		$this->driver->findElement( WebDriverBy::xpath( "//select[@name='default_p4_pagetype']/option[text()='Story']" ) )->click();

		$this->driver->findElement( WebDriverBy::name( 'submit-cmb' ) )->click();

	}

	public function createPosts() {

		$posts = [
			[
				'title'        => 'Test automated – Post',
				'description'  => 'This is demo content generated by an automated test',
				'tags'         => [ 'Oceans' ],
				'p4-page-type' => 'Story',
				'parent'       => 0,
				'categories'   => [],
				'type'         => 'post',

			],
			[
				'title'        => 'Sail with us',
				'description'  => '[shortcake_two_columns title_1="What motivates you?" description_1="We receive
                quite a lot of applications. In order to increase you chances be sure to demonstrate your interest in
                sailing with us, your affinity with our goals, and the skills that you hold that could be an asset for
                Greenpeace." title_2="Apply to join us" description_2="If you have experience at sea, valid marine
                qualifications, licenses, or are eager to learn - join us! We look forward to sailing with you."
                button_text_2="Go to application" button_link_2="https://goo.gl/forms/C343RBAzZ5yhnXbM2" /]

                [shortcake_carousel multiple_image="1042,1210,1012" /]

                [shortcake_tasks tasks_title="Join us at sea" tasks_description="The ship’s core crew is made up of
                people from a wide range of backgrounds – ranging from captains, mates, and marine engineers to doctors,
                cooks and volunteer deckhands. It is hard work. But it could be the experience of a lifetime. Follow
                these steps to apply:" title_1="Learn about our ships" description_1="Take a guided tour of our fleet."
                button_text_1="Explore" button_link_1="http://sailing-with-greenpeace.com/" title_2="What motivates
                you?" description_2="Be prepared to express your interest in sailing with us, your affinity with our
                goals, and the skills that you hold that could be an asset." button_text_2="Go to application"
                button_link_2="https://goo.gl/forms/3lRhUMrriS548jKz1" title_3="Volunteer with a local office"
                description_3="Get to know the people who can help you get aboard a Greenpeace ship. Find a Greenpeace
                office near you, and volunteer locally." button_text_3="Find Greenpeace near you"
                button_link_3="/international/explore/about/worldwide/" /]

                [shortcake_happy_point background="11275" focus_image="center center" mailing_list_iframe="true" /]',
				'tags'         => [ 'ArcticSunrise' ],
				'p4-page-type' => '',
				'parent'       => 'ACT',
				'categories'   => [],
				'type'         => 'page',

			],
			[
				'title'        => 'Energy',
				'description'  => '[shortcake_content_three_column title="Our vision" description="Fossil fuel
                companies will fight tooth and nail to keep us locked to the path that gives them a few more years of
                profit - the path that sends us careening off a cliff. We must fight equally hard - harder - to grab the
                opportunity to take control of our own destiny. It may be our last chance.
                We are the ones we have been waiting for. We are the generation that goes beyond coal and oil."
                image_1="14068" image_2="14066" image_3="1168" /]
                [shortcake_campaign_thumbnail title="Our campaigns" /]

                [shortcake_covers title="What you can do" select_tag="65,88,87" covers_view="0" /]

                [shortcake_content_four_column title="Publications" select_tag="65,88,87"
                p4_page_type_press_release="false" p4_page_type_publication="true" p4_page_type_story="false"
                posts_view="0" p4_post_types="62" /]

                [shortcake_articles article_heading="Latest updates" article_count="3"
                read_more_link="https://www.greenpeace.org/international/?s=&amp;orderby=post_date&amp;f%5Bcat%5D%5BEnergy%5D=69"
                /]

                [shortcake_happy_point background="11275" focus_image="center center" mailing_list_iframe="true" /]',
				'tags'         => [ 'Coal', 'EnergyRevolution', 'Oil' ],
				'p4-page-type' => '',
				'parent'       => 'EXPLORE',
				'categories'   => [ 'Energy' ],
				'type'         => 'page',

			],
		];

		foreach ( $posts as $post ) {
			$menu  = 'menu-' . ( $post['type'] === 'post' ? 'posts' : 'pages' );
			$pages = $this->driver->findElement( WebDriverBy::id( $menu ) );
			$pages->click();

			try {
				$link = $this->driver->findElement(
					WebDriverBy::linkText( 'Add New' ) );
			} catch ( Exception $e ) {
				$this->fail( '->Could not find \'Add New\' button in Pages overview' );
			}
			$link->click();

			$this->driver->wait( 10, 1000 )->until(
				function () {

					return $this->driver->findElement( WebDriverBy::name( 'post_title' ) )->isEnabled();
				}
			);
			//Enter title
			$field = $this->driver->findElement( WebDriverBy::name( 'post_title' ) );

			$field->click()->clear();
			$this->driver->getKeyboard()->sendKeys( $post['title'] );


			//Enter description
			try {
				$this->driver->findElement( WebDriverBy::id( 'content-html' ) )->click();
				$this->driver->findElement( WebDriverBy::id( 'content' ) )->click();
				$this->driver->getKeyboard()->sendKeys( $post['description'] );
				$this->driver->switchTo()->defaultContent();
			} catch ( Exception $e ) {
				$this->fail( '->Failed to enter post description' );
			}


			// Add categories.
			foreach ( $post['categories'] as $category ) {

				try {
					$this->driver->findElement( WebDriverBy::xpath( '//ul[@id=\'categorychecklist\']//label[normalize-space(text())=\'' . $category . '\']/input' ) )->click();
				} catch ( Exception $e ) {
					$this->fail( '->Failed to add category to post' );
				}
			}

			// Add parent page.
			if ( 0 !== $post['parent'] ) {
				$this->driver->findElement( WebDriverBy::xpath( "//select[@name='parent_id']/option[normalize-space(text())='" . $post['parent'] . "']" ) )->click();
			}

			// Add p4-page-type term.
			if ( '' !== $post['p4-page-type'] ) {
				$this->driver->findElement( WebDriverBy::xpath( "//select[@name='p4-page-type']/option[normalize-space(text())='" . $post['p4-page-type'] . "']" ) )->click();
			}

			// Add tags.
			foreach ( $post['tags'] as $tag ) {

				try {
					$this->driver->findElement( WebDriverBy::id( 'new-tag-post_tag' ) )->click();
					$this->driver->getKeyboard()->sendKeys( $tag );
					//Sleep for 3 seconds for suggestion to appear
					usleep( 3000000 );
					//Select suggestion
					$this->driver->getKeyboard()->pressKey( WebDriverKeys::ENTER );
					$this->driver->findElement( WebDriverBy::className( 'tagadd' ) )->click();
				} catch ( Exception $e ) {
					$this->fail( '->Failed to add tag to post' );
				}
			}


			//Publish content
			$publish = $this->driver->findElement( WebDriverBy::id( 'publish' ) );
			// Execute javascript:
			$this->driver->executeScript( "window.scrollTo(0, 0)" );
			$this->driver->findElement( WebDriverBy::id( 'publishing-action' ) )->getLocationOnScreenOnceScrolledIntoView();
			sleep( 1 );
			$publish->click();
		}
	}

	protected function uploadMedia() {
		$driver = $this->driver;


		$pages = $this->driver->findElement( WebDriverBy::id( 'menu-media' ) );
		$pages->click();
		try {
			$link = $this->driver->findElement( WebDriverBy::linkText( 'Add New' )
			);
		} catch ( Exception $e ) {
			$this->fail( '->Could not find \'Add New\' button in Pages overview' );
		}
		$link->click();


		$input = $this->driver->findElement( WebDriverBy::xpath( "//input[starts-with(@id,'html5_')]" ) );

		// Set the file detector.
		$input->setFileDetector( new LocalFileDetector() );

		// Scan data images directory and upload each one to media library.
		$images = array_diff( scandir( 'data/images' ), array( '..', '.' ) );
		foreach ( $images as $image ) {
			$input->sendKeys( 'data/images/' . $image );
		}

		// Wait for images upload.
		$count_images = count( $images );
		$driver->wait( 90, 2000 )->until(
			function () use ( $driver, $count_images ) {
				return count( $driver->findElements(
						WebDriverBy::xpath( "//div[@id='media-items']//a[@class='edit-attachment']" ) ) ) == $count_images;
			}
		);

		$anchors = $this->driver->findElements( WebDriverBy::xpath( "//div[@id='media-items']//a[@class='edit-attachment']" ) );


		$ids = [];
		foreach ( $anchors as $anchor ) {
			$link   = $anchor->getAttribute( 'href' );
			$params = parse_url( $link, PHP_URL_QUERY );
			parse_str( $params, $output );
			$ids[] = $output['post'];
		}

		return $ids;
	}
}
