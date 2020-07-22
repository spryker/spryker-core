<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\Plugin\GlueApplication;

use Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\ConfigurableBundlesProductsResourceRelationshipConfig;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @Glue({
 *     "resourceAttributesClassName": "\\Generated\\Shared\\Transfer\\ConcreteProductsRestAttributesTransfer"
 * })
 *
 * @method \Spryker\Glue\ConfigurableBundlesProductsResourceRelationship\ConfigurableBundlesProductsResourceRelationshipFactory getFactory()
 */
class ProductConcreteByConfigurableBundleTemplateSlotResourceRelationshipPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds `concrete-products` resource as relationship by configurable bundle template slot.
     * - Requires `ConfigurableBundleTemplateSlotStorageTransfer` be provided in resource payload.
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
            ->createProductConcreteExpander()
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
        return ConfigurableBundlesProductsResourceRelationshipConfig::RESOURCE_CONCRETE_PRODUCTS;
    }
}
