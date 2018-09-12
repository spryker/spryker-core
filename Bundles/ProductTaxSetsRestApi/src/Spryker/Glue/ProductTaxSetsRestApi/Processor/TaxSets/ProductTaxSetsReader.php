<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductTaxSetsRestApi\Processor\TaxSets;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestProductTaxSetsAttributesTransfer;
use Generated\Shared\Transfer\TaxSetResponseTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use Spryker\Glue\ProductTaxSetsRestApi\Dependency\Client\ProductTaxSetsRestApiApiToTaxProductConnectorClientInterface;
use Spryker\Glue\ProductTaxSetsRestApi\Processor\Mapper\ProductTaxSetsResourceMapperInterface;
use Spryker\Glue\ProductTaxSetsRestApi\ProductTaxSetsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ProductTaxSetsReader implements ProductTaxSetsReaderInterface
{
    protected const TAX_SET_RESPONSE_ERROR_ABSTRACT_PRODUCT_NOT_FOUND = 'Could not get tax set, product abstract with id "0" not found.';

    /**
     * @var \Spryker\Glue\ProductTaxSetsRestApi\Dependency\Client\ProductTaxSetsRestApiApiToTaxProductConnectorClientInterface
     */
    protected $taxProductConnectorClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ProductTaxSetsRestApi\Processor\Mapper\ProductTaxSetsResourceMapperInterface
     */
    protected $taxSetsResourceMapper;

    /**
     * @param \Spryker\Glue\ProductTaxSetsRestApi\Dependency\Client\ProductTaxSetsRestApiApiToTaxProductConnectorClientInterface $taxProductConnectorClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ProductTaxSetsRestApi\Processor\Mapper\ProductTaxSetsResourceMapperInterface $taxSetResourceMapper
     */
    public function __construct(
        ProductTaxSetsRestApiApiToTaxProductConnectorClientInterface $taxProductConnectorClient,
        RestResourceBuilderInterface $restResourceBuilder,
        ProductTaxSetsResourceMapperInterface $taxSetResourceMapper
    ) {
        $this->taxProductConnectorClient = $taxProductConnectorClient;
        $this->restResourceBuilder = $restResourceBuilder;
        $this->taxSetsResourceMapper = $taxSetResourceMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getTaxSets(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $parentResource = $restRequest->findParentResourceByType(ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS);
        if (!$parentResource) {
            return $this->createAbstractProductNotFoundError();
        }

        $taxSetResponseTransfer = $this->taxProductConnectorClient->getTaxSetForProductAbstract(
            (new ProductAbstractTransfer())->setSku($parentResource->getId())
        );

        if ($taxSetResponseTransfer->getError()) {
            if ($taxSetResponseTransfer->getError() === static::TAX_SET_RESPONSE_ERROR_ABSTRACT_PRODUCT_NOT_FOUND) {
                return $this->createAbstractProductNotFoundError();
            }

            return $this->createTaxSetsNotFoundError($taxSetResponseTransfer);
        }

        $restTaxSetTransfer = $this->taxSetsResourceMapper->mapTaxSetTransferToRestTaxSetsAttributesTransfer($taxSetResponseTransfer->getTaxSet());
        $restResource = $this->formatRestResource($restTaxSetTransfer, $taxSetResponseTransfer->getTaxSet()->getUuid(), $parentResource->getId());

        return $restResponse->addResource($restResource);
    }

    /**
     * @param string $abstractProductSku
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findAbstractProductTaxSetsByAbstractProductSku(string $abstractProductSku, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        $taxSetResponseTransfer = $this->taxProductConnectorClient->getTaxSetForProductAbstract(
            (new ProductAbstractTransfer())->setSku($abstractProductSku)
        );
        if ($taxSetResponseTransfer->getError()) {
            return null;
        }

        $restTaxSetTransfer = $this->taxSetsResourceMapper->mapTaxSetTransferToRestTaxSetsAttributesTransfer($taxSetResponseTransfer->getTaxSet());

        return $this->formatRestResource($restTaxSetTransfer, $taxSetResponseTransfer->getTaxSet()->getUuid(), $abstractProductSku);
    }

    /**
     * @param \Generated\Shared\Transfer\RestProductTaxSetsAttributesTransfer $restTaxSetsAttributesTransfer
     * @param string $uuid
     * @param string $parentResourceId
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function formatRestResource(RestProductTaxSetsAttributesTransfer $restTaxSetsAttributesTransfer, string $uuid, string $parentResourceId): RestResourceInterface
    {
        $restResource = $this->restResourceBuilder->createRestResource(
            ProductTaxSetsRestApiConfig::RESOURCE_TAX_SETS,
            $uuid,
            $restTaxSetsAttributesTransfer
        );

        $selfLink = sprintf(
            '%s/%s/%s',
            ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
            $parentResourceId,
            ProductTaxSetsRestApiConfig::RESOURCE_TAX_SETS
        );

        $restResource->addLink(RestResourceInterface::RESOURCE_LINKS_SELF, $selfLink);

        return $restResource;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createAbstractProductNotFoundError(): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $errorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductsRestApiConfig::RESPONSE_CODE_CANT_FIND_ABSTRACT_PRODUCT)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ProductsRestApiConfig::RESPONSE_DETAIL_CANT_FIND_ABSTRACT_PRODUCT);

        return $restResponse->addError($errorTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\TaxSetResponseTransfer $taxSetResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function createTaxSetsNotFoundError(TaxSetResponseTransfer $taxSetResponseTransfer): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $restErrorTransfer = (new RestErrorMessageTransfer())
            ->setCode(ProductTaxSetsRestApiConfig::RESPONSE_CODE_CANT_FIND_PRODUCT_TAX_SETS)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail($taxSetResponseTransfer->getError());

        return $restResponse->addError($restErrorTransfer);
    }
}
