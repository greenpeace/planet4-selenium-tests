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
    public function getTextareaOption($field) {
        //Click on 'Visual' tab of the textarea to force instantiation of tinymce.
        $this->_driver->findElement(WebDriverBy::id($field . '-tmce'))->click();
        return $this->_driver->executeScript('return tinymce.get("' . $field . '").getContent({format: \'text\'});');
    }
}