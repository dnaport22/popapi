<?php

use Drupal\popapi\Manager\TokenManager;
use Drupal\popapi\Manager\EventsBriteWrapper;
use Drupal\popapi\Http\FetchEvents;
use Drupal\popapi\Controller\EventsDataController;
use Drupal\popapi\Manager\Apidata;

function searchEvents($query) {
  $token = new TokenManager();
  $http = new FetchEvents();
  $data = new Apidata();
  $wrapper = new EventsBriteWrapper($token, $http);
  $data_ctrl = new EventsDataController($wrapper, $data);

  //$x = $data_ctrl->latestEventNode();

//  if ($x) {
//    $data_ctrl->loadEventsbriteFeed($query);
//  }

  //$data_ctrl->populateEventsData($query);

  var_dump($data_ctrl->insertAttendee());

}

searchEvents('lsbu');