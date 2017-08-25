<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../wp-core/login.php';

class P4_TakeAction extends P4_login {

  /**
   * @var \RemoteWebDriver
   */


  public function testTakeAction()
  {

   	//I log in
   	$this->wpLogin();

	//Find TakeAction menu link  
   	$this->webDriver->wait(3);
    	$link = $this->webDriver->findElement(
		WebDriverBy::id("menu-posts-actions")
	);
    	$link->click();

	//Validate TakeAction admin page by looking at page title
	$this->assertContains(
		'Actions',$this->webDriver->findElement(
			WebDriverBy::className('wp-heading-inline'))->getText()
	);

	//Click on 'Add new'
	$add = $this->webDriver->findElement(
		WebDriverBy::className("page-title-action")
	);
        $add->click();	

	//Validate redirection to add take action page
	$this->assertContains(
		'Add New Action',$this->webDriver->findElement(
			WebDriverBy::className('wp-heading-inline'))->getText()
	);

	//Enter title
	$field = $this->webDriver->findElement(
		WebDriverBy::id('title')
	);
	$field->click();
	$this->webDriver->getKeyboard()->sendKeys('test');

	//Enter description
	$field = $this->webDriver->findElement(
                WebDriverBy::id('content_ifr')
        );
        $field->click();
        $this->webDriver->getKeyboard()->sendKeys('This is a sample decription');
	
	//Add task
	$field = $this->webDriver->findElement(
                WebDriverBy::linkText('Add a task')
        );
        $field->click();	
	//Waits for hidden menu to be visible
	$this->webDriver->wait(10, 1000)->until(
		WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('meta_inner'))
	);
	$this->webDriver->findElement(
                WebDriverBy::name('actionTaskMeta[1][title]')
	)->click();
	$this->webDriver->getKeyboard()->sendKeys('Test Task');
	$this->webDriver->findElement(
                WebDriverBy::name('actionTaskMeta[1][summery]')
        )->click();
	$this->webDriver->getKeyboard()->sendKeys('Test Task Summary');
	
	//Publish content
	$this->webDriver->findElement(
                WebDriverBy::id('publish')
        )->click();
	
	//Validate I see successful message
	$this->assertContains(
                'Post published',$this->webDriver->findElement(
                        WebDriverBy::id('message'))->getText()
        );

	// I log out after test
    	$this->wpLogout();
  }

/**
// Feature is missing validation of mandatory fields 

  public function testTakeAction_validation()
  {
  }
**/

  protected function assertElementNotFound($by)
  {
    $els = $this->webDriver->findElements($by);
    if (count($els)) {
      $this->fail("Unexpectedly element was found");
    }
    // increment assertion counter
    $this->assertTrue(true);

  }

}
?>
