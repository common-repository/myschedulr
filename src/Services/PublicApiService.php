<?php

namespace MySchedulr\Services;

use MySchedulr\Helpers\OptionsHelper;
use MySchedulr\Exceptions\SiteIdException;
use MySchedulr\Exceptions\ApiConnectionException;
use MySchedulr\Exceptions\BookingNotFoundException;

class PublicApiService
{
    public $events = [];
    public $siteId = '';
    public $baseUrl = '';
    public $event_categories = [];
    public $bookableGroupServices = [];

    public function __construct()
    {
        $this->baseUrl = MS4WP_PUBLIC_API_URL;
        $this->siteId  = OptionsHelper::getInstanceId();
    }

    /**
     * Get block data form API
     *
     * @throws SiteIdException|ApiConnectionException|BookingNotFoundException
     */
    public function getBlockDataFromApi()
    {
        $this->getGroupServicesFromApi();
        $this->getCategoriesFromApi();
        $this->getEventsFromApi();
    }

    /**
     * This get categories from the API
     *
     * @throws SiteIdException|ApiConnectionException|BookingNotFoundException
     */
    private function getCategoriesFromApi(): void
    {
        $response      = wp_remote_get($this->baseUrl . '/category/all?siteId=' . $this->siteId);
        $response_data = json_decode(wp_remote_retrieve_body($response));

        $this->verifyResponseData($response_data);

        $this->setEventCategories($response_data);
    }

    /**
     * Get events from API
     *
     * @throws SiteIdException|ApiConnectionException|BookingNotFoundException
     */
    private function getEventsFromApi()
    {
        $response      = wp_remote_get($this->baseUrl . '/appointment_type/all?siteId=' . $this->siteId);
        $response_data = json_decode(wp_remote_retrieve_body($response));

        $this->verifyResponseData($response_data);

        $this->setEvents(count($response_data) ? $this->filterAvailableEvents($response_data) : []);
    }

    /**
     * Get available group services from API
     *
     * @throws SiteIdException|ApiConnectionException|BookingNotFoundException
     */
    private function getGroupServicesFromApi()
    {
        $url = $this->baseUrl . '/appointment_type/bookable_group_services?siteId=' . $this->siteId . '&startDate=' . gmdate("Y-m-d\TH:i:s\Z");

        $response      = wp_remote_get($url);
        $response_data = json_decode(wp_remote_retrieve_body($response));

        $this->verifyResponseData($response_data);

        $this->setBookableGroupServices($response_data->services);
    }

    private function filterAvailableEvents($response_data): array
    {
        $availableEvents = [];

        foreach ($response_data as $event) {
            if ($event->service_type === 1 || $event->service_type === 2 && in_array(
                    $event->id,
                    $this->bookableGroupServices
                )) {
                $availableEvents[] = $event;
            }
        }

        return $availableEvents;
    }

    public function getEventCategories(): array
    {
        return $this->event_categories;
    }

    public function setEventCategories(array $event_categories): void
    {
        $this->event_categories = $event_categories;
    }

    public function getEvents(): array
    {
        return $this->events;
    }

    public function setEvents(array $events): void
    {
        $this->events = $events;
    }

    public function hasLinkedAccount(): bool
    {
        return (bool) $this->siteId;
    }

    /**
     * This checks if the response data is valid.
     *
     * @throws ApiConnectionException|SiteIdException|BookingNotFoundException
     */
    private function verifyResponseData($response_data): void
    {
        if (is_null($response_data)) {
            throw new ApiConnectionException();
        }

        if (is_object($response_data) && $response_data->errors->siteId) {
            throw new SiteIdException();
        }

        if (is_object($response_data) && $response_data->type === 'NotFound') {
            throw new BookingNotFoundException();
        }
    }

    public function setBookableGroupServices(array $bookableGroupServices): void
    {
        $this->bookableGroupServices = array_column($bookableGroupServices, 'service_id');
    }
}
