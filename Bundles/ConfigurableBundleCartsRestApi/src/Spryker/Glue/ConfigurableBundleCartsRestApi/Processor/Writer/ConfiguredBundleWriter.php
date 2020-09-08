<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Writer;

use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;
use Generated\Shared\Transfer\RestConfiguredBundlesAttributesTransfer;
use Spryker\Client\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiClientInterface;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Dependency\RestApiResource\ConfigurableBundleCartsRestApiToCartsRestApiResourceInterface;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Mapper\ConfigurableBundleCartMapperInterface;
use Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\RestResponseBuilder\ConfiguredBundleRestResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponse;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ConfiguredBundleWriter implements ConfiguredBundleWriterInterface
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
     * @var \Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Mapper\ConfigurableBundleCartMapperInterface
     */
    protected $configurableBundleCartMapper;

    /**
     * @param \Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\RestResponseBuilder\ConfiguredBundleRestResponseBuilderInterface $configuredBundleRestResponseBuilder
     * @param \Spryker\Client\ConfigurableBundleCartsRestApi\ConfigurableBundleCartsRestApiClientInterface $configurableBundleCartsRestApiClient
     * @param \Spryker\Glue\ConfigurableBundleCartsRestApi\Dependency\RestApiResource\ConfigurableBundleCartsRestApiToCartsRestApiResourceInterface $cartsRestApiResource
     * @param \Spryker\Glue\ConfigurableBundleCartsRestApi\Processor\Mapper\ConfigurableBundleCartMapperInterface $configurableBundleCartMapper
     */
    public function __construct(
        ConfiguredBundleRestResponseBuilderInterface $configuredBundleRestResponseBuilder,
        ConfigurableBundleCartsRestApiClientInterface $configurableBundleCartsRestApiClient,
        ConfigurableBundleCartsRestApiToCartsRestApiResourceInterface $cartsRestApiResource,
        ConfigurableBundleCartMapperInterface $configurableBundleCartMapper
    ) {
        $this->configuredBundleRestResponseBuilder = $configuredBundleRestResponseBuilder;
        $this->configurableBundleCartsRestApiClient = $configurableBundleCartsRestApiClient;
        $this->cartsRestApiResource = $cartsRestApiResource;
        $this->configurableBundleCartMapper = $configurableBundleCartMapper;
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
        // TODO: validation

        $createConfiguredBundleRequestTransfer = $this->configurableBundleCartMapper
            ->mapRestConfiguredBundlesAttributesToCreateConfiguredBundleRequest(
                $restConfiguredBundlesAttributesTransfer,
                new CreateConfiguredBundleRequestTransfer()
            );

        $quoteResponseTransfer = $this->configurableBundleCartsRestApiClient->addConfiguredBundle($createConfiguredBundleRequestTransfer);

        if (!$quoteResponseTransfer->getIsSuccessful()) {
            return $this->configuredBundleRestResponseBuilder->createFailedErrorResponse($quoteResponseTransfer->getErrors());
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
        return new RestResponse();
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function deleteConfiguredBundle(RestRequestInterface $restRequest): RestResponseInterface
    {
        return new RestResponse();
    }
}
