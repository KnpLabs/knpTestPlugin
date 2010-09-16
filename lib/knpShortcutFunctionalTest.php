<?php

/**
 * Base class for functional tests with convenience shortucts.
 *
 * @package knpTestPlugin
 */
class knpShortcutFunctionalTest extends knpFunctionalTest
{
  
  /**
   * Tests a user attribute value.
   *
   * @param string $key
   * @param string $value
   * @param string $ns
   *
   * @return knpLimeFunctionalTest
   */
  public function isUserAttribute($key, $value, $ns = null)
  {
    return $this->with('user')->isAttribute($key, $value, $ns);
  }
  
  /**
   * Tests a user flash value.
   *
   * @param string $key
   * @param string $value
   *
   * @return knpLimeFunctionalTest
   */
  public function isUserFlash($key, $value)
  {
    return $this->with('user')->isFlash($key, $value);
  }
  
  /**
   * Test the current module and action
   *
   * @param string $module 
   * @param string $action 
   * @return knpLimeFunctionalTest
   */
  public function isModuleAction($module, $action)
  {
    return $this->with('request')->begin()
    ->isParameter('module', $module)
    ->isParameter('action', $action)
    ->end()
    ;
  }
    
  public function isUserAuthenticated($boolean = true)
  {
    return $this->with('user')->isAuthenticated($boolean);
  }
  
  public function countMails($recipients = null, $cnt, $msg = null)
  {
    return $this->with('mail')->countMails($recipients, $cnt, $msg);
  }
  
  public function fetchMail($recipients = null)
  {
    return $this->with('mail')->fetchMail($recipients);
  }
  
  public function isMailFrom($value)
  {
    return $this->with('mail')->isFrom($value);
  }

  public function mailContains($regex)
  {
    return $this->with('mail')->contains($regex);
  }
  
  public function extractFromMail($regex, &$m)
  {
    return $this->with('mail')->extract($regex, $m);
  }
  
  public function formHasErrors($value = true)
  {
    return $this->with('form')->begin()->hasErrors($value)->end();
  }

  public function formHasGlobalError($value = true)
  {
    return $this->with('form')->hasGlobalError($value);
  }

  public function formIsError($field, $value = true)
  {
    return $this->with('form')->isError($field, $value);
  }

  public function debugForm()
  {
    return $this->with('form')->debug();
  }
  
  public function debug()
  {
    return $this->with('response')->debug();
  }
  
  public function checkResponseXpathElement($selector, $value = true, $options = array())
  {
    return $this->with('response')->checkXpathElement($selector, $value, $options);
  }
  
  public function isStatusCode($status)
  {
    $this->with('response')->isStatusCode($status);
    return $this;
  }

  public function isRequestParameter($key, $value)
  {
    $this->with('request')->isParameter($key, $value);
    return $this;
  }
  
}
