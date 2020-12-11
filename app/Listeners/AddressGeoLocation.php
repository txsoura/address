<?php

namespace App\Listeners;

use App\Events\AddressCreated;
use App\Models\Address;
use GuzzleHttp\Client;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddressGeoLocation implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AddressCreated  $event
     * @return void
     */
    public function handle(AddressCreated $event)
    {
        $geocode = config('services.maps');

        $address = $event->address;

        $formatedAddress = $this->formatedAddress($address);

        $response = $this->api($geocode, $formatedAddress);
        if ($response) {
            $address->longitude = json_encode($response->results[0]->geometry->location->lng);
            $address->latitude = json_encode($response->results[0]->geometry->location->lat);
            $address->update();
        }
    }

    public function api($geocode, $address)
    {
        $client = new Client();
        $response = $client->request(
            'GET',
            $geocode['url'] . '?address=' . $address . '&key=' . $geocode['key'],
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Cache-Control' => 'no-cache',
                    'Content-Type' => 'application/json'
                ]
            ]
        );

        $response = json_decode($response->getBody());

        switch ($response->status) {
            case 'OVER_DAILY_LIMIT':
                \Log::error('MAPS_RESPONSE:' . $response->status);
                return;
                break;

            case 'OVER_QUERY_LIMIT':
                \Log::error('MAPS_RESPONSE:' . $response->status);
                return;
                break;

            case 'REQUEST_DENIED':
                \Log::error('MAPS_RESPONSE:' . $response->status);
                return;
                break;

            case 'INVALID_REQUEST':
                \Log::error('MAPS_RESPONSE:' . $response->status);
                return;
                break;

            case 'UNKNOWN_ERROR':
                \Log::error('MAPS_RESPONSE:' . $response->status);
                return;
                break;

            case 'ZERO_RESULTS':
                \Log::info('MAPS_RESPONSE:' . $response->status);
                return;
                break;

            default:
                \Log::info('MAPS_RESPONSE:' . $response->status);
                return $response;
                break;
        }
    }

    public function formatedAddress(Address $address)
    {
        return
            $address->street . ', ' .
            $address->number . ' - ' .
            $address->district . ', ' .
            $address->city->name;
    }
}
