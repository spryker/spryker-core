<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductAbstractImageStorageTransfer;
use Generated\Shared\Transfer\RestProductImageSetsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\ProductImageSetsRestApi\ProductImageSetsRestApiConfig;

class AbstractProductImageSetsMapper implements AbstractProductImageSetsMapperInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface
     */
    protected $restResourceBuilder;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     */
    public function __construct(RestResourceBuilderInterface $restResourceBuilder)
    {
        $this->restResourceBuilder = $restResourceBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractImageStorageTransfer $productAbstractImageStorageTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapAbstractProductImageSetsTransferToRestResource(ProductAbstractImageStorageTransfer $productAbstractImageStorageTransfer): RestResourceInterface
    {
        $restProductAbstractImagesAttributesTransfer = new RestProductImageSetsAttributesTransfer();
        $restProductAbstractImagesAttributesTransfer->fromArray(
            ($productAbstractImageStorageTransfer->getImageSets()[0])->toArray(),
            true
        );

        return $this->restResourceBuilder->createRestResource(
            ProductImageSetsRestApiConfig::RESOURCE_ABSTRACT_PRODUCT_IMAGE_SETS,
            (string)$productAbstractImageStorageTransfer->getIdProductAbstract(),
            $restProductAbstractImagesAttributesTransfer
        );
    }
}
