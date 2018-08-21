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
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $abstractProductId
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findAbstractProductImageSetsByAbstractProductId(string $abstractProductId, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        return $this->getFactory()
            ->createAbstractProductImageSetsReader()
            ->findAbstractProductImageSetsByAbstractProductId($abstractProductId, $restRequest);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $concreteProductId
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findConcreteProductImageSetsByConcreteProductId(string $concreteProductId, RestRequestInterface $restRequest): ?RestResourceInterface
    {
        return $this->getFactory()
            ->createConcreteProductImageSetsReader()
            ->findConcreteProductImageSetsByConcreteProductId($concreteProductId, $restRequest);
    }
}
