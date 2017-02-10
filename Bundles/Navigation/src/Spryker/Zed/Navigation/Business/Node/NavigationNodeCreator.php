<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business\Node;

use Generated\Shared\Transfer\NavigationNodeTransfer;
use Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer;
use Orm\Zed\Navigation\Persistence\SpyNavigationNode;
use Orm\Zed\Navigation\Persistence\SpyNavigationNodeLocalizedAttributes;
use Spryker\Zed\Navigation\Persistence\NavigationQueryContainerInterface;

class NavigationNodeCreator implements NavigationNodeCreatorInterface
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
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer
     */
    public function createNavigationNode(NavigationNodeTransfer $navigationNodeTransfer)
    {
        $this->assertNavigationNodeForCreation($navigationNodeTransfer);

        $this->navigationQueryContainer->getConnection()->beginTransaction();

        $navigationNodeTransfer = $this->persistNavigationNode($navigationNodeTransfer);
        // TODO: consider splitting navigation node localized attributes to other class
        $navigationNodeTransfer = $this->persistNavigationNodeLocalizedAttributes($navigationNodeTransfer);

        $this->navigationQueryContainer->getConnection()->commit();

        return $navigationNodeTransfer;
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
    protected function persistNavigationNode(NavigationNodeTransfer $navigationNodeTransfer)
    {
        $navigationNodeEntity = $this->createNavigationNodeEntityFromTransfer($navigationNodeTransfer);
        $navigationNodeEntity->save();

        $navigationNodeTransfer->fromArray($navigationNodeEntity->toArray(), true);

        return $navigationNodeTransfer;
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

            $navigationNodeLocalizedAttributesTransfer->fromArray($navigationNodeLocalizedAttributesEntity->toArray(), true);
        }

        return $navigationNodeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer $navigationNodeLocalizedAttributesTransfer
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNodeLocalizedAttributes
     */
    protected function createNavigationNodeLocalizedAttributesEntityFromTransfer(NavigationNodeLocalizedAttributesTransfer $navigationNodeLocalizedAttributesTransfer)
    {
        $navigationNodeLocalizedAttributesEntity = new SpyNavigationNodeLocalizedAttributes();
        $navigationNodeLocalizedAttributesEntity->fromArray($navigationNodeLocalizedAttributesTransfer->toArray());

        return $navigationNodeLocalizedAttributesEntity;
    }

}
