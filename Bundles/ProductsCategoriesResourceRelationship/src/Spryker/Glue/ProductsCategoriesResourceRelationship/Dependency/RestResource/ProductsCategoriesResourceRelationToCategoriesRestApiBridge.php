<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsCategoriesResourceRelationship\Dependency\RestResource;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class ProductsCategoriesResourceRelationToCategoriesRestApiBridge implements ProductsCategoriesResourceRelationToCategoriesRestApiInterface
{
    /**
     * @var \Spryker\Glue\CategoriesRestApi\CategoriesRestApiResourceInterface
     */
    protected $categoriesRestApiResource;

    /**
     * @param \Spryker\Glue\CategoriesRestApi\CategoriesRestApiResourceInterface $categoriesRestApiResource
     */
    public function __construct($categoriesRestApiResource)
    {
        $this->categoriesRestApiResource = $categoriesRestApiResource;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findProductCategoriesBySku(RestRequestInterface $restRequest): ?RestResourceInterface
    {
        return $this->categoriesRestApiResource
            ->findCategoriesByAbstractProductSku($restRequest);
    }
}
