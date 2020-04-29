<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Persistence\Mapper;

use Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer;
use Generated\Shared\Transfer\NavigationNodeTransfer;

class NavigationMapper
{
    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigationNode[] $navigationNodeEntities
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer[] $navigationNodeTransfers
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer[]
     */
    public function mapNavigationNodeEntitiesToNavigationNodeTransfers(
        array $navigationNodeEntities,
        array $navigationNodeTransfers
    ): array {
        foreach ($navigationNodeEntities as $navigationNodeEntity) {
            $navigationNodeTransfer = (new NavigationNodeTransfer())->fromArray($navigationNodeEntity->toArray(), true);
            foreach ($navigationNodeEntity->getSpyNavigationNodeLocalizedAttributess() as $navigationNodeLocalizedAttributes) {
                $navigationNodeTransfer->addNavigationNodeLocalizedAttribute(
                    (new NavigationNodeLocalizedAttributesTransfer())
                        ->fromArray($navigationNodeLocalizedAttributes->toArray(), true)
                );
            }

            $navigationNodeTransfers[] = $navigationNodeTransfer;
        }

        return $navigationNodeTransfers;
    }
}
