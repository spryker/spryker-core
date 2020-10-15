<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesRestApi\Processor\Builder;

use Generated\Shared\Transfer\ProductManagementAttributeCollectionTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeFilterTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestProductManagementAttributeAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\ProductAttributesRestApi\Processor\Mapper\ProductAttributeMapperInterface;
use Spryker\Glue\ProductAttributesRestApi\ProductAttributesRestApiConfig;
use Symfony\Component\HttpFoundation\Response;

class RestProductAttributeResponseBuilder implements RestProductAttributeResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @var \Spryker\Glue\ProductAttributesRestApi\Processor\Mapper\ProductAttributeMapperInterface
     */
    protected $productAttributeMapper;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ProductAttributesRestApi\Processor\Mapper\ProductAttributeMapperInterface $productAttributeMapper
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ProductAttributeMapperInterface $productAttributeMapper
    ) {
        $this->restResourceBuilder = $restResourceBuilder;
        $this->productAttributeMapper = $productAttributeMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeFilterTransfer $productManagementAttributeFilterTransfer
     * @param \Generated\Shared\Transfer\ProductManagementAttributeCollectionTransfer $productManagementAttributeCollectionTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductAttributeListRestResponse(
        ProductManagementAttributeFilterTransfer $productManagementAttributeFilterTransfer,
        ProductManagementAttributeCollectionTransfer $productManagementAttributeCollectionTransfer
    ): RestResponseInterface {
        $restResponse = $this
            ->restResourceBuilder
            ->createRestResponse(
                $productManagementAttributeCollectionTransfer->getPagination()->getNbResults(),
                $productManagementAttributeFilterTransfer->getFilter()->getLimit() ?? 0
            );

        foreach ($productManagementAttributeCollectionTransfer->getProductManagementAttributes() as $productManagementAttributeTransfer) {
            $restProductManagementAttributeAttributesTransfer = $this->productAttributeMapper
                ->mapProductManagementAttributeToRestProductManagementAttributes(
                    $productManagementAttributeTransfer,
                    new RestProductManagementAttributeAttributesTransfer()
                );

            $restResponse->addResource(
                $this->restResourceBuilder->createRestResource(
                    ProductAttributesRestApiConfig::RESOURCE_PRODUCT_MANAGEMENT_ATTRIBUTES,
                    $restProductManagementAttributeAttributesTransfer->getKey(),
                    $restProductManagementAttributeAttributesTransfer
                )
            );
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductAttributeRestResponse(ProductManagementAttributeTransfer $productManagementAttributeTransfer): RestResponseInterface
    {
        $restProductManagementAttributeAttributesTransfer = $this->productAttributeMapper
            ->mapProductManagementAttributeToRestProductManagementAttributes(
                $productManagementAttributeTransfer,
                new RestProductManagementAttributeAttributesTransfer()
            );

        $restResponse = $this->restResourceBuilder->createRestResponse();

        $restResponse->addResource(
            $this->restResourceBuilder->createRestResource(
                ProductAttributesRestApiConfig::RESOURCE_PRODUCT_MANAGEMENT_ATTRIBUTES,
                $restProductManagementAttributeAttributesTransfer->getKey(),
                $restProductManagementAttributeAttributesTransfer
            )
        );

        return $restResponse;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createProductAttributeNotFoundErrorResponse(): RestResponseInterface
    {
        $restErrorMessageTransfer = (new RestErrorMessageTransfer())
            ->setStatus(Response::HTTP_NOT_FOUND)
            ->setCode(ProductAttributesRestApiConfig::RESPONSE_CODE_PRODUCT_ATTRIBUTE_NOT_FOUND)
            ->setDetail(ProductAttributesRestApiConfig::EXCEPTION_MESSAGE_PRODUCT_ATTRIBUTE_NOT_FOUND);

        return $this->restResourceBuilder
            ->createRestResponse()
            ->addError($restErrorMessageTransfer);
    }
}
