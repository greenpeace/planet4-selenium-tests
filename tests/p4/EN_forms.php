<?php

//This class is needed to start the session and open/close the browser
require_once __DIR__ . '/../wp-core/login.php';

class P4_EN_forms extends P4_login {

  /**
   * @var \RemoteWebDriver
   */


  public function testENforms()
  {

   //I log in
   $this->wpLogin();

    //Find EN menu link  
    $this->webDriver->wait(3);
    $ENlink = $this->webDriver->findElement(WebDriverBy::linktext("EngagingNetworks"));
    $ENlink->click();
    $this->webDriver->findElement(WebDriverBy::id('en_pages_div'));

    //Click on second EN menu link
    $this->webDriver->wait(3);
    $ENlink = $this->webDriver->findElement(WebDriverBy::linktext("EN Pages DataTable"));
    $ENlink->click();
    $this->webDriver->findElement(WebDriverBy::id('en_pages_table_wrapper'));

    //Click on subtype field
    $this->webDriver->findElement(WebDriverBy::id("p4en_pages_subtype"))->click();
    $this->webDriver->findElement(WebDriverBy::xpath("//*[@id='p4en_pages_subtype']/option[8]"))->click();
    //$this->select(WebDriverBy::id('p4en_pages_subtype'))->selectOptionByValue('ND');

    //Select Donation subtype
    $this->webDriver->findElement(WebDriverBy::id("p4en_pages_status"))->click();
    $this->webDriver->findElement(WebDriverBy::xpath("//*[@id='p4en_pages_status']/option[4]"))->click();

    //Click on Save Changes to filter results
    $this->webDriver->findElement(WebDriverBy::id('p4en_pages_datatable_settings_save_button'));

    //Validate results
    $this->webDriver->wait(3);
    $results = $this->webDriver->findElement(WebDriverBy::xpath("//*[@id='en_pages_table']/tbody/tr/td[contains(text(), 'Live')]"));
    $results = $this->webDriver->findElement(WebDriverBy::xpath("//*[@id='en_pages_table']/tbody/tr/td[contains(text(), 'Donation')]"));   

    //Search for invalid petition
    $search = $this->webDriver->findElement(WebDriverBy::xpath("//*[@id='en_pages_table_filter']/label/input"));
    $search->click();
    $this->webDriver->getKeyboard()->sendKeys('!@#');
    $this->webDriver->wait(5);

    //Validate no results are shown
    $this->webDriver->findElement(WebDriverBy::className("dataTables_empty")); 


    // I log out after test
    $this->wpLogout();
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
