<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsBackendApi\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\ProductsBackendApi\ProductsBackendApiFactory getFactory()
 */
class ConcreteProductsByPickingListItemsBackendResourceRelationshipPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    /**
     * @uses \Spryker\Glue\ProductsBackendApi\ProductsBackendApiConfig::RESOURCE_CONCRETE_PRODUCTS
     *
     * @var string
     */
    protected const RESOURCE_CONCRETE_PRODUCTS = 'concrete-products';

    /**
     * {@inheritDoc}
     * - Adds `concrete-products` resources as a relationship to `picking-list-items` resources.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\GlueResourceTransfer> $resources
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return void
     */
    public function addRelationships(
        array $resources,
        GlueRequestTransfer $glueRequestTransfer
    ): void {
        $this->getFactory()
            ->createPickingListItemsBackendResourceRelationshipExpander()
            ->addPickingListItemsConcreteProductsRelationships($resources, $glueRequestTransfer);
    }

    /**
     * {@inheritDoc}
     * - Returns resource type for concrete products.
     *
     * @api
     *
     * @return string
     */
    public function getRelationshipResourceType(): string
    {
        return static::RESOURCE_CONCRETE_PRODUCTS;
    }
}
