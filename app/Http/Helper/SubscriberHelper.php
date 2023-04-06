<?php

namespace App\Http\Helper;

use App\Models\ApiKey;
use Illuminate\Support\Facades\Http;

class SubscriberHelper
{
    /**
     * Return Mailerlite api url
     */
    public static function getMailerLiteApiUrl() {
        return 'https://connect.mailerlite.com/api/subscribers';
    }

    /**
     * Initialize mailerlite http request
     */
    public static function initRequest($apiKey='')
    {
        if (!$apiKey) {
            $apiKey = self::getApiKeyFromDb();
        }

        return Http::withoutVerifying()
        ->withToken($apiKey);
    }

    /**
     * Check if mailerlite api key is valid
     */
    public static function isApiKeyValid($apiKey) {
        $response = self::initRequest($apiKey)->get(self::getMailerLiteApiUrl());

        return $response->getStatusCode() !== 401;
    }

    /**
     * Get api key from database
     */
    public static function getApiKeyFromDb() {
        $data = ApiKey::first();

        return $data ? $data->api_key : '';
    }

    public static function storeApiKeyToDb($apiKey) {
        $user = new ApiKey;
 
        $user->api_key = $apiKey;
 
        $user->save();
    }

    /**
     * Define data for datatable rows
     */
    public static function assignDataForDataTable($subscribers, $request) {
        $data = ['data' => [], 'recordsTotal' => 0, 'recordsFiltered' => 0];
        $start = $request->start ? $request->start : 0;
        $length = $request->length ? $request->length : 10;
        $search = isset($request->search['value']) ? trim($request->search['value']) : '';
        $data['recordsTotal'] = count($subscribers);
        $n = 0;

        if ($search) {
            $subscribers = self::filterSubscribers($subscribers, $search);
        }

        foreach($subscribers as $subscriber) {
            $n++;

            if ($start >= $n) {
                continue;
            }

            $data['data'][] = [
                'id' => $subscriber['id'],
                'email' => $subscriber['email'],
                'name' => $subscriber['fields']['name'],
                'country' => $subscriber['fields']['country'],
                'subscribe_date' => $subscriber['subscribed_at'] ? date('d/m/Y', strtotime($subscriber['subscribed_at'])) : '',
                'subscribe_time' => $subscriber['subscribed_at'] ? date('H:i', strtotime($subscriber['subscribed_at'])) : '',
            ];
            
            if ($n >= ($start + $length)) {
                break;
            }
        }

        $data['recordsFiltered'] = count($subscribers);

        return $data;
    }

    /**
     * Filter subscribers data based on the search value
     */
    public static function filterSubscribers($subscribers, $search) {
        $filteredSubscribers = [];

        foreach($subscribers as $subscriber) {
            $search = strtolower($search);

            $date = $subscriber['subscribed_at'] ? date('d/m/Y', strtotime($subscriber['subscribed_at'])) : '';
            $time = $subscriber['subscribed_at'] ? date('H:i', strtotime($subscriber['subscribed_at'])) : '';

            if (
                ($subscriber['email'] && str_contains(strtolower($subscriber['email']), $search)) ||
                ($subscriber['fields']['name'] && str_contains(strtolower($subscriber['fields']['name']), $search)) ||
                ($subscriber['fields']['country'] && str_contains(strtolower($subscriber['fields']['country']), $search)) ||
                ($date && str_contains($date, $search)) ||
                ($time && str_contains($time, $search))
            ) {

                $filteredSubscribers[] = $subscriber;
            }
        }

        return $filteredSubscribers;
    }
}