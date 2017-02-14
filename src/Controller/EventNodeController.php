<?php
/**
 * @file
 */

namespace Drupal\popapi\Controller;

use Drupal\node\Entity\Node;

/**
 * Class EventNodeController
 * @package Drupal\popapi\Controller
 */
class EventNodeController {

  /**
   * @return array|int
   */
  public static function loadLatestEventDate() {
    $query = \Drupal::entityQuery('node');
    $query->condition('status', 1);
    $query->condition('type', 'events');
    $query->sort('field_eventbrite_event_created', 'ASC');
    $nid = $query->range(0, 1)->execute();

    return $nid;
  }

  // Method to fetch events and add up in json the send to client. Not critical atm.
  public static function loadEventNids() {
    $query = \Drupal::entityQuery('node');
    $query->condition('status', 1);
    $query->condition('type', 'events');
    $query->sort('field_eventbrite_event_start', 'ASC');
    $nid = $query->execute();

    return $nid;
  }

  public static function loadEventContent($ids) {

    return $ids;
  }

  /**
   * @param $eventid
   * @return array|int
   */
  public static function loadEventAttendeeCount($eventid) {
    $query = \Drupal::entityQuery('node');
    $query->condition('status', 1);
    $query->condition('type', 'events');
    $query->condition('field_eventbrite_event_id', $eventid);
    $nid = $query->execute();

    return $nid;
  }

  /**
   * @param $nid
   * @return bool
   */
  public static function incrementAttendee($nid) {
    $node = Node::load($nid);
    $att = $node->get('field_event_attendees')->getValue();
    $att_count = null;

    foreach ($att as $count) {
      foreach ($count as $y) {
        $att_count = $y;
      }
    }

    $node->field_event_attendees = $att_count+1;

    if ($node->save()) {
      return True;
    } else {
      return False;
    }
  }

}