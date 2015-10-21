<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Dependency\Facade;

interface CmsToCategoryInterface
{
    /**
     * @param int $idCategoryNode
     *
     * @return void
     */
    public function updateBlocksAssignedToDeletedCategoryNode($idCategoryNode);
}
