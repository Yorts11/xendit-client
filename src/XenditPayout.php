<?php
namespace Yorts\Xendit;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class XenditPayout{
    const API_BASE = 'https://api.xendit.co/v2/';

    protected $apiKey;
    protected $client;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client = new Client([
            'base_uri' => self::API_BASE,
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($apiKey . ':'),
                'Idempotency-key' => time(),
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function createPayout(array $params){
        if(!isset($params['reference_id']) || empty($params['reference_id'])){
            $errorResponse = [
                'error_code' => '400',
                'message' => 'Reference ID is required and cannot be empty.'
            ];
            return json_encode($errorResponse);
        }
        if(!isset($params['channel_code']) || empty($params['channel_code'])){
            $errorResponse = [
                'error_code' => '400',
                'message' => 'Channel Code is required and cannot be empty.'
            ];
            return json_encode($errorResponse);
        }
        if(!isset($params['channel_properties']['account_holder_name']) || empty($params['channel_properties']['account_holder_name'])){
            $errorResponse = [
                'error_code' => '400',
                'message' => 'Account Holder Name is required and cannot be empty.'
            ];
            return json_encode($errorResponse);
        }
        if(!isset($params['channel_properties']['account_number']) || empty($params['channel_properties']['account_number'])){
            $errorResponse = [
                'error_code' => '400',
                'message' => 'Account Number is required and cannot be empty.'
            ];
            return json_encode($errorResponse);
        }
        if(!isset($params['amount']) || empty($params['amount'])){
            $errorResponse = [
                'error_code' => '400',
                'message' => 'Amount is required and cannot be empty.'
            ];
            return json_encode($errorResponse);
        }
        if(!isset($params['currency']) || empty($params['currency'])){
            $errorResponse = [
                'error_code' => '400',
                'message' => 'Currency is required and cannot be empty.'
            ];
            return json_encode($errorResponse);
        }

        $requestParams = [
            'form_params' => [
                'apikey' => $this->apiKey,
                'reference_id' => $params['reference_id'],
                'channel_code' => $params['channel_code'],
                'channel_properties' => $params['channel_properties'],
                'amount' => $params['amount'],
                'description' => $params['description'],
                'currency' => $params['currency'],
                'receipt_notification' => $params['receipt_notification'],
                'metadata' => $params['metadata'],
            ]
        ];

        $response = $this->client->post('payouts', $requestParams);

        return $response->getBody();
    }

    public function getPayoutReferenceId(string $reference_id){
        $response = $this->client->get('payouts?reference_id='.$reference_id);

        return $response->getBody();
    }

    public function getPayoutId(string $id){
        $response = $this->client->get('payouts/'.$id);
        return $response->getBody();
    }
}
