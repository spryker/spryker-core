<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductRelationDataImport\Business\Hook;

use Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportInterface;
use Spryker\Zed\ProductRelationDataImport\Dependency\Facade\ProductRelationDataImportToProductRelationFacadeInterface;

class ProductRelationAfterImportHook implements DataImporterAfterImportInterface
{
    /**
     * @var \Spryker\Zed\ProductRelationDataImport\Dependency\Facade\ProductRelationDataImportToProductRelationFacadeInterface
     */
    protected $productRelationFacade;

    /**
     * @param \Spryker\Zed\ProductRelationDataImport\Dependency\Facade\ProductRelationDataImportToProductRelationFacadeInterface $productRelationFacade
     */
    public function __construct(ProductRelationDataImportToProductRelationFacadeInterface $productRelationFacade)
    {
        $this->productRelationFacade = $productRelationFacade;
    }

    /**
     * @return void
     */
    public function afterImport(): void
    {
        $this->productRelationFacade->rebuildRelations();
    }
}
