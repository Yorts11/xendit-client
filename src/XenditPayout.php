<?php
namespace Yorts\Xendit;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\ClientException;

class XenditPayout{
    const API_BASE = 'https://api.xendit.co/v2/';

    protected $apiKey;
    protected $client;
    protected $url;
    protected static $customUrl = 'https://api.xendit.co';

    public function __construct($apiKey)
    {
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
            'json' => [
                'apikey' => $this->apiKey,
                'reference_id' => $params['reference_id'],
                'channel_code' => $params['channel_code'],
                'channel_properties' => $params['channel_properties'],
                'amount' => $params['amount'],
                'currency' => $params['currency'],
                'description' => $params['description'],
            ]
        ];

        // Check if receipt_notification is provided and not empty
        if (isset($params['receipt_notification']) && !empty($params['receipt_notification'])) {
            $requestParams['json']['receipt_notification'] = $params['receipt_notification'];
        }

        // Check if metadata is provided and not empty
        if (isset($params['metadata']) && !empty($params['metadata'])) {
            $requestParams['json']['metadata'] = $params['metadata'];
        }

        $response = $this->client->post('payouts', $requestParams);

        $body = $response->getBody()->getContents();
        $decodedBody = json_decode($body, true);
        return $decodedBody;
    }

    public function getPayoutReferenceId(string $reference_id){
        try{
            $response = $this->client->get('payouts', [
                'query' => ['reference_id' => $reference_id]
            ]);

            $body = $response->getBody()->getContents();
            $decodedBody = json_decode($body, true);

            return $decodedBody;
        }catch (ClientException $e){
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $decodedBody = json_decode($body, true);

            // Handle the error and return the error message
            return [
                'error' => true,
                'status_code' => $statusCode,
                'message' => $decodedBody['message']
            ];
        }
    }

    public function getPayoutId(string $id){
        try{
            $response = $this->client->get('payouts/'.$id);
            $body = $response->getBody()->getContents();
            $decodedBody = json_decode($body, true);

            return $decodedBody;
        }catch(ClientException $e){
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $decodedBody = json_decode($body, true);

            // Handle the error and return the error message
            return [
                'error' => true,
                'status_code' => $statusCode,
                'message' => $decodedBody['message']
            ];
        }
    }

    public function payoutCancel(string $id){
        try {
            $response = $this->client->post('payouts/'.$id.'/cancel');
            $body = $response->getBody()->getContents();
            $decodedBody = json_decode($body, true);

            return $decodedBody;
        } catch (ClientException $e) {
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();
            $decodedBody = json_decode($body, true);

            // Handle the error and return the error message
            return [
                'error' => true,
                'status_code' => $statusCode,
                'message' => $decodedBody['message'] ?? 'Error occurred during payout cancellation.'
            ];
        }
    }

    public function getPayoutChannels(string $currency = null, string $channel_category = null){
        $query = [];

        if (!empty($currency)) {
            $query['currency'] = $currency;
        }

        if (!empty($channel_category)) {
            $query['channel_category'] = $channel_category;
        }

        $url = self::$customUrl ?? self::API_BASE;

        $response = $this->client->get($url.'/payouts_channels', [
            'query' => $query
        ]);

        $body = $response->getBody()->getContents();
        $decodedBody = json_decode($body, true);

        return $decodedBody;
    }

}
