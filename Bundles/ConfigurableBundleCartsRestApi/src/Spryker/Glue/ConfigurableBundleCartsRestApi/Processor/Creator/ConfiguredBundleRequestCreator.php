<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Creator;

use Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer;
use Generated\Shared\Transfer\ConfigurableBundleTemplateTransfer;
use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\RestConfiguredBundlesAttributesTransfer;
use Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Dependency\Client\ConfigurableBundleCartsRestApiToConfigurableBundleStorageClientInterface;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Mapper\ConfiguredBundleMapperInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ConfiguredBundleRequestCreator implements ConfiguredBundleRequestCreatorInterface
{
    /**
     * @var \Spryker\Glue\ConfigurableBundleCartsRestApi\Dependency\Client\ConfigurableBundleCartsRestApiToConfigurableBundleStorageClientInterface
     */
    protected $configurableBundleStorageClient;

    /**
     * @var \Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Mapper\ConfiguredBundleMapperInterface
     */
    protected $configuredBundleMapper;

    /**
     * @param \Spryker\Glue\ConfigurableBundleCartsRestApi\Dependency\Client\ConfigurableBundleCartsRestApiToConfigurableBundleStorageClientInterface $configurableBundleStorageClient
     * @param \Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Mapper\ConfiguredBundleMapperInterface $configuredBundleMapper
     */
    public function __construct(
        ConfigurableBundleCartsRestApiToConfigurableBundleStorageClientInterface $configurableBundleStorageClient,
        ConfiguredBundleMapperInterface $configuredBundleMapper
    ) {
        $this->configurableBundleStorageClient = $configurableBundleStorageClient;
        $this->configuredBundleMapper = $configuredBundleMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestConfiguredBundlesAttributesTransfer $restConfiguredBundlesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer|null
     */
    public function createCreateConfiguredBundleRequest(
        RestRequestInterface $restRequest,
        RestConfiguredBundlesAttributesTransfer $restConfiguredBundlesAttributesTransfer
    ): ?CreateConfiguredBundleRequestTransfer {
        $configurableBundleTemplateStorageTransfer = $this->configurableBundleStorageClient
            ->findConfigurableBundleTemplateStorageByUuid(
                $restConfiguredBundlesAttributesTransfer->getTemplateUuid(),
                $restRequest->getMetadata()->getLocale()
            );

        if (!$configurableBundleTemplateStorageTransfer) {
            return null;
        }

        return $this->mapCreateConfiguredBundleRequest(
            $restRequest,
            $configurableBundleTemplateStorageTransfer,
            $restConfiguredBundlesAttributesTransfer
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
            ->setIdCustomer($restRequest->getRestUser()->getSurrogateIdentifier())
            ->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier());

        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer($customerTransfer)
            ->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier());

        return (new UpdateConfiguredBundleRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setGroupKey($restRequest->getResource()->getId());
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer
     * @param \Generated\Shared\Transfer\RestConfiguredBundlesAttributesTransfer $restConfiguredBundlesAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer
     */
    protected function mapCreateConfiguredBundleRequest(
        RestRequestInterface $restRequest,
        ConfigurableBundleTemplateStorageTransfer $configurableBundleTemplateStorageTransfer,
        RestConfiguredBundlesAttributesTransfer $restConfiguredBundlesAttributesTransfer
    ): CreateConfiguredBundleRequestTransfer {
        $customerTransfer = (new CustomerTransfer())
            ->setIdCustomer($restRequest->getRestUser()->getSurrogateIdentifier())
            ->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier());

        $quoteTransfer = (new QuoteTransfer())
            ->setCustomer($customerTransfer)
            ->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier());

        $configurableBundleTemplateTransfer = (new ConfigurableBundleTemplateTransfer())->fromArray(
            $configurableBundleTemplateStorageTransfer->toArray(),
            true
        );

        $createConfiguredBundleRequestTransfer = (new CreateConfiguredBundleRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setConfiguredBundle((new ConfiguredBundleTransfer())->setTemplate($configurableBundleTemplateTransfer));

        return $this->configuredBundleMapper
            ->mapRestConfiguredBundlesAttributesToCreateConfiguredBundleRequest(
                $restConfiguredBundlesAttributesTransfer,
                $createConfiguredBundleRequestTransfer
            );
    }
}
