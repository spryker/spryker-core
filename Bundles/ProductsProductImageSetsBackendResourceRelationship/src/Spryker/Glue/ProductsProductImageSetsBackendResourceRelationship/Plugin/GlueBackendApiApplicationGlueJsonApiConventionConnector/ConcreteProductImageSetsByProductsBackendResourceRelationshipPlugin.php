<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\Plugin\GlueBackendApiApplicationGlueJsonApiConventionConnector;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\GlueJsonApiConventionExtension\Dependency\Plugin\ResourceRelationshipPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Spryker\Glue\ProductsProductImageSetsBackendResourceRelationship\ProductsProductImageSetsBackendResourceRelationshipFactory getFactory()
 */
class ConcreteProductImageSetsByProductsBackendResourceRelationshipPlugin extends AbstractPlugin implements ResourceRelationshipPluginInterface
{
    /**
     * @uses \Spryker\Glue\ProductImageSetsBackendApi\ProductImageSetsBackendApiConfig::RESOURCE_CONCRETE_PRODUCT_IMAGE_SETS
     *
     * @var string
     */
    protected const RESOURCE_CONCRETE_PRODUCT_IMAGE_SETS = 'concrete-product-image-sets';

    /**
     * {@inheritDoc}
     * - Adds `concrete-product-image-sets` resources as a relationship to `concrete-products` resources.
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
            ->createProductConcreteProductImageSetResourceRelationshipExpander()
            ->addProductConcreteProductImageSetsRelationships($resources, $glueRequestTransfer);
    }

    /**
     * Specification:
     * - Returns resource type for concrete product image sets.
     *
     * @api
     *
     * @return string
     */
    public function getRelationshipResourceType(): string
    {
        return static::RESOURCE_CONCRETE_PRODUCT_IMAGE_SETS;
    }
}
