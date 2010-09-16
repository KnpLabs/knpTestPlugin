# knpTestPlugin

Enable the plugin.

Create your base test class which should inherit `knpFunctionalTest` or `knpShortcutFunctionalTest` (provides convenience shortcuts).

Requires a getDoctrineConnection() method.

  <?php

  class adminFunctionalTest extends knpLimeFunctionalTest
  {

    protected function getDoctrineConnection()
    {
      return Doctrine::getTable('Product')->getConnection(); 
    }

    protected function login($login = 'knplabs', $password = 'loremipsum')
    {
      // Some logic here
    }
  }

Add these lines to your `/test/bootstrap/functional.php`.

  require_once($configuration->getRootDir(). '/plugins/knpTestPlugin/knpFunctionalTest.class.php');
  // If adminFunctionalTest is your base test class:
  require_once(dirname(__FILE__) . '/adminFunctionalTest.class.php');

Then create a test class for your functional tests; every test inside should use `test*` as method name.
Each of these methods will be called inside a Doctrine transaction so that the DB is not changed.

  <?php
  
  include(dirname(__FILE__).'/../../bootstrap/functional.php');

  class productActionsTest extends adminFunctionalTest
  {
    public function testCreateSector()
    {
      $this
      ->get('/sector/new')
      ->setField('name', 'knplabs')
      ->click('Go on')
      ->with('response')->begin()
        ->matches("Right")
      ->end()
      ;
    }
    
    public function testOtherThing()
    {
      …
    }
  }
  
  $test = new productActionsTest();
  $test->run();
  
