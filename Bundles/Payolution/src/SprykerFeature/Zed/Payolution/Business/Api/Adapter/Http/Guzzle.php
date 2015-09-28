<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Adapter\Http;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\RequestException;
use SprykerFeature\Zed\Payolution\Business\Api\Adapter\AdapterInterface;
use SprykerFeature\Zed\Payolution\Business\Exception\ApiHttpRequestException;

class Guzzle implements AdapterInterface
{

    const DEFAULT_TIMEOUT = 45;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    private $gatewayUrl;

    /**
     * @param string $gatewayUrl
     */
    public function __construct($gatewayUrl)
    {
        $this->gatewayUrl = $gatewayUrl;
        $this->client = new Client([
            'timeout' => self::DEFAULT_TIMEOUT,
        ]);
    }

    /**
     * @param array $data
     *
     * @throws ApiHttpRequestException
     *
     * @return array
     */
    public function sendArrayDataRequest(array $data)
    {
        \SprykerFeature_Shared_Library_Log::logRaw(json_encode($data), 'payolution.log');

        $guzzleRequest = $this->client->post(
          $this->gatewayUrl,
            $headers = ['Content-Type' => 'application/x-www-form-urlencoded;charset=UTF-8'],
            $data
        );

        try {
            $response = $guzzleRequest->send();
        } catch (RequestException $requestException) {
            throw new ApiHttpRequestException($requestException->getMessage());
        }

        parse_str($response->getBody($asString = true), $out);

        \SprykerFeature_Shared_Library_Log::logRaw(json_encode($out), 'payolution.log');

        return $out;
    }

// @todo CD-408 Clarify if we want to support exchange via XML documents
//    /**
//     * @param \SimpleXMLElement $xmlElement
//     *
//     * @throws ApiHttpRequestException
//     * @return string
//     */
//    public function sendXmlRequest(\SimpleXMLElement $xmlElement)
//    {
//        $guzzleRequest = $this->client
//            ->post(
//                $this->gatewayUrl,
//                $headers = null,
//                [
//                    'load' => $xmlElement->saveXML()
//                ]
//            );
//
//        try {
//            $response = $guzzleRequest->send();
//        } catch (RequestException $requestException) {
//            throw new ApiHttpRequestException($requestException->getMessage());
//        }
//
//        return $response->getBody($asString = true);
//    }

}
