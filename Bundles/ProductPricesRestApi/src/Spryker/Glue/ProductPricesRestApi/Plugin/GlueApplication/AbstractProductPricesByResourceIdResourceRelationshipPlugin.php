<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductPricesRestApi\Plugin\GlueApplication;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ProductPricesRestApi\ProductPricesRestApiConfig;

/**
 * @method \Spryker\Glue\ProductPricesRestApi\ProductPricesRestApiFactory getFactory()
 */
class AbstractProductPricesByResourceIdResourceRelationshipPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds abstract-product-prices resource as relationship by the resource id.
     * - Identifier of passed resources should contain abstract product sku.
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
            ->createAbstractProductPricesRelationshipExpander()
            ->addResourceRelationshipsByResourceId($resources, $restRequest);
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
        return ProductPricesRestApiConfig::RESOURCE_ABSTRACT_PRODUCT_PRICES;
    }
}
