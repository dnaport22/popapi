<?php
/**
 * @file
 */

namespace Drupal\popapi\Manager;

use Drupal\popapi\Http\FetchEvents;
use Drupal\popapi\Manager\TokenManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class EventsBriteWrapper
 * @package Drupal\popapi\Manager
 */

class EventsBriteWrapper {

  /**
   * @var TokenManager
   */
  protected $tokenManager;
  protected $eventFetcher;

  public $searchPageNumber;
  public $searchQuery;

  /**
   * EventsBriteWrapper constructor.
   * @param TokenManager $tokenManager
   */
  public function __construct(TokenManager $tokenManager, FetchEvents $fetchEvents) {
    $this->tokenManager = $tokenManager;
    $this->eventFetcher = $fetchEvents;
  }

  /**
   * @param ContainerInterface $container
   * @return static
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('popapi.api_data'),
      $container->get('popapi.api_fetcher')
    );
  }

  /**
   * @param $value
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup|\Psr\Http\Message\StreamInterface
   */
  public function searchEvents($value) {
    $this->setDefaultSearchPage(1);

    $endpoint = $this->prepareSearchLink($value);
    $response = $this->eventFetcher->Httpget($endpoint);

    return $response;
  }

  /**
   * @param $data
   * @return string
   */
  public function prepareSearchLink($data) {
    $link = $this->tokenManager->getEventsBriteEndpoint()
      . '/events/search/?token=' . $this->tokenManager->getEventsBriteToken()
      . '&q=' . $data . '&sort_by=date&page=' . $this->getDefaultSearchPage();

    return $link;
  }

  public function setDefaultSearchPage($page) {
    $this->searchPageNumber = $page;
  }

  public function getDefaultSearchPage() {
    return $this->searchPageNumber;
  }

  public function setSearchQuery($query) {
    $this->searchQuery = $query;
  }

  public function getSearchQuery() {
    return $this->searchQuery;
  }

}