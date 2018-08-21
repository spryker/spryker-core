<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductImageSetsRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductConcreteImageStorageTransfer;
use Generated\Shared\Transfer\RestProductImageSetsAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\ProductImageSetsRestApi\ProductImageSetsRestApiConfig;

class ConcreteProductImageSetsMapper implements ConcreteProductImageSetsMapperInterface
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
     * @param \Generated\Shared\Transfer\ProductConcreteImageStorageTransfer $productConcreteImageStorageTransfer
     * @param string $sku
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapConcreteProductImageSetsTransferToRestResource(ProductConcreteImageStorageTransfer $productConcreteImageStorageTransfer, string $sku): RestResourceInterface
    {
        $restProductAbstractImagesAttributesTransfer = new RestProductImageSetsAttributesTransfer();
        $restProductAbstractImagesAttributesTransfer->fromArray(
            ($productConcreteImageStorageTransfer->getImageSets()[0])->toArray(),
            true
        );

        return $this->restResourceBuilder->createRestResource(
            ProductImageSetsRestApiConfig::RESOURCE_CONCRETE_PRODUCT_IMAGE_SETS,
            $sku,
            $restProductAbstractImagesAttributesTransfer
        );
    }
}
