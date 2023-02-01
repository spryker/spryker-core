<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesBackendApi\Processor\Builder;

use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeCollectionTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Generated\Shared\Transfer\RestProductAttributesBackendAttributesTransfer;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Mapper\ProductAttributeMapperInterface;
use Spryker\Glue\ProductAttributesBackendApi\ProductAttributesBackendApiConfig;
use Symfony\Component\HttpFoundation\Response;

class ProductAttributeRestResponseBuilder implements ProductAttributeRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\ProductAttributesBackendApi\Processor\Mapper\ProductAttributeMapperInterface
     */
    protected ProductAttributeMapperInterface $productAttributeMapper;

    /**
     * @param \Spryker\Glue\ProductAttributesBackendApi\Processor\Mapper\ProductAttributeMapperInterface $productAttributeMapper
     */
    public function __construct(ProductAttributeMapperInterface $productAttributeMapper)
    {
        $this->productAttributeMapper = $productAttributeMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeCollectionTransfer $productManagementAttributeCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createProductAttributesCollectionRestResponse(
        ProductManagementAttributeCollectionTransfer $productManagementAttributeCollectionTransfer
    ): GlueResponseTransfer {
        $glueResponseTransfer = new GlueResponseTransfer();

        foreach ($productManagementAttributeCollectionTransfer->getProductManagementAttributes() as $productManagementAttributeTransfer) {
            $restProductAttributesBackendAttributesTransfer = $this->productAttributeMapper->mapProductManagementAttributeTransferToRestProductAttributesBackendAttributesTransfer(
                $productManagementAttributeTransfer,
                new RestProductAttributesBackendAttributesTransfer(),
            );

            $glueResourceTransfer = (new GlueResourceTransfer())
                ->setId($restProductAttributesBackendAttributesTransfer->getKey())
                ->setType(ProductAttributesBackendApiConfig::RESOURCE_PRODUCT_ATTRIBUTES)
                ->setAttributes($restProductAttributesBackendAttributesTransfer);
            $glueResponseTransfer->addResource($glueResourceTransfer);
        }

        if ($productManagementAttributeCollectionTransfer->getPagination()) {
            $glueResponseTransfer->setPagination($productManagementAttributeCollectionTransfer->getPagination());
        }

        return $glueResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createProductAttributesRestResponse(
        ProductManagementAttributeTransfer $productManagementAttributeTransfer
    ): GlueResponseTransfer {
        return $this->createProductAttributesCollectionRestResponse(
            (new ProductManagementAttributeCollectionTransfer())->addProductManagementAttribute($productManagementAttributeTransfer),
        );
    }

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createProductAttributeKeyExistsErrorRestResponse(): GlueResponseTransfer
    {
        return (new GlueResponseTransfer())
            ->setHttpStatus(Response::HTTP_BAD_REQUEST)
            ->addError(
                (new GlueErrorTransfer())
                    ->setStatus(Response::HTTP_BAD_REQUEST)
                    ->setCode(ProductAttributesBackendApiConfig::RESPONSE_CODE_PRODUCT_ATTRIBUTE_KEY_EXISTS)
                    ->setMessage(ProductAttributesBackendApiConfig::EXCEPTION_MESSAGE_PRODUCT_ATTRIBUTE_KEY_EXISTS),
            );
    }

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createProductAttributeKeyIsNotProvidedErrorRestResponse(): GlueResponseTransfer
    {
        return (new GlueResponseTransfer())
            ->setHttpStatus(Response::HTTP_BAD_REQUEST)
            ->addError(
                (new GlueErrorTransfer())
                    ->setStatus(Response::HTTP_BAD_REQUEST)
                    ->setCode(ProductAttributesBackendApiConfig::RESPONSE_CODE_PRODUCT_ATTRIBUTE_NOT_PROVIDED)
                    ->setMessage(ProductAttributesBackendApiConfig::EXCEPTION_MESSAGE_PRODUCT_ATTRIBUTE_NOT_PROVIDED),
            );
    }

    /**
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function createProductAttributeNotFoundErrorRestResponse(): GlueResponseTransfer
    {
        return (new GlueResponseTransfer())
            ->setHttpStatus(Response::HTTP_NOT_FOUND)
            ->addError(
                (new GlueErrorTransfer())
                    ->setStatus(Response::HTTP_NOT_FOUND)
                    ->setCode(ProductAttributesBackendApiConfig::RESPONSE_CODE_PRODUCT_ATTRIBUTE_NOT_FOUND)
                    ->setMessage(ProductAttributesBackendApiConfig::EXCEPTION_MESSAGE_PRODUCT_ATTRIBUTE_NOT_FOUND),
            );
    }
}
