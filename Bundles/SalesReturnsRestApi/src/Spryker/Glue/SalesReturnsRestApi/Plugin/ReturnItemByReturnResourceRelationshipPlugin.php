<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi\Plugin;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\SalesReturnsRestApi\SalesReturnsRestApiConfig;

/**
 * @Glue({
 *     "resourceAttributesClassName": "\\Generated\\Shared\\Transfer\\RestReturnsAttributesTransfer"
 * })
 *
 * @method \Spryker\Glue\SalesReturnsRestApi\SalesReturnsRestApiFactory getFactory()
 */
class ReturnItemByReturnResourceRelationshipPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds `return-items` resource as relationship by return.
     * - Requires ReturnTransfer be provided in resource payload.
     *
     * @api
     *
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        $this->getFactory()
            ->createReturnItemExpander()
            ->addResourceRelationships($resources, $restRequest);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getRelationshipResourceType(): string
    {
        return SalesReturnsRestApiConfig::RESOURCE_RETURN_ITEMS;
    }
}
