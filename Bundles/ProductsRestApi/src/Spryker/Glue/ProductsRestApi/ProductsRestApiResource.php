<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsRestApi;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\AbstractRestResource;

/**
 * @method \Spryker\Glue\ProductsRestApi\ProductsRestApiFactory getFactory()
 */
class ProductsRestApiResource extends AbstractRestResource implements ProductsRestApiResourceInterface
{
    /**
     * @param string $productIdentifier
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findByConcreteProductSku(string $productIdentifier, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        return $this
            ->getFactory()
            ->createConcreteProductsReader()
            ->findOneByProductConcrete($productIdentifier, $restRequest);
    }
}
