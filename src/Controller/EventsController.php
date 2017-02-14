<?php
/**
 * @file
 */

namespace Drupal\popapi\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\popapi\Manager\EventsBriteWrapper;
use Drupal\popapi\Manager\Apidata;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\popapi\Manager\TokenManager;
use Drupal\popapi\Controller\EventsDataController;

/**
 * Class EventsController
 * @package Drupal\popapi\Controller
 */

class EventsController extends ControllerBase {

  /**
   * @var Apidata
   */
  protected $apiData;

  /**
   * EventsController constructor.
   * @param Apidata $apiData
   */
  public function __construct(ApiData $apiData) {
    $this->apiData = $apiData;
  }

  /**
   * @param ContainerInterface $container
   * @return static
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('popapi.api_data')
    );
  }

  /**
   * {$inheritdoc}
   *
   * TODO: Needs so much cleaning and refactoring, this is just for experimenting.
   * TODO: Once all the essential experiments pass, have to refactor code.
   */
  public function content() {



    $data = $node->loadEventsbriteFeed();
    foreach ($data['events'] as $event) {
      if ($event['start']['local'] < $date) {
        $this->saveEvents($event);
      }

      if ($event['start']['local'] > $date) {
        \Drupal::logger('popapi')->warning('Duplicate feeds detected.');
      }
    }

    $build = array(
      '#type' => 'markup',
      '#markup' => 'Success!',
    );
    return $build;

  }


  public function saveEvents($events) {

    $mydata = array();

    $node = Node::create(array(
      'type' => 'events',
      'title' => $events['name']['text'],
      'body' => array(
        'value' => $events['description']['text'],
        'format' => 'basic_html',
      ),
      'field_date' => $events['start']['local'],
      'field_image_url' => $events['logo']['url'],
      'field_category' => $this->category($events['category_id']),
      'field_event_id' => $events['id'],
      'field_date_created' => $events['created'],
    ));
    $node->save();

  }

  public function category($cat_id) {
    if (array_key_exists($cat_id, $this->apiData->getCategories())) {
      return $this->apiData->getCategories()[$cat_id];
    }
  }

}
