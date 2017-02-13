<?php
/**
 * @file
 */

namespace Drupal\popapi\Controller;

use Drupal\popapi\Controller\EventNodeController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class EventAttendanceController
 * @package Drupal\popapi\Controller
 */
class EventAttendanceController {

  /**
   * @param Request $request
   * @return JsonResponse
   */
  public function insertAttendee(Request $request) {
    $data = json_decode($request->getContent(), TRUE);
    $nid = $this->getEventNodeId($data['event_id']);

    if ($this->addAttendeeCount($nid)) {
      return new JsonResponse($this->getStatus(1));
    } else {
      return new JsonResponse($this->getStatus(0));
    }

  }

  /**
   * @param $event_id
   * @return mixed
   */
  private function getEventNodeId($event_id) {
    $load_id = EventNodeController::loadEventAttendeeCount($event_id);

    foreach ($load_id as $id) {
      return $id;
    }
  }

  /**
   * @param $nid
   * @return bool
   */
  private function addAttendeeCount($nid) {
    $set = EventNodeController::incrementAttendee($nid);

    if ($set) {
      return True;
    } else {
      return False;
    }
  }

  /**
   * @param $s
   * @return mixed
   */
  public function getStatus($s) {
    return $response['status'] = $s;
  }
}