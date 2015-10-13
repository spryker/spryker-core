<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\ProductCategory\Dependency\Facade;

interface CmsToCategoryInterface
{
    /**
     * @param int $idCategoryNode
     */
    public function updateBlocksAssignedToDeletedCategoryNode($idCategoryNode);
}
