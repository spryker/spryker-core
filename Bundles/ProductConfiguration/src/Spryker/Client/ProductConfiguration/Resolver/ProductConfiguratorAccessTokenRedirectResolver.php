<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Resolver;

use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Spryker\Client\ProductConfiguration\Dependency\External\ProductConfigurationToHttpClientInterface;
use Spryker\Client\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilEncodingInterface;
use Spryker\Client\ProductConfiguration\Http\Exception\ProductConfigurationHttpRequestException;
use Spryker\Shared\Log\LoggerTrait;

class ProductConfiguratorAccessTokenRedirectResolver implements ProductConfiguratorAccessTokenRedirectResolverInterface
{
    use LoggerTrait;

    protected const ASSESS_TOKEN_REQUEST_METHOD = 'POST';
    protected const ASSESS_TOKEN_REQUEST_HEADERS = [
        'Content-Type' => 'application/json',
    ];
    protected const GLOSSARY_KEY_PRODUCT_CONFIGURATION_CAN_NOT_OBTAIN_ACCESS_TOKEN = 'product_configuration.access_token.request.error.can_not_obtain_access_token';

    protected const CONFIGURATOR_REDIRECT_URL_RESPONSE_KEY = 'configuratorRedirectUrl';
    protected const IS_RESPONSE_SUCCESSFUL_KEY = 'isSuccessful';
    protected const RESPONSE_ERROR_MESSAGES_KEY = 'messages';

    /**
     * @var \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestExpanderInterface[]
     */
    protected $productConfiguratorRequestExpanderPlugins;

    /**
     * @var \Spryker\Client\ProductConfiguration\Dependency\External\ProductConfigurationToHttpClientInterface
     */
    protected $httpClient;

    /**
     * @var \Spryker\Client\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilEncodingInterface
     */
    protected $utilEncoding;

    /**
     * @param \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestExpanderInterface[] $productConfiguratorRequestExpanderPlugins
     * @param \Spryker\Client\ProductConfiguration\Dependency\External\ProductConfigurationToHttpClientInterface $httpClient
     * @param \Spryker\Client\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilEncodingInterface $utilEncoding
     */
    public function __construct(
        array $productConfiguratorRequestExpanderPlugins,
        ProductConfigurationToHttpClientInterface $httpClient,
        ProductConfigurationToUtilEncodingInterface $utilEncoding
    ) {
        $this->productConfiguratorRequestExpanderPlugins = $productConfiguratorRequestExpanderPlugins;
        $this->httpClient = $httpClient;
        $this->utilEncoding = $utilEncoding;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    public function resolveProductConfiguratorAccessTokenRedirect(
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
        $productConfiguratorRedirectTransfer = (new ProductConfiguratorRedirectTransfer())
            ->setIsSuccessful(true);

        try {
            $response = $this->httpClient->request(
                static::ASSESS_TOKEN_REQUEST_METHOD,
                $productConfiguratorRequestTransfer->getAccessTokenRequestUrl(),
                $this->buildAccessTokenRequestOptions($productConfiguratorRequestTransfer)
            );
        } catch (ProductConfigurationHttpRequestException $configurationHttpRequestException) {
            return $this->addProductConfigurationError(
                $productConfiguratorRedirectTransfer,
                static::GLOSSARY_KEY_PRODUCT_CONFIGURATION_CAN_NOT_OBTAIN_ACCESS_TOKEN,
                $configurationHttpRequestException
            );
        }

        $responseData = $this->utilEncoding->decodeJson($response->getBody(), true);

        if ($responseData[static::IS_RESPONSE_SUCCESSFUL_KEY]) {
            return $productConfiguratorRedirectTransfer
                ->setConfiguratorRedirectUrl($responseData[static::CONFIGURATOR_REDIRECT_URL_RESPONSE_KEY]);
        }

        return $productConfiguratorRedirectTransfer->setIsSuccessful(false)
            ->addMessage(static::RESPONSE_ERROR_MESSAGES_KEY);
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

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer $productConfiguratorRedirectTransfer
     * @param string $message
     * @param \Spryker\Client\ProductConfiguration\Http\Exception\ProductConfigurationHttpRequestException $configurationHttpRequestException
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    protected function addProductConfigurationError(
        ProductConfiguratorRedirectTransfer $productConfiguratorRedirectTransfer,
        string $message,
        ProductConfigurationHttpRequestException $configurationHttpRequestException
    ): ProductConfiguratorRedirectTransfer {
        $this->getLogger()->error(
            $configurationHttpRequestException->getMessage(),
            ['exception' => $configurationHttpRequestException]
        );

        return $productConfiguratorRedirectTransfer->setIsSuccessful(false)
        ->addMessage($message);
    }
}
