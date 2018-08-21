<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\PriceProductStorageTransfer;
use Generated\Shared\Transfer\RestProductPricesAttributesTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\ProductPricesRestApi\ProductPricesRestApiConfig;

class AbstractProductPricesResourceMapper implements AbstractProductPricesResourceMapperInterface
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
     * @param \Generated\Shared\Transfer\PriceProductStorageTransfer $productPricesTransfer
     * @param string $idResource
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function mapAbstractProductPricesTransferToRestResource(PriceProductStorageTransfer $productPricesTransfer, string $idResource): RestResourceInterface
    {
        $productPricesRestAttributesTransfer = new RestProductPricesAttributesTransfer();
        $productPricesRestAttributesTransfer->fromArray($productPricesTransfer->toArray(), true);

        return $this->restResourceBuilder->createRestResource(
            ProductPricesRestApiConfig::RESOURCE_ABSTRACT_PRODUCT_PRICES,
            $idResource,
            $productPricesRestAttributesTransfer
        );
    }
}
