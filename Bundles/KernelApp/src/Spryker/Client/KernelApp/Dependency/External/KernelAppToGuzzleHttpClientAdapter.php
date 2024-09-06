<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\KernelApp\Dependency\External;

use Generated\Shared\Transfer\AcpHttpRequestTransfer;
use Generated\Shared\Transfer\AcpHttpResponseTransfer;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

class KernelAppToGuzzleHttpClientAdapter implements KernelAppToHttpClientAdapterInterface
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected ClientInterface $httpClient;

    /**
     * @param \GuzzleHttp\ClientInterface $httpClient
     */
    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param \Generated\Shared\Transfer\AcpHttpRequestTransfer $acpHttpRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AcpHttpResponseTransfer
     */
    public function send(AcpHttpRequestTransfer $acpHttpRequestTransfer): AcpHttpResponseTransfer
    {
        $request = new Request(
            $acpHttpRequestTransfer->getMethodOrFail(),
            $acpHttpRequestTransfer->getUriOrFail(),
            $acpHttpRequestTransfer->getHeaders(),
            $acpHttpRequestTransfer->getBodyOrFail(),
        );

        try {
            $response = $this->httpClient->send($request);
        } catch (RequestException $e) {
            $acpHttpResponseTransfer = new AcpHttpResponseTransfer();
            $acpHttpResponseTransfer
                ->setHttpStatusCode($e->getResponse() ? $e->getResponse()->getStatusCode() : null)
                ->setContent($e->getResponse() ? $e->getResponse()->getBody()->getContents() : null);

            return $acpHttpResponseTransfer;
        }

        $responseBody = $response->getBody()->getContents();

        $acpHttpResponseTransfer = new AcpHttpResponseTransfer();
        $acpHttpResponseTransfer
            ->setHttpStatusCode($response->getStatusCode())
            ->setContent($responseBody);

        return $acpHttpResponseTransfer;
    }
}
