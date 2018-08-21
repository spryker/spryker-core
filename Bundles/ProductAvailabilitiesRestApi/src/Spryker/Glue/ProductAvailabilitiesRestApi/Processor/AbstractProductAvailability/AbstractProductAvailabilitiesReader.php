<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAvailabilitiesRestApi\Processor\AbstractProductAvailability;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client\ProductAvailabilitiesRestApiToAvailabilityResourceAliasStorageClientInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\AbstractProductAvailabilitiesResourceMapperInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\ProductAvailabilitiesRestApiConfig;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class AbstractProductAvailabilitiesReader implements AbstractProductAvailabilitiesReaderInterface
{
    /**
     * @var \Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client\ProductAvailabilitiesRestApiToAvailabilityResourceAliasStorageClientInterface
     */
    protected $availabilityResourceAliasStorageClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\AbstractProductAvailabilitiesResourceMapperInterface
     */
    protected $productsAvailabilityResourceMapper;

    /**
     * @param \Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client\ProductAvailabilitiesRestApiToAvailabilityResourceAliasStorageClientInterface $availabilityStorageClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\AbstractProductAvailabilitiesResourceMapperInterface $productsAvailabilityResourceMapper
     */
    public function __construct(
        ProductAvailabilitiesRestApiToAvailabilityResourceAliasStorageClientInterface $availabilityStorageClient,
        RestResourceBuilderInterface $restResourceBuilder,
        AbstractProductAvailabilitiesResourceMapperInterface $productsAvailabilityResourceMapper
    ) {
        $this->availabilityResourceAliasStorageClient = $availabilityStorageClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->productsAvailabilityResourceMapper = $productsAvailabilityResourceMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getAbstractProductAvailability(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();
        $abstractProductResource = $restRequest->findParentResourceByType(ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS);
        if (!$abstractProductResource) {
            $restErrorTransfer = (new RestErrorMessageTransfer())
                ->setCode(ProductsRestApiConfig::RESPONSE_CODE_ABSTRACT_PRODUCT_SKU_IS_MISSING)
                ->setStatus(Response::HTTP_BAD_REQUEST)
                ->setDetail(ProductsRestApiConfig::RESPONSE_DETAIL_ABSTRACT_PRODUCT_SKU_IS_MISSING);

            return $restResponse->addError($restErrorTransfer);
        }
        $abstractSku = $abstractProductResource->getId();

        $restResource = $this->findAbstractProductAvailabilityByAbstractProductSku($abstractSku);
        if (!$restResource) {
            $restErrorTransfer = (new RestErrorMessageTransfer())
                ->setCode(ProductAvailabilitiesRestApiConfig::RESPONSE_CODE_ABSTRACT_PRODUCT_AVAILABILITY_NOT_FOUND)
                ->setStatus(Response::HTTP_NOT_FOUND)
                ->setDetail(ProductAvailabilitiesRestApiConfig::RESPONSE_DETAILS_ABSTRACT_PRODUCT_AVAILABILITY_NOT_FOUND);

            return $restResponse->addError($restErrorTransfer);
        }

        return $restResponse->addResource($restResource);
    }

    /**
     * @param string $abstractProductSku
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findAbstractProductAvailabilityByAbstractProductSku(string $abstractProductSku): ?RestResourceInterface
    {
        $availabilityAbstractEntityTransfer = $this->availabilityResourceAliasStorageClient
            ->getAvailabilityAbstract($abstractProductSku);
        if (!$availabilityAbstractEntityTransfer->getAbstractSku()) {
            return null;
        }

        $restResource = $this->productsAvailabilityResourceMapper
            ->mapAbstractProductsAvailabilityTransferToRestResource($availabilityAbstractEntityTransfer);
        $restResourceSelfLink = sprintf(
            '%s/%s/%s',
            ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
            $abstractProductSku,
            ProductAvailabilitiesRestApiConfig::RESOURCE_ABSTRACT_PRODUCT_AVAILABILITIES
        );
        $restResource->addLink('self', $restResourceSelfLink);

        return $restResource;
    }
}
