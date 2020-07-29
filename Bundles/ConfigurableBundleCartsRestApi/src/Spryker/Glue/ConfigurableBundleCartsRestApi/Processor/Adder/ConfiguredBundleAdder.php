<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Adder;

use Generated\Shared\Transfer\RestConfiguredBundlesAttributesTransfer;
use Spryker\Client\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiClientInterface;
use Spryker\Glue\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiConfig;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Dependency\RestApiResource\ConfigurableBundleCartsRestApiToCartsRestApiResourceInterface;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\RestResponseBuilder\ConfiguredBundleRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ConfiguredBundleAdder implements ConfiguredBundleAdderInterface
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
     * @param \Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\RestResponseBuilder\ConfiguredBundleRestResponseBuilderInterface $configuredBundleRestResponseBuilder
     * @param \Spryker\Client\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiClientInterface $configurableBundleCartsRestApiClient
     * @param \Spryker\Glue\ConfigurableBundleCartsRestApi\Dependency\RestApiResource\ConfigurableBundleCartsRestApiToCartsRestApiResourceInterface $cartsRestApiResource
     */
    public function __construct(
        ConfiguredBundleRestResponseBuilderInterface $configuredBundleRestResponseBuilder,
        ConfigurableBundleCartsRestApiClientInterface $configurableBundleCartsRestApiClient,
        ConfigurableBundleCartsRestApiToCartsRestApiResourceInterface $cartsRestApiResource
    ) {
        $this->configuredBundleRestResponseBuilder = $configuredBundleRestResponseBuilder;
        $this->configurableBundleCartsRestApiClient = $configurableBundleCartsRestApiClient;
        $this->cartsRestApiResource = $cartsRestApiResource;
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
        if (!$this->validateCartIdentifier($restRequest)) {
            return $this->configuredBundleRestResponseBuilder->createCartIdMissingErrorResponse();
        }

        $createConfiguredBundleRequestTransfer = $this->createCartItemRequestTransfer($restRequest, $restCartItemsAttributesTransfer);
        $quoteResponseTransfer = $this->configurableBundleCartsRestApiClient
            ->addConfiguredBundle($createConfiguredBundleRequestTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            $this->mapper->map();

            return $this->configuredBundleRestResponseBuilder->createFailedErrorResponse();
        }

        return $this->cartsRestApiResource->createCartRestResponse(
            $quoteResponseTransfer->getQuoteTransfer(),
            $restRequest
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    protected function validateCartIdentifier(RestRequestInterface $restRequest): bool
    {
        $cartsResource = $restRequest->findParentResourceByType(ConfigurableBundleCartsRestApiConfig::RESOURCE_CARTS);

        return (bool)$cartsResource->getId();
    }
}
