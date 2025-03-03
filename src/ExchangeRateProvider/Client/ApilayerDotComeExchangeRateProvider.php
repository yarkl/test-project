<?php

declare(strict_types=1);

namespace Test\CommissionRateCalculator\ExchangeRateProvider\Client;

use Exception;

class ApilayerDotComeExchangeRateProvider implements ExchangeApiRequesterInterface
{

    public function __construct(public string $apiKey, public string $apiUrl)
    {}

    /**
     * @throws Exception
     */
    public function fetch(): array
    {
        $response = $this->sendRequest();

        return $this->parseResponse($response);
    }

    /**
     * @throws Exception
     */
    public function sendRequest(): string
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_HTTPHEADER => [
                "Content-Type: text/plain",
                "apikey: $this->apiKey"
            ],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET"
        ]);

        $response = curl_exec($curl);
        $error = curl_error($curl);

        curl_close($curl);

        if ($error) {
            throw new Exception("cURL Error: " . $error);
        }

        return $response;
    }

    /**
     * @throws Exception
     */
    private function parseResponse(string $response): array
    {
        if (json_validate($response)) {
            return json_decode($response, true);
        }

        throw new Exception('Invalid JSON response from API');
    }
}
