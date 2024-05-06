<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Persistence\Mapper;

use Generated\Shared\Transfer\NavigationTransfer;
use Orm\Zed\Navigation\Persistence\SpyNavigation;
use Propel\Runtime\Collection\Collection;

class NavigationMapper
{
    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\Navigation\Persistence\SpyNavigation> $navigationEntities
     * @param array<\Generated\Shared\Transfer\NavigationTransfer> $navigationTransfers
     *
     * @return array<\Generated\Shared\Transfer\NavigationTransfer>
     */
    public function mapNavigationEntitiesToNavigationTransfers(
        Collection $navigationEntities,
        array $navigationTransfers
    ): array {
        foreach ($navigationEntities as $navigationEntity) {
            $navigationTransfers[] = $this->mapNavigationEntityToNavigationTransfer($navigationEntity, new NavigationTransfer());
        }

        return $navigationTransfers;
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigation $navigationEntity
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationTransfer
     */
    public function mapNavigationEntityToNavigationTransfer(
        SpyNavigation $navigationEntity,
        NavigationTransfer $navigationTransfer
    ): NavigationTransfer {
        return $navigationTransfer->fromArray($navigationEntity->toArray(), true);
    }
}
