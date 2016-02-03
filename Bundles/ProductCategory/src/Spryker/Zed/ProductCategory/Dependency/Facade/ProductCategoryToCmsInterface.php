<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\ProductCategory\Dependency\Facade;

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
     * @return \Generated\Shared\Transfer\CmsBlockTransfer[]
     */
    public function getCmsBlocksByIdCategoryNode($idCategoryNode);

}
