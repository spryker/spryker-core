<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductPricesRestApi\Processor\AbstractProductPrices\AbstractProductPricesReaderInterface;

class AbstractProductPricesRelationshipExpander implements AbstractProductPricesRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductPricesRestApi\Processor\AbstractProductPrices\AbstractProductPricesReaderInterface
     */
    protected $abstractProductPricesReader;

    /**
     * @param \Spryker\Glue\ProductPricesRestApi\Processor\AbstractProductPrices\AbstractProductPricesReaderInterface $abstractProductPricesReader
     */
    public function __construct(AbstractProductPricesReaderInterface $abstractProductPricesReader)
    {
        $this->abstractProductPricesReader = $abstractProductPricesReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationshipsByResourceId(array $resources, RestRequestInterface $restRequest): array
    {
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
