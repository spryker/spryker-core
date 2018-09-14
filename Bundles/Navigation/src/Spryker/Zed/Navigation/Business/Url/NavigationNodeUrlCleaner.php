<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business\Url;

use Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer;
use Generated\Shared\Transfer\NavigationNodeTransfer;
use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Navigation\Persistence\SpyNavigationNodeLocalizedAttributes;
use Spryker\Zed\Navigation\Business\Node\NavigationNodeUpdaterInterface;
use Spryker\Zed\Navigation\Persistence\NavigationQueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class NavigationNodeUrlCleaner implements NavigationNodeUrlCleanerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\Navigation\Persistence\NavigationQueryContainerInterface
     */
    protected $navigationQueryContainer;

    /**
     * @var \Spryker\Zed\Navigation\Business\Node\NavigationNodeUpdaterInterface
     */
    protected $nodeUpdater;

    /**
     * @param \Spryker\Zed\Navigation\Persistence\NavigationQueryContainerInterface $navigationQueryContainer
     * @param \Spryker\Zed\Navigation\Business\Node\NavigationNodeUpdaterInterface $nodeUpdater
     */
    public function __construct(NavigationQueryContainerInterface $navigationQueryContainer, NavigationNodeUpdaterInterface $nodeUpdater)
    {
        $this->navigationQueryContainer = $navigationQueryContainer;
        $this->nodeUpdater = $nodeUpdater;
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    public function detachUrlFromNavigationNodes(UrlTransfer $urlTransfer)
    {
        $this->assertUrlForDetach($urlTransfer);

        $this->handleDatabaseTransaction(function () use ($urlTransfer) {
            $this->executeDetachUrlFromNavigationNodesTransaction($urlTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    protected function assertUrlForDetach(UrlTransfer $urlTransfer)
    {
        $urlTransfer->requireIdUrl();
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return void
     */
    protected function executeDetachUrlFromNavigationNodesTransaction(UrlTransfer $urlTransfer)
    {
        $navigationNodeLocalizedAttributesEntities = $this->findNodesByUrl($urlTransfer);
        foreach ($navigationNodeLocalizedAttributesEntities as $navigationNodeLocalizedAttributes) {
            $navigationNodeTransfer = $this->createUpdateNavigationNodeTransfer($navigationNodeLocalizedAttributes);
            $this->nodeUpdater->updateNavigationNode($navigationNodeTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\UrlTransfer $urlTransfer
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNodeLocalizedAttributes[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findNodesByUrl(UrlTransfer $urlTransfer)
    {
        return $this->navigationQueryContainer
            ->queryNavigationNodeLocalizedAttributesByFkUrl($urlTransfer->getIdUrl())
            ->find();
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigationNodeLocalizedAttributes $navigationNodeLocalizedAttributes
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer
     */
    protected function createUpdateNavigationNodeTransfer(SpyNavigationNodeLocalizedAttributes $navigationNodeLocalizedAttributes)
    {
        $navigationNodeEntity = $navigationNodeLocalizedAttributes->getSpyNavigationNode();

        $navigationNodeLocalizedAttributesTransfer = new NavigationNodeLocalizedAttributesTransfer();
        $navigationNodeLocalizedAttributesTransfer
            ->setIdNavigationNodeLocalizedAttributes($navigationNodeLocalizedAttributes->getIdNavigationNodeLocalizedAttributes())
            ->setFkUrl(null);

        $navigationNodeTransfer = new NavigationNodeTransfer();
        $navigationNodeTransfer
            ->setIdNavigationNode($navigationNodeEntity->getIdNavigationNode())
            ->setIsActive(false)
            ->addNavigationNodeLocalizedAttribute($navigationNodeLocalizedAttributesTransfer);

        return $navigationNodeTransfer;
    }
}
