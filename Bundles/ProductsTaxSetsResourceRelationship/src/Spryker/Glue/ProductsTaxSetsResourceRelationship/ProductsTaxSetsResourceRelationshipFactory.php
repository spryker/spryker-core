<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductsTaxSetsResourceRelationship;

use Spryker\Glue\Kernel\AbstractFactory;
use Spryker\Glue\ProductsTaxSetsResourceRelationship\Dependency\RestResource\ProductsTaxSetsResourceRelationshipToTaxSetsRestApiResourceInterface;
use Spryker\Glue\ProductsTaxSetsResourceRelationship\Processor\Expander\ProductsTaxSetsResourceRelationshipExpander;
use Spryker\Glue\ProductsTaxSetsResourceRelationship\Processor\Expander\ProductsTaxSetsResourceRelationshipExpanderInterface;

class ProductsTaxSetsResourceRelationshipFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\ProductsTaxSetsResourceRelationship\Dependency\RestResource\ProductsTaxSetsResourceRelationshipToTaxSetsRestApiResourceInterface
     */
    public function getProductTaxSetsResource(): ProductsTaxSetsResourceRelationshipToTaxSetsRestApiResourceInterface
    {
        return $this->getProvidedDependency(ProductsTaxSetsResourceRelationshipDependencyProvider::RESOURCE_TAX_SETS);
    }

    /**
     * @return \Spryker\Glue\ProductsTaxSetsResourceRelationship\Processor\Expander\ProductsTaxSetsResourceRelationshipExpanderInterface
     */
    public function createProductsTaxSetsResourceRelationshipExpander(): ProductsTaxSetsResourceRelationshipExpanderInterface
    {
        return new ProductsTaxSetsResourceRelationshipExpander($this->getProductTaxSetsResource());
    }
}
