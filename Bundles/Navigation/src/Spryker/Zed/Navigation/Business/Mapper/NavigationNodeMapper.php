<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer;
use Generated\Shared\Transfer\NavigationNodeTransfer;

class NavigationNodeMapper implements NavigationNodeMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer[] $navigationNodeTransfers
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer[]
     */
    public function mapToNavigationNodeTransfers(array $navigationNodeTransfers): array
    {
        $newNavigationNodeTransfers = [];
        foreach ($navigationNodeTransfers as $navigationNodeTransfer) {
            $newNavigationNodeTransfers[] = (new NavigationNodeTransfer())
                ->setIsActive($navigationNodeTransfer->getIsActive())
                ->setValidFrom($navigationNodeTransfer->getValidFrom())
                ->setValidTo($navigationNodeTransfer->getValidTo())
                ->setNodeType($navigationNodeTransfer->getNodeType())
                ->setPosition($navigationNodeTransfer->getPosition())
                ->setNavigationNodeLocalizedAttributes(
                    $this->mapToNavigationNodeLocalizedAttributesTransfers($navigationNodeTransfer->getNavigationNodeLocalizedAttributes())
                )
                ->setFkParentNavigationNode($navigationNodeTransfer->getFkParentNavigationNode());
        }

        return $newNavigationNodeTransfers;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer[] $navigationNodeLocalizedAttributesTransfers
     *
     * @return \ArrayObject
     */
    protected function mapToNavigationNodeLocalizedAttributesTransfers(ArrayObject $navigationNodeLocalizedAttributesTransfers): ArrayObject
    {
        $newNavigationNodeLocalizedAttributesTransfers = [];
        foreach ($navigationNodeLocalizedAttributesTransfers as $navigationNodeLocalizedAttributesTransfer) {
            $newNavigationNodeLocalizedAttributesTransfers[] = (new NavigationNodeLocalizedAttributesTransfer())
                ->setCategoryUrl($navigationNodeLocalizedAttributesTransfer->getCategoryUrl())
                ->setCmsPageUrl($navigationNodeLocalizedAttributesTransfer->getCmsPageUrl())
                ->setCssClass($navigationNodeLocalizedAttributesTransfer->getCssClass())
                ->setExternalUrl($navigationNodeLocalizedAttributesTransfer->getExternalUrl())
                ->setFkLocale($navigationNodeLocalizedAttributesTransfer->getFkLocale())
                ->setFkNavigationNode($navigationNodeLocalizedAttributesTransfer->getFkNavigationNode())
                ->setFkUrl($navigationNodeLocalizedAttributesTransfer->getFkUrl())
                ->setLink($navigationNodeLocalizedAttributesTransfer->getLink())
                ->setTitle($navigationNodeLocalizedAttributesTransfer->getTitle())
                ->setUrl($navigationNodeLocalizedAttributesTransfer->getUrl());
        }

        return new ArrayObject($newNavigationNodeLocalizedAttributesTransfers);
    }
}
