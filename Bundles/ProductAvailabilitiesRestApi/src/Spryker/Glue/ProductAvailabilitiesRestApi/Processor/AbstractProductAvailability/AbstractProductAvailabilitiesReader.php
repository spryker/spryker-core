<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAvailabilitiesRestApi\Processor\AbstractProductAvailability;

use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client\ProductAvailabilitiesRestApiToAvailabilityStorageClientInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client\ProductAvailabilitiesRestApiToProductStorageClientInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\AbstractProductAvailabilitiesResourceMapperInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\ProductAvailabilitiesRestApiConfig;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class AbstractProductAvailabilitiesReader implements AbstractProductAvailabilitiesReaderInterface
{
    protected const PRODUCT_ABSTRACT_MAPPING_TYPE = 'sku';
    protected const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @var \Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client\ProductAvailabilitiesRestApiToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client\ProductAvailabilitiesRestApiToAvailabilityStorageClientInterface
     */
    protected $availabilityStorageClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\AbstractProductAvailabilitiesResourceMapperInterface
     */
    protected $productsAvailabilityResourceMapper;

    /**
     * @param \Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client\ProductAvailabilitiesRestApiToProductStorageClientInterface $productStorageClient
     * @param \Spryker\Glue\ProductAvailabilitiesRestApi\Dependency\Client\ProductAvailabilitiesRestApiToAvailabilityStorageClientInterface $availabilityStorageClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\AbstractProductAvailabilitiesResourceMapperInterface $productsAvailabilityResourceMapper
     */
    public function __construct(
        ProductAvailabilitiesRestApiToProductStorageClientInterface $productStorageClient,
        ProductAvailabilitiesRestApiToAvailabilityStorageClientInterface $availabilityStorageClient,
        RestResourceBuilderInterface $restResourceBuilder,
        AbstractProductAvailabilitiesResourceMapperInterface $productsAvailabilityResourceMapper
    ) {
        $this->availabilityStorageClient = $availabilityStorageClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->productsAvailabilityResourceMapper = $productsAvailabilityResourceMapper;
        $this->productStorageClient = $productStorageClient;
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
            $restErrorTransfer = $this->createAbstractProductSkuIsNotSpecifiedError();

            return $restResponse->addError($restErrorTransfer);
        }
        $abstractSku = $abstractProductResource->getId();

        $restResource = $this->findAbstractProductAvailabilityBySku($abstractSku, $restRequest);
        if (!$restResource) {
            $restErrorTransfer = $this->createAbstractProductAvailabilityNotFoundError();

            return $restResponse->addError($restErrorTransfer);
        }

        return $restResponse->addResource($restResource);
    }

    /**
     * @param string $sku
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findAbstractProductAvailabilityBySku(string $sku, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        $abstractProductData = $this->productStorageClient
            ->findProductAbstractStorageDataByMapping(
                static::PRODUCT_ABSTRACT_MAPPING_TYPE,
                $sku,
                $restRequest->getMetadata()->getLocale()
            );
        if (!$abstractProductData) {
            return null;
        }

        $availabilityAbstractEntityTransfer = $this->availabilityStorageClient
            ->getAvailabilityAbstract($abstractProductData[static::KEY_ID_PRODUCT_ABSTRACT]);
        if (!$availabilityAbstractEntityTransfer->getAbstractSku()) {
            return null;
        }

        return $this->buildProductAvailabilitiesResource($sku, $availabilityAbstractEntityTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createAbstractProductSkuIsNotSpecifiedError(): RestErrorMessageTransfer
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductsRestApiConfig::RESPONSE_CODE_ABSTRACT_PRODUCT_SKU_IS_NOT_SPECIFIED)
            ->setStatus(Response::HTTP_BAD_REQUEST)
            ->setDetail(ProductsRestApiConfig::RESPONSE_DETAIL_ABSTRACT_PRODUCT_SKU_IS_NOT_SPECIFIED);

        return $restErrorTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createAbstractProductAvailabilityNotFoundError(): RestErrorMessageTransfer
    {
        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductAvailabilitiesRestApiConfig::RESPONSE_CODE_ABSTRACT_PRODUCT_AVAILABILITY_NOT_FOUND)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ProductAvailabilitiesRestApiConfig::RESPONSE_DETAILS_ABSTRACT_PRODUCT_AVAILABILITY_NOT_FOUND);

        return $restErrorTransfer;
    }

    /**
     * @param string $sku
     * @param \Generated\Shared\Transfer\SpyAvailabilityAbstractEntityTransfer $availabilityAbstractEntityTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function buildProductAvailabilitiesResource(string $sku, SpyAvailabilityAbstractEntityTransfer $availabilityAbstractEntityTransfer): RestResourceInterface
    {
        $restProductsAbstractAvailabilityAttributesTransfer = $this->productsAvailabilityResourceMapper
            ->mapAvailabilityTransferToRestAbstractProductAvailabilityAttributesTransfer($availabilityAbstractEntityTransfer);

        $restResource = $this->restResourceBuilder->createRestResource(
            ProductAvailabilitiesRestApiConfig::RESOURCE_ABSTRACT_PRODUCT_AVAILABILITIES,
            $sku,
            $restProductsAbstractAvailabilityAttributesTransfer
        );

        $restResourceSelfLink = sprintf(
            '%s/%s/%s',
            ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
            $sku,
            ProductAvailabilitiesRestApiConfig::RESOURCE_ABSTRACT_PRODUCT_AVAILABILITIES
        );
        $restResource->addLink(RestLinkInterface::LINK_SELF, $restResourceSelfLink);

        return $restResource;
    }
}
