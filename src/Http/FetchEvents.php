<?php
/**
 * @file
 */

namespace Drupal\popapi\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Class FetchEvents
 * @package Drupal\popapi\Http
 *
 * In the area you want to return the result, using any URL for $url:
 * $events = new FetchEvents();
 * $response = $events->requestEvents($url);
 */

class FetchEvents {
  use StringTranslationTrait;

  /**
   * @var Client
   */
  private $client;

  /**
   * FetchEvents constructor.
   */
  public function __construct() {
    $this->client = new \GuzzleHttp\Client();
  }

  /**
   * @param $endpoint
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup|\Psr\Http\Message\StreamInterface
   */
  public function Httpget($endpoint) {
    try {
      $response = $this->getClient()->get($endpoint, ['http_error' => false]);
      $res = json_decode($response->getBody(), true);
      return($res);
    } catch (RequestException $e) {
      return(\Drupal::logger('popapi')->error('Error occured while fetching feeds from eventsbrite.'));
    }
  }

  /**
   * @return Client
   */
  public function getClient() {
    return $this->client;
  }
}