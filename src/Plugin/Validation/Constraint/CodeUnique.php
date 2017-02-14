<?php
/**
 * @file
 */

namespace Drupal\popapi\Plugin\Validation\Constraint;

use Drupal\Core\Validation\Plugin\Validation\Constraint\UniqueFieldConstraint;

/**
* Checks if a field is unique.
*
* @Constraint(
*   id = "MyModuleCodeUnique",
*   label = @Translation("MyModule code unique", context = "Validation"),
* )
*/
class CodeUnique extends UniqueFieldConstraint {

  public $message = 'The code %value is already taken.';

}