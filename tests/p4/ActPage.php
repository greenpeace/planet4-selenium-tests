<?php

use WebDriverBy as By;

// This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../AbstractClass.php';

class P4_Actpage extends AbstractClass {

    public function testActpage() {
        $this->driver->get($this->_url);

        $this->driver->findElement(By::className('act-nav-link'))->click();

        $this->assertEquals($this->driver->getCurrentURL(), $this->_url . 'act/');

        // Validate header block is present in page
        try {
            $this->driver->findElement(By::className('page-header'));
            $this->driver->findElement(By::className('page-header-title'));
            $this->driver->findElement(By::className('page-header-subtitle'));
            $this->driver->findElement(By::className('page-header-content'));
        } catch(Exception $e){
            $this->fail('->Failed to see header block in act page');
        }

        // Validate header title font
        try {
            $font = $this->driver->findElement(By::className('page-header-title'))->getCssValue('font-family');
            $this->assertEquals('Roboto, sans-serif', $font);
            $padding = $this->driver->findElement(By::className('page-header'))->getCssValue('padding');
            $this->assertEquals('144px 0px 60px', $padding);
        } catch(Exception $e){
            $this->fail('->Failed: Page header title has wrong font');
        }

        // Validate proper text color on various page elements
        try {
            $body_color = $this->driver->findElement(By::cssSelector('body'))->getCssValue('color');
            $section_color = $this->driver->findElement(By::className('page-section-description'))->getCssValue('color');
            $this->assertEquals('rgba(26, 26, 26, 1)', $body_color);
            $this->assertEquals('rgba(26, 26, 26, 1)', $section_color);
        } catch(Exception $e){
            $this->fail('->Failed: Text color is wrong');
        }

        // Validate covers block is present in page
        try {
            $this->driver->findElement(By::className('covers-block'));
            $this->driver->findElement(By::className('cover-card'));
        } catch(Exception $e){
            $this->fail('->Failed to see covers block in act page');
        }

        // Validate happy point block is present in page
        try {
            $this->driver->findElement(By::className('happy-point-block-wrap'));
            $element = $this->driver->findElement(
                By::id('happy-point'));
            // Scroll down and wait to happy point to load
            $element->getLocationOnScreenOnceScrolledIntoView();
            usleep(2000000);
            $this->driver->switchTo()->frame($this->driver->findElement(By::cssSelector('#happy-point iframe')));
            // Validate input fields are present
            $this->driver->findElement(By::id('en__field_supporter_emailAddress'));
            $this->driver->findElement(By::id('en__field_supporter_country'));
            $this->driver->findElement(By::className('subscriber-btn'));
        } catch(Exception $e) {
            $this->fail('->Failed to see happy point block in act page');
        }

        $this->driver->switchTo()->defaultContent();

        // Validate footer block is present in page
        try {
            $this->driver->findElement(By::className('site-footer'));
            $this->driver->findElement(By::className('footer-social-media'));
            $this->driver->findElement(By::className('footer-links'));
            $this->driver->findElement(By::className('footer-links-secondary'));
        } catch(Exception $e) {
            $this->fail('->Failed to see footer block in act page');
        }
        echo "\n-> Act Page test PASSED";
    }
}
