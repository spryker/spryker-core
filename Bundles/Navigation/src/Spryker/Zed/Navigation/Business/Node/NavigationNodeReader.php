<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business\Node;

use Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer;
use Generated\Shared\Transfer\NavigationNodeTransfer;
use Orm\Zed\Navigation\Persistence\Base\SpyNavigationNodeLocalizedAttributes;
use Orm\Zed\Navigation\Persistence\SpyNavigationNode;
use Spryker\Zed\Navigation\Persistence\NavigationQueryContainerInterface;

class NavigationNodeReader implements NavigationNodeReaderInterface
{
    /**
     * @var \Spryker\Zed\Navigation\Persistence\NavigationQueryContainerInterface
     */
    protected $navigationQueryContainer;

    /**
     * @param \Spryker\Zed\Navigation\Persistence\NavigationQueryContainerInterface $navigationQueryContainer
     */
    public function __construct(NavigationQueryContainerInterface $navigationQueryContainer)
    {
        $this->navigationQueryContainer = $navigationQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer|null
     */
    public function findNavigationNode(NavigationNodeTransfer $navigationNodeTransfer)
    {
        $this->assertNavigationNodeForRead($navigationNodeTransfer);

        $navigationNodeEntity = $this->findNavigationNodeEntity($navigationNodeTransfer);

        if (!$navigationNodeEntity) {
            return null;
        }

        return $this->mapNavigationNodeEntityToTransfer($navigationNodeEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return void
     */
    protected function assertNavigationNodeForRead(NavigationNodeTransfer $navigationNodeTransfer)
    {
        $navigationNodeTransfer->requireIdNavigationNode();
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNode|null
     */
    protected function findNavigationNodeEntity(NavigationNodeTransfer $navigationNodeTransfer)
    {
        return $this->navigationQueryContainer
            ->queryNavigationNodeById($navigationNodeTransfer->getIdNavigationNode())
            ->findOne();
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigationNode $navigationNodeEntity
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer
     */
    protected function mapNavigationNodeEntityToTransfer(SpyNavigationNode $navigationNodeEntity)
    {
        $navigationNodeTransfer = new NavigationNodeTransfer();
        $navigationNodeTransfer->fromArray($navigationNodeEntity->toArray(), true);

        foreach ($navigationNodeEntity->getSpyNavigationNodeLocalizedAttributess() as $navigationNodeLocalizedAttributesEntity) {
            $navigationNodeLocalizedAttributesTransfer = $this->mapNavigationNodeLocalizedAttributesEntityToTransfer($navigationNodeLocalizedAttributesEntity);

            $navigationNodeTransfer->addNavigationNodeLocalizedAttribute($navigationNodeLocalizedAttributesTransfer);
        }

        return $navigationNodeTransfer;
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigationNodeLocalizedAttributes $navigationNodeLocalizedAttributesEntity
     *
     * @return \Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer
     */
    protected function mapNavigationNodeLocalizedAttributesEntityToTransfer(SpyNavigationNodeLocalizedAttributes $navigationNodeLocalizedAttributesEntity)
    {
        $navigationNodeLocalizedAttributesTransfer = new NavigationNodeLocalizedAttributesTransfer();
        $navigationNodeLocalizedAttributesTransfer->fromArray($navigationNodeLocalizedAttributesEntity->toArray(), true);

        if ($navigationNodeLocalizedAttributesEntity->getFkUrl()) {
            $navigationNodeLocalizedAttributesTransfer->setUrl($navigationNodeLocalizedAttributesEntity->getSpyUrl()->getUrl());
        }

        return $navigationNodeLocalizedAttributesTransfer;
    }
}
