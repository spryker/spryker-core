<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsProductTaxSetsResourceRelationship;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductsProductTaxSetsResourceRelationship\Dependency\RestResource\ProductsProductTaxSetsResourceRelationshipToTaxSetsRestApiResourceInterface;
use Spryker\Glue\ProductsProductTaxSetsResourceRelationship\Processor\Expander\ProductsProductTaxSetsResourceRelationshipExpander;
use Spryker\Glue\ProductsProductTaxSetsResourceRelationship\Processor\Expander\ProductsProductTaxSetsResourceRelationshipExpanderInterface;

class ProductsProductTaxSetsResourceRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductsProductTaxSetsResourceRelationship\Dependency\RestResource\ProductsProductTaxSetsResourceRelationshipToTaxSetsRestApiResourceInterface
     */
    public function getProductTaxSetsResource(): ProductsProductTaxSetsResourceRelationshipToTaxSetsRestApiResourceInterface
    {
        return $this->getProvidedDependency(ProductsProductTaxSetsResourceRelationshipDependencyProvider::RESOURCE_TAX_SETS);
    }

    /**
     * @return \Spryker\Glue\ProductsProductTaxSetsResourceRelationship\Processor\Expander\ProductsProductTaxSetsResourceRelationshipExpanderInterface
     */
    public function createProductsTaxSetsResourceRelationshipExpander(): ProductsProductTaxSetsResourceRelationshipExpanderInterface
    {
        return new ProductsProductTaxSetsResourceRelationshipExpander($this->getProductTaxSetsResource());
    }
}
