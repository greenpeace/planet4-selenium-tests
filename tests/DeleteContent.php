<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/wp-core/login.php';

class P4_DeleteContent extends P4_login {

  /**
   * @var \RemoteWebDriver
   */


  public function testDeleteContent()
  {

    //I log in
    try{
        $this->wpLogin();
    }catch(Exception $e){
        $this->fail('->Failed to log in, verify credentials and URL');
    }

    //Go to pages to delete content created by automated tests
    $this->webDriver->wait(3);
    $pages = $this->webDriver->findElement(
        WebDriverBy::id("menu-pages"));
    $pages->click();
    //Search for content created by automated tests
    try{
        $link = $this->webDriver->findElement(
            WebDriverBy::id("post-search-input"));
        $link->click();
        $this->webDriver->getKeyboard()->sendKeys('Test automated - ');
        $btn = $this->webDriver->findElement(
            WebDriverBy::id("search-submit"));
        $btn->click();
        usleep(2000000);
    }catch(Exception $e){
        $this->fail("->Failed to search for content in pages overview");
    }
    
    //Count number of results
    $rows = explode(" ",$this->webDriver->findElement(
        WebDriverBy::className("displaying-num"))->getText());
    $rows = intval($rows[0]);
    //Flag to identify if there are items to delete
    $items_to_delete = false;
    $counter = $rows/20;
    $y = 1;
    while($counter>0){
        //For each search result, validate it has the same title as content generated by automated test
        for ($x = $y; $x <= min($rows,20); $x++) {
            $pth = "#the-list tr:nth-child($x) td.title.column-title strong a.row-title";
            $title_ = $this->webDriver->findElement(
                WebDriverBy::cssSelector($pth))->getText();
            $title = explode(" – ", $title_);
            $title = $title[0];
            //If title matches then select checkbox
            try{
                if ($title == "Test automated"){
                $items_to_delete = true;
                $this->webDriver->findElement(
                    WebDriverBy::cssSelector("#the-list tr:nth-child($x) th.check-column input[type='checkbox']"))->click();
                }
            }catch(Exception $e){
                $this->fail("->Not able to select element in row $x, make sure it is not currently being edited");
            }
        }
        //If there are items to delete then apply 'move to trash' bulk action
        if ($items_to_delete){
            $this->webDriver->findElement(
                WebDriverBy::id("bulk-action-selector-bottom"))->click();
            $this->webDriver->findElement(
                WebDriverBy::cssSelector("#bulk-action-selector-bottom option[value='trash']"))->click();
            $this->webDriver->findElement(
                WebDriverBy::id("doaction2"))->click();    
        }else{
            echo "\n--- No pages to delete";
        }
        $y = $x;
        $counter --;
        $rows=$rows-20;
    }

    //Go to posts to delete content created by automated tests
    $this->webDriver->wait(3);
    $pages = $this->webDriver->findElement(
        WebDriverBy::id("menu-posts"));
    $pages->click();
    //Search for content created by automated tests
    try{
        $link = $this->webDriver->findElement(
            WebDriverBy::id("post-search-input"));
        $link->click();
        $this->webDriver->getKeyboard()->sendKeys('Test automated - ');
        $btn = $this->webDriver->findElement(
            WebDriverBy::id("search-submit"));
        $btn->click();
        usleep(2000000);
    }catch(Exception $e){
        $this->fail("->Failed to search for content in posts overview");
    }
    
    //Count number of results
    $rows = $this->webDriver->findElements(
        WebDriverBy::cssSelector("#the-list tr"));
    $rows = count($rows);
    //Flag to identify if there are items to delete
    $items_to_delete = false;
    //For each search result, validate it has the same title as content generated by automated test
    for ($x = 1; $x <= $rows; $x++) {
        $pth = "#the-list tr:nth-child($x) td.title.column-title strong a.row-title";
        $title_ = $this->webDriver->findElement(
            WebDriverBy::cssSelector($pth))->getText();
        $title = explode(" – ", $title_);
        $title = $title[0];
        //If title matches then select checkbox
        try{
            if ($title == "Test automated"){
            $items_to_delete = true;
            $this->webDriver->findElement(
                WebDriverBy::cssSelector("#the-list tr:nth-child($x) th.check-column input[type='checkbox']"))->click();
            }
        }catch(Exception $e){
            $this->fail("->Not able to select element in row $x, make sure it is not currently being edited");
        }
    }
    //If there are items to delete then apply 'move to trash' bulk action
    if ($items_to_delete){
        $this->webDriver->findElement(
            WebDriverBy::id("bulk-action-selector-bottom"))->click();
        $this->webDriver->findElement(
            WebDriverBy::cssSelector("#bulk-action-selector-bottom option[value='trash']"))->click();
        $this->webDriver->findElement(
            WebDriverBy::id("doaction2"))->click();    
    }else{
        echo "\n--- No posts to delete";
    }


    // I log out after test
    $this->wpLogout();
    echo "\n-> Delete content script PASSED";
  }

  protected function assertElementNotFound($by)
  {
    $this->webDriver->takeScreenshot('reports/screenshots/'.__CLASS__.'.png');
    $els = $this->webDriver->findElements($by);
    if (count($els)) {
        $this->fail("Unexpectedly element was found");
    }
    // increment assertion counter
    $this->assertTrue(true);

  }

}
?>
