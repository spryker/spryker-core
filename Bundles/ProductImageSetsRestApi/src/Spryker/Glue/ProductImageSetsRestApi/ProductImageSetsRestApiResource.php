<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductImageSetsRestApi;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\Kernel\AbstractRestResource;

/**
 * @method \Spryker\Glue\ProductImageSetsRestApi\ProductImageSetsRestApiFactory getFactory()
 */
class ProductImageSetsRestApiResource extends AbstractRestResource implements ProductImageSetsRestApiResourceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findAbstractProductImageSetsBySku(string $sku, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        return $this->getFactory()
            ->createAbstractProductImageSetsReader()
            ->findAbstractProductImageSetsBySku($sku, $restRequest);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $sku
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findConcreteProductImageSetsBySku(string $sku, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        return $this->getFactory()
            ->createConcreteProductImageSetsReader()
            ->findConcreteProductImageSetsBySku($sku, $restRequest);
    }
}
