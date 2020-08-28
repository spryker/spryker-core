<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductRelationDataImport\Dependency\Facade;

interface ProductRelationDataImportToProductRelationFacadeInterface
{
    /**
     * @return void
     */
    public function rebuildRelations();
}
