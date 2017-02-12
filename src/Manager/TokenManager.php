<?php
/**
 * @file
 */

namespace Drupal\popapi\Manager;

/**
 * Class TokenManager
 * @package Drupal\popapi\Manager
 */

class TokenManager {

  protected $eventsbrite_token;
  protected $eventsbrite_endpoint;

  /**
   * TokenManager constructor.
   */
  public function __construct() {
    $this->eventsbrite_endpoint = 'https://www.eventbriteapi.com/v3';
    $this->eventsbrite_token = '4GD7NTDMEHS6K7GSVVLJ';
  }

  /**
   * @return string
   */
  public function getEventsBriteToken() {
    return $this->eventsbrite_token;
  }

  /**
   * @return string
   */
  public function getEventsBriteEndpoint() {
    return $this->eventsbrite_endpoint;
  }

}
