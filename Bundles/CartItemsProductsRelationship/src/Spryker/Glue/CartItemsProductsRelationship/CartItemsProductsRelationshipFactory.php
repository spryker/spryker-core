<?php

/**
 * Copyright© 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartItemsProductsRelationship;

use Spryker\Glue\CartItemsProductsRelationship\Dependency\RestResource\CartItemsProductsRelationToProductsRestApiInterface;
use Spryker\Glue\CartItemsProductsRelationship\Processor\Expander\CartItemsProductsRelationshipExpander;
use Spryker\Glue\CartItemsProductsRelationship\Processor\Expander\CartItemsProductsRelationshipExpanderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

class CartItemsProductsRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CartItemsProductsRelationship\Dependency\RestResource\CartItemsProductsRelationToProductsRestApiInterface
     */
    public function getProductsResource(): CartItemsProductsRelationToProductsRestApiInterface
    {
        return $this->getProvidedDependency(CartItemsProductsRelationshipDependencyProvider::RESOURCE_PRODUCTS);
    }

    /**
     * @return \Spryker\Glue\CartItemsProductsRelationship\Processor\Expander\CartItemsProductsRelationshipExpanderInterface
     */
    public function createCartsProductsResourceExpander(): CartItemsProductsRelationshipExpanderInterface
    {
        return new CartItemsProductsRelationshipExpander($this->getProductsResource());
    }
}
