<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Sender;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductConfiguratorPageResponseTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Spryker\Client\ProductConfiguration\Dependency\External\ProductConfigurationToHttpClientInterface;
use Spryker\Client\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilEncodingServiceInterface;
use Spryker\Client\ProductConfiguration\Http\Exception\ProductConfigurationHttpRequestException;
use Spryker\Shared\Log\LoggerTrait;
use Symfony\Component\HttpFoundation\Request;

class ProductConfiguratorRequestSender implements ProductConfiguratorRequestSenderInterface
{
    use LoggerTrait;

    /**
     * @var array
     */
    protected const ASSESS_TOKEN_REQUEST_HEADERS = [
        'Content-Type' => 'application/json',
    ];

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PRODUCT_CONFIGURATION_CAN_NOT_OBTAIN_ACCESS_TOKEN = 'product_configuration.access_token.request.error.can_not_obtain_access_token';

    /**
     * @var \Spryker\Client\ProductConfiguration\Dependency\External\ProductConfigurationToHttpClientInterface
     */
    protected $httpClient;

    /**
     * @var \Spryker\Client\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Client\ProductConfiguration\Dependency\External\ProductConfigurationToHttpClientInterface $httpClient
     * @param \Spryker\Client\ProductConfiguration\Dependency\Service\ProductConfigurationToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        ProductConfigurationToHttpClientInterface $httpClient,
        ProductConfigurationToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->httpClient = $httpClient;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    public function sendProductConfiguratorAccessTokenRequest(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRedirectTransfer {
        $productConfiguratorRedirectTransfer = (new ProductConfiguratorRedirectTransfer())->setIsSuccessful(true);
        try {
            $response = $this->httpClient->request(
                Request::METHOD_POST,
                $productConfiguratorRequestTransfer->getAccessTokenRequestUrlOrFail(),
                $this->buildAccessTokenRequestOptions($productConfiguratorRequestTransfer),
            );
        } catch (ProductConfigurationHttpRequestException $productConfigurationHttpRequestException) {
            return $this->addProductConfigurationError(
                $productConfiguratorRedirectTransfer,
                static::GLOSSARY_KEY_PRODUCT_CONFIGURATION_CAN_NOT_OBTAIN_ACCESS_TOKEN,
                $productConfigurationHttpRequestException,
            );
        }

        $responseData = $this->utilEncodingService->decodeJson($response->getBody(), true) ?: [];
        $productConfiguratorPageResponseTransfer = (new ProductConfiguratorPageResponseTransfer())->fromArray($responseData);

        if ($productConfiguratorPageResponseTransfer->getIsSuccessful()) {
            return $productConfiguratorRedirectTransfer->setConfiguratorRedirectUrl(
                $productConfiguratorPageResponseTransfer->getConfiguratorRedirectUrl(),
            );
        }

        return $productConfiguratorRedirectTransfer->setIsSuccessful(false)->addMessage(
            (new MessageTransfer())->setValue($productConfiguratorPageResponseTransfer->getMessage()),
        );
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
            'json' => $productConfiguratorRequestTransfer->getProductConfiguratorRequestDataOrFail()->toArray(),
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
            $message,
            ['exception' => $configurationHttpRequestException],
        );

        return $productConfiguratorRedirectTransfer->setIsSuccessful(false)->addMessage(
            (new MessageTransfer())->setValue($message),
        );
    }
}
