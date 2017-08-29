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
	try{
		$ENlink = $this->webDriver->findElement(WebDriverBy::linktext("EngagingNetworks"));
	}catch(Exception $e){
		$this->fail('->Could not find menu link for Engaging Networks page');
	}
	$ENlink->click();

	//Click on second EN menu link
	$this->webDriver->wait(3);
	try{
		$ENlink = $this->webDriver->findElement(WebDriverBy::linktext("EN Pages DataTable"));
	}catch(Exception $e){
                $this->fail('->Could not find submenu link called EN Pages DataTable under Engaging Networks menu link');
        }
	$ENlink->click();

	//Look for table present
	try{
		$this->webDriver->findElement(WebDriverBy::id('en_pages_table_wrapper'));
	}catch(Exception $e){
		$this->fail('->Could not find table wrapper in EN Pages DataTable page');
	}

	//Select Donation subtype
	try{
		$this->webDriver->findElement(WebDriverBy::id("p4en_pages_subtype"))->click();
		$this->webDriver->findElement(WebDriverBy::xpath("//*[@id='p4en_pages_subtype']/option[8]"))->click();
	}catch(Exception $e){
		$this->fail('->Could not find Donation option in subtype dropdown field');
	}

	//Select live status
	try{
		$this->webDriver->findElement(WebDriverBy::id("p4en_pages_status"))->click();
		$this->webDriver->findElement(WebDriverBy::xpath("//*[@id='p4en_pages_status']/option[4]"))->click();
	}catch(Exception $e){
		$this->fail('->Could not find Live option in status dropdown field');
	}

	//Click on Save Changes to filter results
	$this->webDriver->findElement(WebDriverBy::id('p4en_pages_datatable_settings_save_button'))->click();

	//Fail if test error is shown
	try{
		if($this->webDriver->findElement(WebDriverBy::className('p4en_error_message'))){
			$this->fail("->Error message shown in EN Pages Data Table page, check API settings are correct");
		}
	}
	catch(Exception $e){
		
		unset($e);
	}

	//Validate results
	$this->webDriver->wait(3);
	try{
		$results = $this->webDriver->findElement(WebDriverBy::xpath("//*[@id='en_pages_table']/tbody/tr"))->getText();
		$this->assertContains('Live',$results);	
		$this->assertContains('Donation',$results);		

	}catch(Exception $e){
                $this->fail('->Could not find matching words in search results. Make sure there are existing forms that match filter');
        }


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
