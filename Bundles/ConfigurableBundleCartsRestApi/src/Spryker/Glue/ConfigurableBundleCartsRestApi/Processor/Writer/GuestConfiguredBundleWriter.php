<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Writer;

use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestConfiguredBundlesAttributesTransfer;
use Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer;
use Spryker\Client\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiClientInterface;
use Spryker\Glue\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiConfig;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Dependency\Client\ConfigurableBundleCartsRestApiToConfigurableBundleStorageClientInterface;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Dependency\RestApiResource\ConfigurableBundleCartsRestApiToCartsRestApiResourceInterface;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Mapper\ConfiguredBundleMapperInterface;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\RestResponseBuilder\ConfiguredBundleRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Shared\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiConfig as ConfigurableBundleCartsRestApiSharedConfig;

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
     * @var \Spryker\Glue\ConfigurableBundleCartsRestApi\Dependency\Client\ConfigurableBundleCartsRestApiToConfigurableBundleStorageClientInterface
     */
    protected $configurableBundleStorageClient;

    /**
     * @var \Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Mapper\ConfiguredBundleMapperInterface
     */
    protected $configuredBundleMapper;

    /**
     * @param \Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\RestResponseBuilder\ConfiguredBundleRestResponseBuilderInterface $configuredBundleRestResponseBuilder
     * @param \Spryker\Client\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiClientInterface $configurableBundleCartsRestApiClient
     * @param \Spryker\Glue\ConfigurableBundleCartsRestApi\Dependency\RestApiResource\ConfigurableBundleCartsRestApiToCartsRestApiResourceInterface $cartsRestApiResource
     * @param \Spryker\Glue\ConfigurableBundleCartsRestApi\Dependency\Client\ConfigurableBundleCartsRestApiToConfigurableBundleStorageClientInterface $configurableBundleStorageClient
     * @param \Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Mapper\ConfiguredBundleMapperInterface $configuredBundleMapper
     */
    public function __construct(
        ConfiguredBundleRestResponseBuilderInterface $configuredBundleRestResponseBuilder,
        ConfigurableBundleCartsRestApiClientInterface $configurableBundleCartsRestApiClient,
        ConfigurableBundleCartsRestApiToCartsRestApiResourceInterface $cartsRestApiResource,
        ConfigurableBundleCartsRestApiToConfigurableBundleStorageClientInterface $configurableBundleStorageClient,
        ConfiguredBundleMapperInterface $configuredBundleMapper
    ) {
        $this->configuredBundleRestResponseBuilder = $configuredBundleRestResponseBuilder;
        $this->configurableBundleCartsRestApiClient = $configurableBundleCartsRestApiClient;
        $this->cartsRestApiResource = $cartsRestApiResource;
        $this->configurableBundleStorageClient = $configurableBundleStorageClient;
        $this->configuredBundleMapper = $configuredBundleMapper;
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
        if (!$this->findGuestCartIdentifier($restRequest)) {
            return $this->createFailedResponse(ConfigurableBundleCartsRestApiSharedConfig::ERROR_IDENTIFIER_FAILED_CART_ID_MISSING);
        }

        $configurableBundleTemplateStorageTransfer = $this->configurableBundleStorageClient
            ->findConfigurableBundleTemplateStorageByUuid(
                $restConfiguredBundlesAttributesTransfer->getTemplateUuid(),
                $restRequest->getMetadata()->getLocale()
            );

        if (!$configurableBundleTemplateStorageTransfer) {
            return $this->createFailedResponse(ConfigurableBundleCartsRestApiSharedConfig::ERROR_IDENTIFIER_CONFIGURABLE_BUNDLE_TEMPLATE_NOT_FOUND);
        }

        $createConfiguredBundleRequestTransfer = $this->createCreateConfiguredBundleRequest(
            $restRequest,
            $configurableBundleTemplateStorageTransfer,
            $restConfiguredBundlesAttributesTransfer
        );

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
        if (!$this->findGuestCartIdentifier($restRequest)) {
            return $this->createFailedResponse(ConfigurableBundleCartsRestApiSharedConfig::ERROR_IDENTIFIER_FAILED_CART_ID_MISSING);
        }

        if ($restConfiguredBundlesAttributesTransfer->getQuantity() <= 0) {
            return $this->createFailedResponse(ConfigurableBundleCartsRestApiSharedConfig::ERROR_IDENTIFIER_CONFIGURED_BUNDLE_WRONG_QUANTITY);
        }

        $updateConfiguredBundleRequestTransfer = $this->createUpdateConfiguredBundleRequest($restRequest)
            ->setGroupKey($restRequest->getResource()->getId())
            ->setQuantity($restConfiguredBundlesAttributesTransfer->getQuantity());

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
        if (!$this->findGuestCartIdentifier($restRequest)) {
            return $this->createFailedResponse(ConfigurableBundleCartsRestApiSharedConfig::ERROR_IDENTIFIER_FAILED_CART_ID_MISSING);
        }

        $updateConfiguredBundleRequestTransfer = $this->createUpdateConfiguredBundleRequest($restRequest)
            ->setGroupKey($restRequest->getResource()->getId());

        $quoteResponseTransfer = $this->configurableBundleCartsRestApiClient->removeConfiguredBundle($updateConfiguredBundleRequestTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->configuredBundleRestResponseBuilder->createFailedResponse($quoteResponseTransfer);
        }

        return $this->configuredBundleRestResponseBuilder->createRestResponse();
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
     * @param \Generated\Shared\Transfer\RestConfiguredBundlesAttributesTransfer $restConfiguredBundlesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer
     */
    public function createCreateConfiguredBundleRequest(
        RestRequestInterface $restRequest,
        ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer,
        RestConfiguredBundlesAttributesTransfer $restConfiguredBundlesAttributesTransfer
    ): CreateConfiguredBundleRequestTransfer {
        $customerTransfer = (new CustomerTransfer())
            ->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier());

        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer($customerTransfer)
            ->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier())
            ->setUuid($this->findGuestCartIdentifier($restRequest));

        $configurableBundleTransfer = (new ConfigurableBundleTemplateTransfer())->fromArray(
            $configurableBundleTemplateStorageTransfer->toArray(),
            true
        );

        $createConfiguredBundleRequestTransfer = (new CreateConfiguredBundleRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setConfiguredBundle((new ConfiguredBundleTransfer())->setTemplate($configurableBundleTransfer));

        return $this->configuredBundleMapper
            ->mapRestConfiguredBundlesAttributesToCreateConfiguredBundleRequest(
                $restConfiguredBundlesAttributesTransfer,
                $createConfiguredBundleRequestTransfer
            );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer
     */
    public function createUpdateConfiguredBundleRequest(RestRequestInterface $restRequest): UpdateConfiguredBundleRequestTransfer
    {
        $customerTransfer = (new CustomerTransfer())
            ->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier());

        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer($customerTransfer)
            ->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier())
            ->setUuid($this->findGuestCartIdentifier($restRequest));

        return (new UpdateConfiguredBundleRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setGroupKey($restRequest->getResource()->getId());
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
