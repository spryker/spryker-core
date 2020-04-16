<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Persistence;

use Generated\Shared\Transfer\NavigationNodeTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\Navigation\Persistence\NavigationPersistenceFactory getFactory()
 */
class NavigationRepository extends AbstractRepository implements NavigationRepositoryInterface
{
    /**
     * @param int $idNavigation
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer[]
     */
    public function getNavigationNodesByNavigationId(int $idNavigation): array
    {
        $navigationNodeEntities = $this->getFactory()
            ->createNavigationNodeQuery()
                ->filterByFkNavigation($idNavigation)
            ->joinWithSpyNavigationNodeLocalizedAttributes()
            ->find();

        if (!$navigationNodeEntities) {
            return [];
        }

        $navigationNodeTransfers = [];
        foreach ($navigationNodeEntities as $navigationNodeEntity) {
            $navigationNodeTransfers[] = $this->getFactory()
                ->createProductMapper()
                ->mapNavigationNodeEntityToNavigationNodeTransfer($navigationNodeEntity, new NavigationNodeTransfer());
        }

        return $navigationNodeTransfers;
    }
}
