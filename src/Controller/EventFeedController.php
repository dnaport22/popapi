<?php
/**
 * @file
 */

namespace Drupal\popapi\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class EventFeedController
 * @package Drupal\popapi\Controller
 */
class EventFeedController {

  public function fetchEvents(Request $request) {
    $nids = EventNodeController::loadEventNids();
    $content = EventNodeController::loadEventContent($nids);
    return new JsonResponse($content);
  }
}