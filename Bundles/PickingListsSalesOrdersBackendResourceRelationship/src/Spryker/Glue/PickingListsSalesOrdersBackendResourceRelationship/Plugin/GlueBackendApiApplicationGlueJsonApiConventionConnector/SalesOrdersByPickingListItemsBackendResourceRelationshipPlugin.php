<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\PickingListsSalesOrdersBackendResourceRelationship\PickingListsSalesOrdersBackendResourceRelationshipFactory getFactory()
 */
class SalesOrdersByPickingListItemsBackendResourceRelationshipPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    /**
     * @uses \Spryker\Glue\SalesOrdersBackendApi\SalesOrdersBackendApiConfig::RESOURCE_SALES_ORDERS
     *
     * @var string
     */
    protected const RESOURCE_SALES_ORDERS = 'sales-orders';

    /**
     * {@inheritDoc}
     * - Adds `sales-orders` resources as a relationship to `picking-list-items` resources.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\GlueResourceTransfer> $resources
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return void
     */
    public function addRelationships(array $resources, GlueRequestTransfer $glueRequestTransfer): void
    {
        $this->getFactory()
            ->createPickingListsSalesOrdersBackendResourceRelationshipExpander()
            ->addPickingListItemsSalesOrdersRelationships($resources, $glueRequestTransfer);
    }

    /**
     * {@inheritDoc}
     * - Returns resource type for sales orders.
     *
     * @api
     *
     * @return string
     */
    public function getRelationshipResourceType(): string
    {
        return static::RESOURCE_SALES_ORDERS;
    }
}
