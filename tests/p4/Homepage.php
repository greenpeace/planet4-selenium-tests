<?php

use WebDriverBy as By;

// This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../AbstractClass.php';

class P4_Search extends AbstractClass {

    public function testSearch() {
        $str = 'Test automated';
        $fld = $this->driver->findElement(By::cssSelector('#search_form input.form-control'));
        $fld->click();
        $this->driver->getKeyboard()->sendKeys($str);
        $this->driver->findElement(By::cssSelector('#search_form .top-nav-search-btn'))->click();
        $ttl = explode(' -', $this->driver->getTitle());
        $ttl= $ttl[0];
        $this->assertEquals('Search Results ' . $str, $ttl);
        $t = $this->driver->findElement(By::cssSelector('h2.result-statement'))->getText();
        $this->assertContains('results for \'' . $str . '\'', $t);
        $res = explode(' ', $t);
        if ($res[0] == '0') {
            // If no results shown throw error
            $this->fail('->Could not find any results, check if there is content containing string: ' . $str);
        }
        try {
            $ttl = $this->driver->findElement(
                By::cssSelector('li.search-result-list-item:first-child a.search-result-item-headline'))->getText();
            $this->assertContains($str, $ttl);
        } catch(Exception $e) {
            $this->fail('->Search results do not match string entered');
        }

        // Validate checkboxes and buttons borders and colors
        try {
            $border = $this->driver->findElement(
                By::className('custom-control-description'))->getCssValue('border-color');
            $this->assertEquals('rgb(51, 51, 51)', $border);
            $border = $this->driver->findElement(
                By::className('search-btn'))->getCssValue('border-radius');
            $this->assertEquals('0px', $border);
            $border = $this->driver->findElement(
                By::className('btn-secondary'))->getCssValue('border-color');
            $this->assertEquals('rgb(7, 67, 101)', $border);
        } catch(Exception $e){
            $this->fail('->Failed: Checkboxes have wrong border');
        }

        echo "\n-> Search test PASSED";
    }
}
