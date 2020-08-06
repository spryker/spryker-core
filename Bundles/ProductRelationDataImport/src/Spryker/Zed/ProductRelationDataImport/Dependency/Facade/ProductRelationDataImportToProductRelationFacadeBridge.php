<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationDataImport\Dependency\Facade;

class ProductRelationDataImportToProductRelationFacadeBridge implements ProductRelationDataImportToProductRelationFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductRelation\Business\ProductRelationFacadeInterface
     */
    protected $productRelationFacade;

    /**
     * @param \Spryker\Zed\ProductRelation\Business\ProductRelationFacadeInterface $productRelationFacade
     */
    public function __construct($productRelationFacade)
    {
        $this->productRelationFacade = $productRelationFacade;
    }

    /**
     * @return void
     */
    public function rebuildRelations()
    {
        $this->productRelationFacade->rebuildRelations();
    }
}
