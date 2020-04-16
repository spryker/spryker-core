<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Persistence\Mapper;

use Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer;
use Generated\Shared\Transfer\NavigationNodeTransfer;
use Orm\Zed\Navigation\Persistence\SpyNavigationNode;
use Orm\Zed\Navigation\Persistence\SpyNavigationNodeLocalizedAttributes;

class NavigationMapper
{
    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigationNode $navigationNodeEntity
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer
     */
    public function mapNavigationNodeEntityToNavigationNodeTransfer(
        SpyNavigationNode $navigationNodeEntity,
        NavigationNodeTransfer $navigationNodeTransfer
    ): NavigationNodeTransfer {
        $navigationNodeTransfer->fromArray($navigationNodeEntity->toArray(), true);
        foreach ($navigationNodeEntity->getSpyNavigationNodeLocalizedAttributess() as $navigationNodeLocalizedAttributes) {
            $navigationNodeTransfer->addNavigationNodeLocalizedAttribute(
                $this->mapNavigationNodeLocalizedAttributesEntityToNavigationNodeLocalizedAttributesTransfer(
                    $navigationNodeLocalizedAttributes,
                    new NavigationNodeLocalizedAttributesTransfer()
                )
            );
        }

        return $navigationNodeTransfer;
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigationNodeLocalizedAttributes $navigationNodeLocalizedAttributesEntity
     * @param \Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer $navigationNodeLocalizedAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer
     */
    protected function mapNavigationNodeLocalizedAttributesEntityToNavigationNodeLocalizedAttributesTransfer(
        SpyNavigationNodeLocalizedAttributes $navigationNodeLocalizedAttributesEntity,
        NavigationNodeLocalizedAttributesTransfer $navigationNodeLocalizedAttributesTransfer
    ): NavigationNodeLocalizedAttributesTransfer {
        return $navigationNodeLocalizedAttributesTransfer
            ->fromArray($navigationNodeLocalizedAttributesEntity->toArray(), true);
    }
}
