<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAvailabilitiesRestApi\Plugin\GlueApplication;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ProductAvailabilitiesRestApi\ProductAvailabilitiesRestApiConfig;

/**
 * @method \Spryker\Glue\ProductAvailabilitiesRestApi\ProductAvailabilitiesRestApiFactory getFactory()
 */
class ConcreteProductAvailabilitiesByResourceIdResourceRelationshipPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    /**
     * {@inheritdoc}
     * - Adds concrete-product-availabilities resource as a relationship by the resource id.
     * - Identifier of passed resources should contain concrete product sku.
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
            ->createConcreteProductAvailabilitiesRelationshipExpander()
            ->addResourceRelationshipsByResourceId($resources, $restRequest);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getRelationshipResourceType(): string
    {
        return ProductAvailabilitiesRestApiConfig::RESOURCE_CONCRETE_PRODUCT_AVAILABILITIES;
    }
}
