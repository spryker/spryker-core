<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesRestApi\Processor\Reader;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeFilterTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductAttributesRestApi\Dependency\Client\ProductAttributesRestApiToProductAttributeClientInterface;
use Spryker\Glue\ProductAttributesRestApi\Processor\Builder\RestProductAttributeResponseBuilderInterface;

class ProductAttributeReader implements ProductAttributeReaderInterface
{
    /**
     * @var \Spryker\Glue\ProductAttributesRestApi\Dependency\Client\ProductAttributesRestApiToProductAttributeClientInterface
     */
    protected $productAttributeClient;

    /**
     * @var \Spryker\Glue\ProductAttributesRestApi\Processor\Builder\RestProductAttributeResponseBuilderInterface
     */
    protected $restProductAttributeResponseBuilder;

    /**
     * @param \Spryker\Glue\ProductAttributesRestApi\Dependency\Client\ProductAttributesRestApiToProductAttributeClientInterface $productAttributeClient
     * @param \Spryker\Glue\ProductAttributesRestApi\Processor\Builder\RestProductAttributeResponseBuilderInterface $restProductAttributeResponseBuilder
     */
    public function __construct(
        ProductAttributesRestApiToProductAttributeClientInterface $productAttributeClient,
        RestProductAttributeResponseBuilderInterface $restProductAttributeResponseBuilder
    ) {
        $this->restProductAttributeResponseBuilder = $restProductAttributeResponseBuilder;
        $this->productAttributeClient = $productAttributeClient;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getProductAttributes(RestRequestInterface $restRequest): RestResponseInterface
    {
        if ($restRequest->getResource()->getId()) {
            return $this->getProductAttributesById($restRequest);
        }

        return $this->getProductAttributesList($restRequest);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getProductAttributesList(RestRequestInterface $restRequest): RestResponseInterface
    {
        $productManagementAttributeFilterTransfer = $this->createProductManagementAttributeFilter($restRequest);
        $productManagementAttributeCollectionTransfer = $this->productAttributeClient
            ->getProductManagementAttributes($productManagementAttributeFilterTransfer);

        return $this->restProductAttributeResponseBuilder->createProductAttributeListRestResponse(
            $productManagementAttributeFilterTransfer,
            $productManagementAttributeCollectionTransfer
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    protected function getProductAttributesById(RestRequestInterface $restRequest): RestResponseInterface
    {
        $productManagementAttributeFilterTransfer = $this->createProductManagementAttributeFilter($restRequest)
            ->addKey($restRequest->getResource()->getId());

        $productManagementAttributeTransfer = $this->productAttributeClient
            ->getProductManagementAttributes($productManagementAttributeFilterTransfer)
            ->getProductManagementAttributes()
            ->getIterator()
            ->current();

        if (!$productManagementAttributeTransfer) {
            return $this->restProductAttributeResponseBuilder->createProductAttributeNotFoundErrorResponse();
        }

        return $this->restProductAttributeResponseBuilder->createProductAttributeRestResponse($productManagementAttributeTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeFilterTransfer
     */
    protected function createProductManagementAttributeFilter(RestRequestInterface $restRequest): ProductManagementAttributeFilterTransfer
    {
        $filterTransfer = new FilterTransfer();

        if ($restRequest->getPage()) {
            $filterTransfer
                ->setOffset($restRequest->getPage()->getOffset())
                ->setLimit($restRequest->getPage()->getLimit() ?? 0);
        }

        return (new ProductManagementAttributeFilterTransfer())
            ->fromArray($restRequest->getHttpRequest()->query->all(), true)
            ->setFilter($filterTransfer);
    }
}
