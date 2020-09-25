<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Http;

use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use SprykerEco\Zed\Payone\Business\Exception\TimeoutException;

class GuzzleAdapter implements AdapterInterface
{
    protected const HTTP_METHOD_POST = 'POST';

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;

    /**
     * GuzzleAdapter constructor.
     *
     * @param \GuzzleHttp\ClientInterface $httpClient
     */
    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    public function sendAccessTokenRequest(ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRedirectTransfer {
        try {
            $response = $this->httpClient->request( static::HTTP_METHOD_POST,
                $productConfiguratorRequestTransfer->getAccessTokenRequestUrl(),
                ['form_params' => $productConfiguratorRequestTransfer->getProductConfiguratorRequestData()->toArray()]
            );
        } catch (ConnectException $e) {
            throw new TimeoutException('Timeout - Configurator page Communication: ' . $e->getMessage());
        } catch (ClientException $e) {
            $response = $e->getResponse();
        }

        $result = (string)$response->getBody();

        return (new ProductConfiguratorRedirectTransfer())
            ->setIsSuccessful(true)
            ->setConfiguratorRedirectUrl($result);
    }
}
