<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Resolver;

use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Spryker\Client\ProductConfiguration\Http\Exception\ProductConfigurationHttpRequestException;
use Spryker\Client\ProductConfiguration\Http\ProductConfigurationGuzzleHttpClientInterface;

class ProductConfiguratorAccessTokenRedirectResolver implements ProductConfiguratorAccessTokenRedirectResolverInterface
{
    protected const ASSESS_TOKEN_REQUEST_METHOD = 'POST';
    protected const ASSESS_TOKEN_REQUEST_HEADERS = [
        'Content-Type' => 'application/json',
    ];

    /**
     * @var \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestExpanderInterface[]
     */
    protected $productConfiguratorRequestExpanderPlugins;

    /**
     * @var \Spryker\Client\ProductConfiguration\Http\ProductConfigurationGuzzleHttpClientInterface
     */
    protected $httpClient;

    /**
     * @param \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestExpanderInterface[] $productConfiguratorRequestExpanderPlugins
     * @param \Spryker\Client\ProductConfiguration\Http\ProductConfigurationGuzzleHttpClientInterface $httpClient
     */
    public function __construct(
        array $productConfiguratorRequestExpanderPlugins,
        ProductConfigurationGuzzleHttpClientInterface $httpClient
    ) {
        $this->productConfiguratorRequestExpanderPlugins = $productConfiguratorRequestExpanderPlugins;
        $this->httpClient = $httpClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    public function prepareProductConfiguratorAccessTokenRedirect(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRedirectTransfer {
        $productConfiguratorRequestTransfer = $this->executeProductConfigurationRequestExpanderPlugins(
            $productConfiguratorRequestTransfer
        );

        $productConfiguratorRequestTransfer->requireAccessTokenRequestUrl();

        return $this->sendAccessTokenRequest($productConfiguratorRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer
     */
    protected function executeProductConfigurationRequestExpanderPlugins(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRequestTransfer {
        foreach ($this->productConfiguratorRequestExpanderPlugins as $productConfiguratorRequestExpanderPlugin) {
            $productConfiguratorRequestTransfer = $productConfiguratorRequestExpanderPlugin->expand($productConfiguratorRequestTransfer);
        }

        return $productConfiguratorRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    protected function sendAccessTokenRequest(ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer): ProductConfiguratorRedirectTransfer
    {
        try {
            $response = $this->httpClient->request(
                static::ASSESS_TOKEN_REQUEST_METHOD,
                $productConfiguratorRequestTransfer->getAccessTokenRequestUrl(),
                $this->buildAccessTokenRequestOptions($productConfiguratorRequestTransfer)
            );
        } catch (ProductConfigurationHttpRequestException $configurationHttpRequestException) {
            return (new ProductConfiguratorRedirectTransfer())
                ->setIsSuccessful(false)
                ->addMessage($configurationHttpRequestException->getMessage());
        }

        return (new ProductConfiguratorRedirectTransfer())
            ->setIsSuccessful(true)
            ->setConfiguratorRedirectUrl($response->getBody());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return array
     */
    protected function buildAccessTokenRequestOptions(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): array {
        return [
            'json' => $productConfiguratorRequestTransfer->getProductConfiguratorRequestData()->toArray(),
            'headers' => static::ASSESS_TOKEN_REQUEST_HEADERS,
        ];
    }
}
