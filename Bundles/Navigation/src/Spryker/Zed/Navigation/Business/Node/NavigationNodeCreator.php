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
use Spryker\Zed\Navigation\Persistence\NavigationQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class NavigationNodeCreator implements NavigationNodeCreatorInterface
{
    use DatabaseTransactionHandlerTrait;

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
    public function createNavigationNode(NavigationNodeTransfer $navigationNodeTransfer)
    {
        $this->assertNavigationNodeForCreation($navigationNodeTransfer);

        return $this->handleDatabaseTransaction(function () use ($navigationNodeTransfer) {
            return $this->executeCreateNavigationNodeTransaction($navigationNodeTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return void
     */
    protected function assertNavigationNodeForCreation(NavigationNodeTransfer $navigationNodeTransfer)
    {
        $navigationNodeTransfer
            ->requireFkNavigation()
            ->requireNavigationNodeLocalizedAttributes();

        foreach ($navigationNodeTransfer->getNavigationNodeLocalizedAttributes() as $navigationNodeLocalizedAttributesTransfer) {
            $this->assertNavigationNodeLocalizedAttributesForCreation($navigationNodeLocalizedAttributesTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer $navigationNodeLocalizedAttributesTransfer
     *
     * @return void
     */
    protected function assertNavigationNodeLocalizedAttributesForCreation(NavigationNodeLocalizedAttributesTransfer $navigationNodeLocalizedAttributesTransfer)
    {
        $navigationNodeLocalizedAttributesTransfer
            ->requireFkLocale()
            ->requireTitle();
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer
     */
    protected function executeCreateNavigationNodeTransaction(NavigationNodeTransfer $navigationNodeTransfer)
    {
        $navigationNodeTransfer = $this->persistNavigationNode($navigationNodeTransfer);
        $navigationNodeTransfer = $this->persistNavigationNodeLocalizedAttributes($navigationNodeTransfer);
        $this->navigationNodeTouch->touchNavigationNode($navigationNodeTransfer);

        return $navigationNodeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer
     */
    protected function persistNavigationNode(NavigationNodeTransfer $navigationNodeTransfer)
    {
        $navigationNodeEntity = $this->createNavigationNodeEntityFromTransfer($navigationNodeTransfer);
        $navigationNodeEntity->save();

        return $this->hydrateNavigationNodeTransfer($navigationNodeTransfer, $navigationNodeEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNode
     */
    protected function createNavigationNodeEntityFromTransfer(NavigationNodeTransfer $navigationNodeTransfer)
    {
        $navigationNodeEntity = new SpyNavigationNode();
        $navigationNodeEntity->fromArray($navigationNodeTransfer->toArray());

        return $navigationNodeEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer
     */
    protected function persistNavigationNodeLocalizedAttributes(NavigationNodeTransfer $navigationNodeTransfer)
    {
        foreach ($navigationNodeTransfer->getNavigationNodeLocalizedAttributes() as $navigationNodeLocalizedAttributesTransfer) {
            $navigationNodeLocalizedAttributesEntity = $this->createNavigationNodeLocalizedAttributesEntityFromTransfer($navigationNodeLocalizedAttributesTransfer);
            $navigationNodeLocalizedAttributesEntity->setFkNavigationNode($navigationNodeTransfer->getIdNavigationNode());
            $navigationNodeLocalizedAttributesEntity->save();

            $this->hydrateNavigationNodeLocalizedAttributesTransfer($navigationNodeLocalizedAttributesTransfer, $navigationNodeLocalizedAttributesEntity);
        }

        return $navigationNodeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer $navigationNodeLocalizedAttributesTransfer
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNodeLocalizedAttributes
     */
    protected function createNavigationNodeLocalizedAttributesEntityFromTransfer(
        NavigationNodeLocalizedAttributesTransfer $navigationNodeLocalizedAttributesTransfer
    ) {
        $navigationNodeLocalizedAttributesEntity = new SpyNavigationNodeLocalizedAttributes();
        $navigationNodeLocalizedAttributesEntity->fromArray($navigationNodeLocalizedAttributesTransfer->toArray());

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
     * @param \Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer $navigationNodeLocalizedAttributesTransfer
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
