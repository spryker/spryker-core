<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business\Node;

use Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer;
use Generated\Shared\Transfer\NavigationNodeTransfer;
use Orm\Zed\Navigation\Persistence\SpyNavigationNode;
use Orm\Zed\Navigation\Persistence\SpyNavigationNodeLocalizedAttributes;
use Spryker\Zed\Navigation\Business\Exception\NavigationNodeLocalizedAttributesNotFoundException;
use Spryker\Zed\Navigation\Business\Exception\NavigationNodeNotFoundException;
use Spryker\Zed\Navigation\Persistence\NavigationQueryContainerInterface;

class NavigationNodeUpdater implements NavigationNodeUpdaterInterface
{

    /**
     * @var \Spryker\Zed\Navigation\Persistence\NavigationQueryContainerInterface
     */
    protected $navigationQueryContainer;

    /**
     * @var \Spryker\Zed\Navigation\Business\Node\NavigationNodeTouchInterface
     */
    protected $navigationNodeTouch;

    /**
     * @param \Spryker\Zed\Navigation\Persistence\NavigationQueryContainerInterface $navigationQueryContainer
     * @param \Spryker\Zed\Navigation\Business\Node\NavigationNodeTouchInterface $navigationNodeTouch
     */
    public function __construct(NavigationQueryContainerInterface $navigationQueryContainer, NavigationNodeTouchInterface $navigationNodeTouch)
    {
        $this->navigationQueryContainer = $navigationQueryContainer;
        $this->navigationNodeTouch = $navigationNodeTouch;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer
     */
    public function updateNavigationNode(NavigationNodeTransfer $navigationNodeTransfer)
    {
        $this->assertNavigationNodeForUpdate($navigationNodeTransfer);

        $this->navigationQueryContainer->getConnection()->beginTransaction();

        $navigationNodeTransfer = $this->persistNavigationNode($navigationNodeTransfer);
        $navigationNodeTransfer = $this->persistNavigationNodeLocalizedAttributes($navigationNodeTransfer);
        $this->navigationNodeTouch->touchNavigationNode($navigationNodeTransfer);

        $this->navigationQueryContainer->getConnection()->commit();

        return $navigationNodeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return void
     */
    protected function assertNavigationNodeForUpdate(NavigationNodeTransfer $navigationNodeTransfer)
    {
        $navigationNodeTransfer->requireIdNavigationNode();
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @throws \Spryker\Zed\Navigation\Business\Exception\NavigationNodeNotFoundException
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNode
     */
    protected function getNavigationNodeEntity(NavigationNodeTransfer $navigationNodeTransfer)
    {
        $navigationNodeEntity = $this->navigationQueryContainer
            ->queryNavigationNodeById($navigationNodeTransfer->getIdNavigationNode())
            ->findOne();

        if (!$navigationNodeEntity) {
            throw new NavigationNodeNotFoundException(sprintf(
                'Navigation node entity not found with ID %d.',
                $navigationNodeTransfer->getIdNavigationNode()
            ));
        }

        return $navigationNodeEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer
     */
    protected function persistNavigationNode(NavigationNodeTransfer $navigationNodeTransfer)
    {
        $navigationNodeEntity = $this->getNavigationNodeEntity($navigationNodeTransfer);

        $navigationNodeEntity->fromArray($navigationNodeTransfer->modifiedToArray());
        $navigationNodeEntity->save();

        return $this->hydrateNavigationNodeTransfer($navigationNodeTransfer, $navigationNodeEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer
     */
    protected function persistNavigationNodeLocalizedAttributes(NavigationNodeTransfer $navigationNodeTransfer)
    {
        foreach ($navigationNodeTransfer->getNavigationNodeLocalizedAttributes() as $navigationNodeLocalizedAttributesTransfer) {
            $navigationNodeLocalizedAttributesEntity = $this->findOrCreateNavigationNodeLocalizedAttributesEntity($navigationNodeLocalizedAttributesTransfer);

            $navigationNodeLocalizedAttributesEntity->fromArray($navigationNodeLocalizedAttributesTransfer->modifiedToArray());
            $navigationNodeLocalizedAttributesEntity->save();

            $this->hydrateNavigationNodeLocalizedAttributesTransfer($navigationNodeLocalizedAttributesTransfer, $navigationNodeLocalizedAttributesEntity);
        }

        return $navigationNodeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer $navigationNodeLocalizedAttributesTransfer
     *
     * @throws \Spryker\Zed\Navigation\Business\Exception\NavigationNodeLocalizedAttributesNotFoundException
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNodeLocalizedAttributes
     */
    protected function findOrCreateNavigationNodeLocalizedAttributesEntity(NavigationNodeLocalizedAttributesTransfer $navigationNodeLocalizedAttributesTransfer)
    {
        if (!$navigationNodeLocalizedAttributesTransfer->getIdNavigationNodeLocalizedAttributes()) {
            return new SpyNavigationNodeLocalizedAttributes();
        }

        $navigationNodeLocalizedAttributesEntity = $this->navigationQueryContainer
            ->queryNavigationNodeLocalizedAttributesById($navigationNodeLocalizedAttributesTransfer->getIdNavigationNodeLocalizedAttributes())
            ->findOne();

        if (!$navigationNodeLocalizedAttributesEntity) {
            throw new NavigationNodeLocalizedAttributesNotFoundException(sprintf(
                'Navigation node localized attributes entity not found with ID %d.',
                $navigationNodeLocalizedAttributesEntity->getIdNavigationNodeLocalizedAttributes()
            ));
        }

        return $navigationNodeLocalizedAttributesEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigationNode $navigationNodeEntity
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer
     */
    protected function hydrateNavigationNodeTransfer(NavigationNodeTransfer $navigationNodeTransfer, SpyNavigationNode $navigationNodeEntity)
    {
        $navigationNodeTransfer->fromArray($navigationNodeEntity->toArray(), true);

        return $navigationNodeTransfer;
    }

    /**
     * @param NavigationNodeLocalizedAttributesTransfer$navigationNodeLocalizedAttributesTransfer
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigationNodeLocalizedAttributes $navigationNodeLocalizedAttributesEntity
     *
     * @return \Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer
     */
    protected function hydrateNavigationNodeLocalizedAttributesTransfer(
        NavigationNodeLocalizedAttributesTransfer $navigationNodeLocalizedAttributesTransfer,
        SpyNavigationNodeLocalizedAttributes $navigationNodeLocalizedAttributesEntity
    ) {
        $navigationNodeLocalizedAttributesTransfer->fromArray($navigationNodeLocalizedAttributesEntity->toArray(), true);

        return $navigationNodeLocalizedAttributesTransfer;
    }

}
