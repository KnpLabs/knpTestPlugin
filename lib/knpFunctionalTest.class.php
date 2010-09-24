<?php

/**
 * Base class for functional tests inspired by PHPUnit. 
 * Uses Doctrine transactions to keep a DB intact between tests.
 *
 * @package knpTestPlugin
 * @author Matthieu Bontemps (matthieu@knplabs.com)
 */
abstract class knpFunctionalTest extends sfTestBrowser
{
  /**
   * Initializes the browser tester instance.
   *
   * @param sfBrowserBase $browser A sfBrowserBase instance
   * @param lime_test     $lime    A lime instance
   */
  public function __construct(sfBrowserBase $browser = null, lime_test $lime = null, $testers = array())
  {
    if(null === $browser)
    {
      $browser = new sfBrowser();
    }

    parent::__construct($browser, $lime, $testers);
  }

  /**
   * Get a Doctrine connection to run the test 
   * 
   * @return Doctrine_Connection
   */
  protected abstract function getDoctrineConnection();

  /**
   * Do something before a single test is run 
   * @return void
   */
  protected function setUp()
  {
  }

  /**
   * Do something after a single test is run 
   * @return void
   */
  protected function tearDown()
  {
  }
  
  /**
   * Run all test methods of this test suite 
   * 
   * @return void
   */
  public function run()
  {
    $conn = $this->getDoctrineConnection();

    foreach($this->getTestMethods() as $method)
    {
      $conn->beginTransaction();
      $this->setUp();
      $this->restart();
      $this->title($this->humanize($method));

      try
      {
        $this->{$method}($browser);
      }
      catch(Exception $e)
      {
        $conn->rollBack();
        $this->tearDown();

        $msg = (string)$e;
        if(preg_match("@exception '[^']+' with message '(.*)' in ([^ ]+):(\d+)@Uis", $e, $m))
        {
          $this->test()->fail($m[1] . ' (' . basename($m[2]) . ':' . $m[3] . ')');
        }
        else
        {
          $this->test()->fail($e);
        }
        continue;
      }
      $this->tearDown();
      try
      {
        $conn->rollBack();
      }
      catch(Exception $e)
      {
      }
    }
  }

  /**
   * Get the test methods to run
   *
   * @return array
   **/
  protected function getTestMethods()
  {
    $methods = get_class_methods(get_class($this));
    foreach($methods as $index => $method)
    {
      if(!preg_match('#^test.+$#', $method))
      {
        unset($methods[$index]);
      }
    }
    
    return $methods;
  }

  /**
   * Make a method name into a nice readable string
   *
   * @param string A method name
   * @return A nice method name
   */
  protected function humanize($method)
  {
    if (substr($method, 0, 4) == 'test')
    {
      $method = substr($method, 4);
    }

    if(preg_match('@^(\d+)(.*)$@', $method, $m))
    {
      $method = preg_replace('/_/', '$1 $2', $method);
      return false;
    }

    $method = preg_replace('/([a-z])([A-Z])/', '$1 $2', $method);

    return ucfirst(strtolower($method));
  }

  /**
   * Writes a nice banner with a comment.
   *
   * @param string A comment
   * @return $this
   */
  protected function title($comment)
  {
    $char = '*';
    $sharps = str_repeat($char, 0);

    if($comment) 
    {
      $nb = 0*2 + 2 + strlen($comment);
      if($nb > 120) 
      {
        $nb = 120;
      }
      $this->test()->info(str_repeat($char, $nb));
      $this->test()->info($sharps . ' ' . $comment . ' ' . $sharps);
      $this->test()->info(str_repeat($char, $nb));
    }
    return $this;
  }

}
