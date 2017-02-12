<?php
/**
 * @file
 */

namespace Drupal\popapi\Controller;

use Drupal\node\Entity\Node;
use Drupal\popapi\Manager\Apidata;
use Drupal\popapi\Manager\EventsBriteWrapper;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class EventsDataController
 * @package Drupal\popapi\Controller
 */

class EventsDataController {

  /**
   * @var EventsBriteWrapper
   * @var Apidata
   */
  protected $eventsBriteWrapper;
  protected $apidata;

  public $local_nid;
  public $local_date;

  /**
   * EventsDataController constructor.
   * @param EventsBriteWrapper $eventsBriteWrapper
   * @param Apidata $apidata
   */
  public function __construct(EventsBriteWrapper $eventsBriteWrapper, Apidata $apidata) {
    $this->eventsBriteWrapper = $eventsBriteWrapper;
    $this->apidata = $apidata;
  }

  /**
   * @param ContainerInterface $container
   * @return static
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('popapi.api_wrapper'),
      $container->get('popapi.api_data')
    );
  }

  /**
   * @return mixed
   */
  public function latestEventNode() {
    $query = \Drupal::entityQuery('node');
    $query->condition('status', 1);
    $query->condition('type', 'events');
    $query->sort('field_eventbrite_event_created', 'ASC');
    $id_array = $query->range(0, 1)->execute();

    if (count($id_array) > 0) {
      foreach ($id_array as $id) {
        $this->setLocalNodeId($id);
        return True;
      }
    } else {
      \Drupal::logger('popapi')->warning('No Node Found.');
      return False;
    }
  }

  /**
   * @return mixed
   */
  public function getEventDate() {
    $node = Node::load($this->getLocalNodeId());
    $date = $node->get('field_eventbrite_event_created')->getValue();

    foreach ($date as $dt) {
      return $this->getLocalFeedDate($dt);
    }
  }

  /**
   * @param $query
   */
  public function loadEventsbriteFeed($query) {
    $response = $this->eventsBriteWrapper->searchEvents($query);

    return $this->compareResponse($response);
  }

  public function populateEventsData($query) {
    $response = $this->eventsBriteWrapper->searchEvents($query);

    foreach ($response['events'] as $event) {
      $this->saveEvents($event);
    }
  }

  /**
   * @param $res
   */
  public function compareResponse($res) {
    foreach ($res['events'] as $event) {
      if ($event['created'] < $this->getEventDate()) {
        $this->saveEvents($event);
        \Drupal::logger('popapi')->info('New event created.');
      }

      if ($event['created'] > $this->getEventDate()) {
        \Drupal::logger('popapi')->warning('Duplicate feed detected.');
      }
    }
  }

  /**
   * @param $events
   */
  public function saveEvents($events) {

    $node = Node::create(array(
      'type' => 'events',
      'title' => $events['name']['text'],
      'body' => array(
        'value' => $events['description']['text'],
        'format' => 'basic_html',
      ),
      'field_eventbrite_event_id' => $events['id'],
      'field_eventbrite_event_url' => $events['url'],
      'field_eventbrite_event_start' => $events['start']['local'],
      'field_eventbrite_event_created' => $events['created'],
      'field_eventbrite_event_image_url' => $events['logo']['url'],
      'field_eventbrite_event_category' => $this->category($events['category_id']),
      'field_event_attendees' => 0,
    ));
    $node->save();

  }

  /**
   * @param $cat_id
   * @return mixed
   */
  public function category($cat_id) {
    if (array_key_exists($cat_id, $this->apidata->getCategories())) {
      return $this->apidata->getCategories()[$cat_id];
    }
  }

  /**
   * @param $date
   * @return mixed
   */
  public function getLocalFeedDate($date) {
    foreach ($date as $dt) {
      return $dt;
    }
  }

  public function getLocalNodeId() {
    return $this->local_nid;
  }

  public function setLocalNodeId($id) {
    $this->local_nid = $id;
  }


}