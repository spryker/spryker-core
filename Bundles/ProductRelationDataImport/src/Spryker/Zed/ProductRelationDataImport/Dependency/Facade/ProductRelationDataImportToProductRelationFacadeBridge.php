<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
