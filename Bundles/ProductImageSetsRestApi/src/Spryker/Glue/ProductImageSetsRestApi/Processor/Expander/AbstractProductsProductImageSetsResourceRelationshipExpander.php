<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductImageSetsRestApi\Processor\Expander;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductImageSetsRestApi\Processor\Reader\AbstractProductImageSetsReaderInterface;

class AbstractProductsProductImageSetsResourceRelationshipExpander implements AbstractProductsProductImageSetsResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\ProductImageSetsRestApi\Processor\Reader\AbstractProductImageSetsReaderInterface
     */
    protected $abstractProductImageSetsReader;

    /**
     * @param \Spryker\Glue\ProductImageSetsRestApi\Processor\Reader\AbstractProductImageSetsReaderInterface $abstractProductImageSetsReader
     */
    public function __construct(AbstractProductImageSetsReaderInterface $abstractProductImageSetsReader)
    {
        $this->abstractProductImageSetsReader = $abstractProductImageSetsReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            $abstractProductImageSetsResource = $this->abstractProductImageSetsReader
                ->findAbstractProductImageSetsBySku($resource->getId(), $restRequest);
            if ($abstractProductImageSetsResource !== null) {
                $resource->addRelationship($abstractProductImageSetsResource);
            }
        }
    }
}
