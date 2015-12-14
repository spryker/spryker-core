<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCategory\Dependency\Facade;

use Generated\Shared\Transfer\CmsBlockTransfer;

interface ProductCategoryToCmsInterface
{

    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function updateBlocksAssignedToDeletedCategoryNode($idCategoryNode);

    /**
     * @param int $idCategoryNode
     *
     * @return CmsBlockTransfer[]
     */
    public function getCmsBlocksByIdCategoryNode($idCategoryNode);

}
