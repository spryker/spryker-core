<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsCategoryResourceRelationship\Dependency\RestResource;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;

class ProductsCategoryResourceRelationToCategoriesRestApiBridge implements ProductsCategoryResourceRelationToCategoriesRestApiInterface
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
     * @param string $abstractProductSku
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findCategoriesByAbstractProductSku($abstractProductSku, $restRequest): ?RestResourceInterface
    {
        return $this->categoriesRestApiResource
            ->findCategoriesByAbstractProductSku($abstractProductSku, $restRequest);
    }
}
