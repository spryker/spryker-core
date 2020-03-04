<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationDataImport\Communication;

use Spryker\Zed\DataImport\Communication\DataImportCommunicationFactory;
use Spryker\Zed\ProductRelationDataImport\Dependency\Facade\ProductRelationDataImportToProductRelationFacadeInterface;
use Spryker\Zed\ProductRelationDataImport\ProductRelationDataImportDependencyProvider;

class ProductRelationDataImportCommunicationFactory extends DataImportCommunicationFactory
{
    /**
     * @return \Spryker\Zed\ProductRelationDataImport\Dependency\Facade\ProductRelationDataImportToProductRelationFacadeInterface
     */
    public function getProductRelationFacade(): ProductRelationDataImportToProductRelationFacadeInterface
    {
        return $this->getProvidedDependency(ProductRelationDataImportDependencyProvider::FACADE_PRODUCT_RELATION);
    }
}
