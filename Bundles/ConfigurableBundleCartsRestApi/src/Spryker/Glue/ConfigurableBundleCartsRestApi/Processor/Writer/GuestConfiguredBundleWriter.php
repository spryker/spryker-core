<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Writer;

use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\RestConfiguredBundlesAttributesTransfer;
use Spryker\Client\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiClientInterface;
use Spryker\Glue\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiConfig;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Dependency\RestApiResource\ConfigurableBundleCartsRestApiToCartsRestApiResourceInterface;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Creator\ConfiguredBundleRequestCreatorInterface;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\RestResponseBuilder\ConfiguredBundleRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Shared\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiConfig as ConfigurableBundleCartsRestApiSharedConfig;
use Symfony\Component\HttpFoundation\Response;

class GuestConfiguredBundleWriter implements GuestConfiguredBundleWriterInterface
{
    /**
     * @var \Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\RestResponseBuilder\ConfiguredBundleRestResponseBuilderInterface
     */
    protected $configuredBundleRestResponseBuilder;

    /**
     * @var \Spryker\Client\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiClientInterface
     */
    protected $configurableBundleCartsRestApiClient;

    /**
     * @var \Spryker\Glue\ConfigurableBundleCartsRestApi\Dependency\RestApiResource\ConfigurableBundleCartsRestApiToCartsRestApiResourceInterface
     */
    protected $cartsRestApiResource;

    /**
     * @var \Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Creator\ConfiguredBundleRequestCreatorInterface
     */
    protected $configuredBundleRequestCreator;

    /**
     * @param \Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\RestResponseBuilder\ConfiguredBundleRestResponseBuilderInterface $configuredBundleRestResponseBuilder
     * @param \Spryker\Client\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiClientInterface $configurableBundleCartsRestApiClient
     * @param \Spryker\Glue\ConfigurableBundleCartsRestApi\Dependency\RestApiResource\ConfigurableBundleCartsRestApiToCartsRestApiResourceInterface $cartsRestApiResource
     * @param \Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Creator\ConfiguredBundleRequestCreatorInterface $configuredBundleRequestCreator
     */
    public function __construct(
        ConfiguredBundleRestResponseBuilderInterface $configuredBundleRestResponseBuilder,
        ConfigurableBundleCartsRestApiClientInterface $configurableBundleCartsRestApiClient,
        ConfigurableBundleCartsRestApiToCartsRestApiResourceInterface $cartsRestApiResource,
        ConfiguredBundleRequestCreatorInterface $configuredBundleRequestCreator
    ) {
        $this->configuredBundleRestResponseBuilder = $configuredBundleRestResponseBuilder;
        $this->configurableBundleCartsRestApiClient = $configurableBundleCartsRestApiClient;
        $this->cartsRestApiResource = $cartsRestApiResource;
        $this->configuredBundleRequestCreator = $configuredBundleRequestCreator;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestConfiguredBundlesAttributesTransfer $restConfiguredBundlesAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addConfiguredBundle(
        RestRequestInterface $restRequest,
        RestConfiguredBundlesAttributesTransfer $restConfiguredBundlesAttributesTransfer
    ): RestResponseInterface {
        $createConfiguredBundleRequestTransfer = $this->configuredBundleRequestCreator
            ->createCreateConfiguredBundleRequest($restRequest, $restConfiguredBundlesAttributesTransfer);

        if (!$createConfiguredBundleRequestTransfer) {
            return $this->createFailedResponse(ConfigurableBundleCartsRestApiSharedConfig::ERROR_IDENTIFIER_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_FOUND);
        }

        $createConfiguredBundleRequestTransfer->getQuote()->setUuid($this->findGuestCartIdentifier($restRequest));
        $quoteResponseTransfer = $this->configurableBundleCartsRestApiClient->addConfiguredBundle($createConfiguredBundleRequestTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->configuredBundleRestResponseBuilder->createFailedResponse($quoteResponseTransfer);
        }

        return $this->cartsRestApiResource->createCartRestResponse(
            $quoteResponseTransfer->getQuoteTransfer(),
            $restRequest
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestConfiguredBundlesAttributesTransfer $restConfiguredBundlesAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function updateConfiguredBundleQuantity(
        RestRequestInterface $restRequest,
        RestConfiguredBundlesAttributesTransfer $restConfiguredBundlesAttributesTransfer
    ): RestResponseInterface {
        $quoteUuid = $this->findGuestCartIdentifier($restRequest);

        if (!$quoteUuid) {
            return $this->createFailedResponse(ConfigurableBundleCartsRestApiSharedConfig::ERROR_IDENTIFIER_FAILED_CART_ID_MISSING);
        }

        if ($restConfiguredBundlesAttributesTransfer->getQuantity() <= 0) {
            return $this->createFailedResponse(ConfigurableBundleCartsRestApiSharedConfig::ERROR_IDENTIFIER_CONFIGURED_BUNDLE_WRONG_QUANTITY);
        }

        $updateConfiguredBundleRequestTransfer = $this->configuredBundleRequestCreator
            ->createUpdateConfiguredBundleRequest($restRequest)
            ->setQuantity($restConfiguredBundlesAttributesTransfer->getQuantity());

        $updateConfiguredBundleRequestTransfer->getQuote()->setUuid($quoteUuid);
        $quoteResponseTransfer = $this->configurableBundleCartsRestApiClient->updateConfiguredBundleQuantity($updateConfiguredBundleRequestTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->configuredBundleRestResponseBuilder->createFailedResponse($quoteResponseTransfer);
        }

        return $this->cartsRestApiResource->createCartRestResponse(
            $quoteResponseTransfer->getQuoteTransfer(),
            $restRequest
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function deleteConfiguredBundle(RestRequestInterface $restRequest): RestResponseInterface
    {
        $quoteUuid = $this->findGuestCartIdentifier($restRequest);

        if (!$quoteUuid) {
            return $this->createFailedResponse(ConfigurableBundleCartsRestApiSharedConfig::ERROR_IDENTIFIER_FAILED_CART_ID_MISSING);
        }

        $updateConfiguredBundleRequestTransfer = $this->configuredBundleRequestCreator->createUpdateConfiguredBundleRequest($restRequest);
        $updateConfiguredBundleRequestTransfer->getQuote()->setUuid($quoteUuid);

        $quoteResponseTransfer = $this->configurableBundleCartsRestApiClient->removeConfiguredBundle($updateConfiguredBundleRequestTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->configuredBundleRestResponseBuilder->createFailedResponse($quoteResponseTransfer);
        }

        return $this->configuredBundleRestResponseBuilder->createRestResponse()
            ->setStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return string|null
     */
    protected function findGuestCartIdentifier(RestRequestInterface $restRequest): ?string
    {
        $cartsResource = $restRequest->findParentResourceByType(ConfigurableBundleCartsRestApiConfig::RESOURCE_GUEST_CARTS);

        return $cartsResource ? $cartsResource->getId() : null;
    }

    /**
     * @param string $errorIdentifier
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createFailedResponse(string $errorIdentifier): RestResponseInterface
    {
        $quoteResponseTransfer = (new QuoteResponseTransfer())
            ->addError((new QuoteErrorTransfer())->setErrorIdentifier($errorIdentifier));

        return $this->configuredBundleRestResponseBuilder->createFailedResponse($quoteResponseTransfer);
    }
}
