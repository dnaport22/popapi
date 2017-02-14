<?php
/**
 * @file
 */

namespace Drupal\popapi\Manager;

/**
 * Class Apidata
 * @package Drupal\popapi\Manager
 */

class Apidata {

  /**
   * @mixed
   */
  private $categories;

  /**
   * Apidata constructor.
   */
  public function __construct() {
    $this->setCategories();
  }

  public function setCategories() {
    $this->categories = array(
      '' => 'Other',
      '101' => 'Business',
      '102' => 'Science & Tech',
      '103' => 'Music',
      '104' => 'Film & Media',
      '105' => 'Performing Arts',
      '106' => 'Fashion',
      '107' => 'Health',
      '108' => 'Sports & Fitness',
      '109' => 'Travel & Outdoor',
      '110' => 'Food',
      '111' => 'Spirituality',
      '112' => 'Government',
      '113' => 'Community',
      '115' => 'Family & Education',
      '116' => 'Holiday',
      '117' => 'Home & Lifestyle',
      '118' => 'Auto, Boat & Air',
      '119' => 'Hobbies',
      '199' => 'Other',
    );
  }

  /**
   * @return mixed
   */
  public function getCategories() {
    return $this->categories;
  }

}