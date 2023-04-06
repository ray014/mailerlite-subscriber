<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use App\Http\Helper\SubscriberHelper;

class SubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $response = SubscriberHelper::initRequest()->get(SubscriberHelper::getMailerLiteApiUrl());

        return SubscriberHelper::assignDataForDataTable($response->json()['data'], $request);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'name' => 'string|required|max:255',
            'country' => 'string|required|max:255'
        ]);

        $response = SubscriberHelper::initRequest()->post(SubscriberHelper::getMailerLiteApiUrl(), [
            'email' => $validated['email'],
            'fields' => [
                'name' => $validated['name'],
                'country' => $validated['country']
            ],
        ]);

        if ($response->getStatusCode() === 201) {
            return Response::json([], 201);
        }
        
        if (isset($response->json()['message'])) {
            $responseMsg = $response->json()['message'];
        }
        else if ($response->getStatusCode() === 200) {
            $responseMsg = 'Subscriber already exists';
        }
        else {
            $responseMsg = 'An unknown error has occured, please try again';
        }

        return Response::json(['message' => $responseMsg], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!$id) {
            return Response::json([], 400);
        }

        $response = SubscriberHelper::initRequest()->get(SubscriberHelper::getMailerLiteApiUrl() . '/' . $id);

        if (!$response->getStatusCode() === 200) {
            return Response::json([], 400);
        }

        $data = $response->json()['data'];

        return Response::json(['id'=> $data['id'], 'name' => $data['fields']['name'], 'country' => $data['fields']['country']], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!$id) {
            return Response::json(['message' => 'Error ID is not found'], 400);
        }

        $validated = $request->validate([
            'name' => 'string|required|max:255',
            'country' => 'string|required|max:255'
        ]);

        $response = SubscriberHelper::initRequest()->put(SubscriberHelper::getMailerLiteApiUrl() . '/' . $id, [
            'fields' => [
                'name' => $validated['name'],
                'country' => $validated['country']
            ],
        ]);

        if ($response->getStatusCode() === 200) {
            return Response::json([], 200);
        }

        if (isset($response->json()['message'])) {
            $responseMsg = $response->json()['message'];
        }
        else {
            $responseMsg = 'An unknown error has occured, please try again';
        }
        
        return Response::json(['message' => $responseMsg], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!$id) {
            return Response::json([], 400);
        }

        $response = SubscriberHelper::initRequest()->delete(SubscriberHelper::getMailerLiteApiUrl() . '/' . $id);

        if (!$response->getStatusCode() === 204) {
            return Response::json([], 400);
        }

        return Response::json([], 200);
    }


    /**
     * Store api key to database
     */
    public function storeApiKey(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required',
        ]);

        if (!SubscriberHelper::isApiKeyValid($validated['key'])) {
            return Response::json([], 400);
        }

        SubscriberHelper::storeApiKeyToDb($validated['key']);

        return Response::json([], 200);
    }
}
