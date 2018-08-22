<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsCategoryResourceRelationship\Processor\Mapper;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ProductsCategoryResourceRelationship\Dependency\RestResource\ProductsCategoryResourceRelationToCategoriesRestApiInterface;

class AbstractProductsCategoryResourceRelationshipMapper implements AbstractProductsCategoryResourceRelationshipMapperInterface
{
    /**
     * @var \Spryker\Glue\ProductsCategoryResourceRelationship\Dependency\RestResource\ProductsCategoryResourceRelationToCategoriesRestApiInterface
     */
    protected $categoriesResource;

    /**
     * @param \Spryker\Glue\ProductsCategoryResourceRelationship\Dependency\RestResource\ProductsCategoryResourceRelationToCategoriesRestApiInterface $categoriesResource
     */
    public function __construct(ProductsCategoryResourceRelationToCategoriesRestApiInterface $categoriesResource)
    {
        $this->categoriesResource = $categoriesResource;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function mapResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            $abstractCategoriesResource = $this->categoriesResource
                ->findProductCategoriesBySku($resource->getId(), $restRequest);
            if ($abstractCategoriesResource !== null) {
                $resource->addRelationship($abstractCategoriesResource);
            }
        }
    }
}
