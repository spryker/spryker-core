<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductImageSetsRestApi;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface ProductImageSetsRestApiResourceInterface
{
    /**
     * Specification:
     * - Retrieves abstract product image sets by abstract product identifier.
     *
     * @api
     *
     * @param string $abstractProductId
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findAbstractProductImageSetsByAbstractProductId(string $abstractProductId, RestRequestInterface $restRequest): ?RestResourceInterface;

    /**
     * Specification:
     * - Retrieves concrete product image sets by concrete product identifier.
     *
     * @api
     *
     * @param string $concreteProductId
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findConcreteProductImageSetsByConcreteProductId(string $concreteProductId, RestRequestInterface $restRequest): ?RestResourceInterface;
}
