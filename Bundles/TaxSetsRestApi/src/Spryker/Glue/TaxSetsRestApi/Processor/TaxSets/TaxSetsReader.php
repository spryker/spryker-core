<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\TaxSetsRestApi\Processor\TaxSets;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\TaxRateSetTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use Spryker\Glue\TaxSetsRestApi\Dependency\Client\TaxSetsRestApiToTaxProductConnectorClientInterface;
use Spryker\Glue\TaxSetsRestApi\Processor\Mapper\TaxSetsResourceMapperInterface;
use Spryker\Glue\TaxSetsRestApi\TaxSetsRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class TaxSetsReader implements TaxSetsReaderInterface
{
    protected const TAX_SET_RESPONSE_ERROR_ABSTRACT_PRODUCT_NOT_FOUND = 'Could not get tax set, product abstract with id "0" not found.';
    /**
     * @var \Spryker\Glue\TaxSetsRestApi\Dependency\Client\TaxSetsRestApiToTaxProductConnectorClientInterface
     */
    protected $taxProductConnectorClient;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\TaxSetsRestApi\Processor\Mapper\TaxSetsResourceMapperInterface
     */
    protected $taxSetsResourceMapper;

    /**
     * @param \Spryker\Glue\TaxSetsRestApi\Dependency\Client\TaxSetsRestApiToTaxProductConnectorClientInterface $taxProductConnectorClient
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\TaxSetsRestApi\Processor\Mapper\TaxSetsResourceMapperInterface $taxSetResourceMapper
     */
    public function __construct(
        TaxSetsRestApiToTaxProductConnectorClientInterface $taxProductConnectorClient,
        RestResourceBuilderInterface $restResourceBuilder,
        TaxSetsResourceMapperInterface $taxSetResourceMapper
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
    public function readTaxSets(RestRequestInterface $restRequest): RestResponseInterface
    {
        $restResponse = $this->restResourceBuilder->createRestResponse();

        $parentResource = $restRequest->findParentResourceByType(ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS);
        if (!$parentResource) {
            return $restResponse->addError($this->createAbstractProductNotFoundError());
        }

        $taxSetResponseTransfer = $this->taxProductConnectorClient->getTaxSetForProductAbstract(
            (new ProductAbstractTransfer())->setSku($parentResource->getId())
        );

        if ($taxSetResponseTransfer->getError()) {
            if ($taxSetResponseTransfer->getError() === static::TAX_SET_RESPONSE_ERROR_ABSTRACT_PRODUCT_NOT_FOUND) {
                $restErrorTransfer = $this->createAbstractProductNotFoundError();

                return $restResponse->addError($restErrorTransfer);
            }
            $restErrorTransfer = (new RestErrorMessageTransfer())
                ->setCode(TaxSetsRestApiConfig::RESPONSE_CODE_CANT_FIND_PRODUCT_TAX_SETS)
                ->setStatus(Response::HTTP_NOT_FOUND)
                ->setDetail($taxSetResponseTransfer->getError());

            return $restResponse->addError($restErrorTransfer);
        }
        $restResource = $this->mapTransferToRestResource($taxSetResponseTransfer->getTaxRateSet(), $parentResource->getId());

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
        return $this->findOne($abstractProductSku);
    }

    /**
     * @param string $idResource
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    protected function findOne(string $idResource): ?RestResourceInterface
    {
        $taxSetResponseTransfer = $this->taxProductConnectorClient->getTaxSetForProductAbstract(
            (new ProductAbstractTransfer())->setSku($idResource)
        );
        if ($taxSetResponseTransfer->getError()) {
            return null;
        }

        return $this->mapTransferToRestResource($taxSetResponseTransfer->getTaxRateSet(), $idResource);
    }

    /**
     * @param \Generated\Shared\Transfer\TaxRateSetTransfer $taxRateSetTransfer
     * @param string $parentResourceId
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function mapTransferToRestResource(TaxRateSetTransfer $taxRateSetTransfer, string $parentResourceId): RestResourceInterface
    {
        $restResource = $this->taxSetsResourceMapper->mapTaxSetsResponseAttributesTransferToRestResponse($taxRateSetTransfer);
        $restResource->addLink('self', $this->getSelfLink($parentResourceId));

        return $restResource;
    }

    /**
     * @param string $parentResourceId
     *
     * @return string
     */
    protected function getSelfLink(string $parentResourceId): string
    {
        return sprintf(
            '%s/%s/%s',
            ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
            $parentResourceId,
            TaxSetsRestApiConfig::RESOURCE_TAX_SETS
        );
    }

    /**
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    protected function createAbstractProductNotFoundError(): RestErrorMessageTransfer
    {
        return (new RestErrorMessageTransfer())
            ->setCode(ProductsRestApiConfig::RESPONSE_CODE_CANT_FIND_ABSTRACT_PRODUCT)
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setDetail(ProductsRestApiConfig::RESPONSE_DETAIL_CANT_FIND_ABSTRACT_PRODUCT);
    }
}
