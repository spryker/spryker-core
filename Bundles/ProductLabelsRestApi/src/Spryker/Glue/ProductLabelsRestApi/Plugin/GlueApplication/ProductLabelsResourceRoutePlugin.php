<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductLabelsRestApi\Plugin\GlueApplication;

use Generated\Shared\Transfer\RestProductLabelsAttributesTransfer;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ProductLabelsRestApi\ProductLabelsRestApiConfig;

class ProductLabelsResourceRoutePlugin extends AbstractPlugin implements ResourceRoutePluginInterface
{
    /**
     * {@inheritDoc}
     * - Configures available actions for product-labels resource.
     *
     * @api
     *
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface $resourceRouteCollection
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRouteCollectionInterface
     */
    public function configure(ResourceRouteCollectionInterface $resourceRouteCollection): ResourceRouteCollectionInterface
    {
        return $resourceRouteCollection->addGet(ProductLabelsRestApiConfig::ACTION_PRODUCT_LABELS_GET, false);
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
        return ProductLabelsRestApiConfig::CONTROLLER_PRODUCT_LABELS;
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
        return ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS;
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
        return RestProductLabelsAttributesTransfer::class;
    }
}
