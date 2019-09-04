<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\PermissionAwareTrait;
use Spryker\Glue\ProductPricesRestApi\Processor\AbstractProductPrices\AbstractProductPricesReaderInterface;
use Spryker\Glue\ProductPricesRestApi\ProductPricesRestApiConfig;

class AbstractProductPricesRelationshipExpander implements AbstractProductPricesRelationshipExpanderInterface
{
    use PermissionAwareTrait;

    /**
     * @var \Spryker\Glue\ProductPricesRestApi\Processor\AbstractProductPrices\AbstractProductPricesReaderInterface
     */
    protected $abstractProductPricesReader;

    /**
     * @var \Spryker\Glue\ProductPricesRestApi\ProductPricesRestApiConfig
     */
    protected $productPricesRestApiConfig;

    /**
     * @param \Spryker\Glue\ProductPricesRestApi\Processor\AbstractProductPrices\AbstractProductPricesReaderInterface $abstractProductPricesReader
     * @param \Spryker\Glue\ProductPricesRestApi\ProductPricesRestApiConfig $productPricesRestApiConfig
     */
    public function __construct(
        AbstractProductPricesReaderInterface $abstractProductPricesReader,
        ProductPricesRestApiConfig $productPricesRestApiConfig
    ) {
        $this->abstractProductPricesReader = $abstractProductPricesReader;
        $this->productPricesRestApiConfig = $productPricesRestApiConfig;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationshipsByResourceId(array $resources, RestRequestInterface $restRequest): array
    {
        if ($this->productPricesRestApiConfig->getPermissionCheckEnabled() && !$this->can('SeePricePermissionPlugin')) {
            return $resources;
        }

        foreach ($resources as $resource) {
            $abstractProductPricesResource = $this->abstractProductPricesReader
                ->findAbstractProductPricesBySku($resource->getId(), $restRequest);
            if ($abstractProductPricesResource) {
                $resource->addRelationship($abstractProductPricesResource);
            }
        }

        return $resources;
    }
}
