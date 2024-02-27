<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use NextBasket\Services\RabbitMQBroker;
use NextBasket\Utils\Log;
use Symfony\Component\HttpFoundation\Response as HTTPResponse;

class UserController extends Controller
{
    public function store(Request $request)
    {

        if ( !$request->filled(['email', 'firstName', 'lastName'])) {
            return response()->json(['message' => 'You are missing required information'], HTTPResponse::HTTP_BAD_REQUEST);
        }

        $data = $request->only(['email', 'firstName', 'lastName']);

        // store to log file.
        try {
            $log = new Log(storage_path('logs/app.log'));
            $log->write(json_encode($data) . "\n");
        } catch (Exception $e) {
            return response()->json(['message' => 'Internal Server Error'], HTTPResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        // Fire notification event.
        $broker = new RabbitMQBroker($this->getBrokerConfig());
        $broker->send(json_encode($data), "notifications");

        return response()->json(['message' => 'Saved!']);
    }

    /**
     * Get broker config data
     *
     * @return array
     */
    protected function getBrokerConfig() :array
    {
        return [
            'host' => env('RABBITMQ_HOST'),
            'port' => env('RABBITMQ_PORT'),
            'user' => env('RABBITMQ_USER', 'user'),
            'password' => env('RABBITMQ_PASSWORD', 'password')
        ];
    }
}
