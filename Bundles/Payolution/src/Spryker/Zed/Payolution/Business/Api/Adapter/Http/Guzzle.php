<?php

/**
 * This file is part of the Spryker Platform.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Payolution\Business\Api\Adapter\Http;

use Guzzle\Http\Client as GuzzleClient;
use Guzzle\Http\Exception\RequestException;
use Spryker\Zed\Payolution\Business\Exception\ApiHttpRequestException;

class Guzzle extends AbstractHttpAdapter
{

    const DEFAULT_TIMEOUT = 45;

    /**
     * @var \Guzzle\Http\Client
     */
    protected $client;

    /**
     * @param string $gatewayUrl
     * @param string $contentType
     */
    public function __construct($gatewayUrl, $contentType)
    {
        parent::__construct($gatewayUrl, $contentType);

        $this->client = new GuzzleClient([
            'timeout' => self::DEFAULT_TIMEOUT,
        ]);
    }

    /**
     * @param array|string $data
     *
     * @return \Guzzle\Http\Message\RequestInterface
     */
    protected function buildRequest($data)
    {
        return $this->client->post(
            $this->gatewayUrl,
            ['Content-Type' => self::$requestContentTypes[$this->contentType]],
            $data
        );
    }

    /**
     * @param \Guzzle\Http\Message\RequestInterface $request
     * @param string $user
     * @param string $password
     *
     * @return void
     */
    protected function authorizeRequest($request, $user, $password)
    {
        $request->setAuth($user, $password);
    }

    /**
     * @param \Guzzle\Http\Message\RequestInterface $request
     *
     * @throws \Spryker\Zed\Payolution\Business\Exception\ApiHttpRequestException
     *
     * @return string
     */
    protected function send($request)
    {
        try {
            $response = $request->send();
        } catch (RequestException $requestException) {
            throw new ApiHttpRequestException($requestException->getMessage());
        }

        return $response->getbody(true);
    }

}
