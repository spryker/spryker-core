<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AlternativeProductsRestApi\Plugin\GlueApplication;

use Generated\Shared\Transfer\ConcreteProductsRestAttributesTransfer;
use Spryker\Glue\AlternativeProductsRestApi\AlternativeProductsRestApiConfig;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceWithParentPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;

class ConcreteAlternativeProductsResourceRoutePlugin extends AbstractPlugin implements ResourceRoutePluginInterface, ResourceWithParentPluginInterface
{
    /**
     * {@inheritDoc}
     *  - Configures action to retrieve concrete alternative products collection.
     *
     * @api
     *
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface $resourceRouteCollection
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface
     */
    public function configure(ResourceRouteCollectionInterface $resourceRouteCollection): ResourceRouteCollectionInterface
    {
        return $resourceRouteCollection
            ->addGet(AlternativeProductsRestApiConfig::ACTION_CONCRETE_ALTERNATIVE_PRODUCTS_GET, false);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceType(): string
    {
        return AlternativeProductsRestApiConfig::RELATIONSHIP_NAME_CONCRETE_ALTERNATIVE_PRODUCTS;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getController(): string
    {
        return AlternativeProductsRestApiConfig::CONTROLLER_CONCRETE_ALTERNATIVE_PRODUCTS;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceAttributesClassName(): string
    {
        return ConcreteProductsRestAttributesTransfer::class;
    }

    /**
     * @return string
     */
    public function getParentResourceType(): string
    {
        return ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS;
    }
}
