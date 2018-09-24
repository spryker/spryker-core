<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business\Tree;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer;
use Generated\Shared\Transfer\NavigationNodeTransfer;
use Generated\Shared\Transfer\NavigationTransfer;
use Generated\Shared\Transfer\NavigationTreeNodeTransfer;
use Generated\Shared\Transfer\NavigationTreeTransfer;
use Orm\Zed\Navigation\Persistence\Base\SpyNavigationNodeLocalizedAttributes;
use Orm\Zed\Navigation\Persistence\Map\SpyNavigationNodeLocalizedAttributesTableMap;
use Orm\Zed\Navigation\Persistence\SpyNavigation;
use Orm\Zed\Navigation\Persistence\SpyNavigationNode;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Navigation\Persistence\NavigationQueryContainerInterface;

class NavigationTreeReader implements NavigationTreeReaderInterface
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
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationTreeTransfer|null
     */
    public function findNavigationTree(NavigationTransfer $navigationTransfer, ?LocaleTransfer $localeTransfer = null)
    {
        $this->assertNavigationForRead($navigationTransfer);
        $this->assertLocaleForRead($localeTransfer);

        $navigationEntity = $this->findNavigationEntity($navigationTransfer);

        if (!$navigationEntity) {
            return null;
        }

        return $this->mapNavigationEntityToNavigationTreeTransfer($navigationEntity, $localeTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return void
     */
    protected function assertNavigationForRead(NavigationTransfer $navigationTransfer)
    {
        $navigationTransfer->requireIdNavigation();
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return void
     */
    protected function assertLocaleForRead(?LocaleTransfer $localeTransfer = null)
    {
        if (!$localeTransfer) {
            return;
        }

        $localeTransfer->requireIdLocale();
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigation|null
     */
    protected function findNavigationEntity(NavigationTransfer $navigationTransfer)
    {
        return $this->navigationQueryContainer
            ->queryNavigationById($navigationTransfer->getIdNavigation())
            ->findOne();
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigation $navigationEntity
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationTreeTransfer
     */
    protected function mapNavigationEntityToNavigationTreeTransfer(SpyNavigation $navigationEntity, ?LocaleTransfer $localeTransfer = null)
    {
        $navigationTreeTransfer = new NavigationTreeTransfer();

        $navigationTransfer = $this->mapNavigationEntityToTransfer($navigationEntity);
        $navigationTreeTransfer->setNavigation($navigationTransfer);

        $rootNavigationNodes = $this->findRootNavigationNodes($navigationEntity);
        $nodesWithoutPosition = new ArrayObject();
        foreach ($rootNavigationNodes as $navigationNodeEntity) {
            $navigationTreeNodeTransfer = $this->getNavigationTreeNodeRecursively($navigationNodeEntity, $localeTransfer);
            if ($navigationNodeEntity->getPosition() === null) {
                $nodesWithoutPosition[] = $navigationTreeNodeTransfer;

                continue;
            }

            $navigationTreeTransfer->addNode($navigationTreeNodeTransfer);
        }

        foreach ($nodesWithoutPosition as $item) {
            $navigationTreeTransfer->getNodes()->append($item);
        }

        return $navigationTreeTransfer;
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigation $navigationEntity
     *
     * @return \Generated\Shared\Transfer\NavigationTransfer
     */
    protected function mapNavigationEntityToTransfer(SpyNavigation $navigationEntity)
    {
        $navigationTransfer = new NavigationTransfer();
        $navigationTransfer->fromArray($navigationEntity->toArray(), true);

        return $navigationTransfer;
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigation $navigationEntity
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNode[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findRootNavigationNodes(SpyNavigation $navigationEntity)
    {
        return $this->navigationQueryContainer
            ->queryRootNavigationNodesByIdNavigation($navigationEntity->getIdNavigation())
            ->find();
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigationNode $navigationNodeEntity
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationTreeNodeTransfer
     */
    protected function getNavigationTreeNodeRecursively(SpyNavigationNode $navigationNodeEntity, ?LocaleTransfer $localeTransfer = null)
    {
        $navigationTreeNodeTransfer = new NavigationTreeNodeTransfer();

        $navigationNodeTransfer = $this->mapNavigationNodeEntityToTransfer($navigationNodeEntity, $localeTransfer);
        $navigationTreeNodeTransfer->setNavigationNode($navigationNodeTransfer);

        $childrenNavigationNodeEntities = $this->findChildrenNavigationNodes($navigationNodeTransfer);
        foreach ($childrenNavigationNodeEntities as $childrenNavigationNodeEntity) {
            $childNavigationTreeNodeTransfer = $this->getNavigationTreeNodeRecursively($childrenNavigationNodeEntity, $localeTransfer);
            $navigationTreeNodeTransfer->addChild($childNavigationTreeNodeTransfer);
        }

        return $navigationTreeNodeTransfer;
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigationNode $navigationNodeEntity
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer
     */
    protected function mapNavigationNodeEntityToTransfer(SpyNavigationNode $navigationNodeEntity, ?LocaleTransfer $localeTransfer = null)
    {
        $navigationNodeTransfer = new NavigationNodeTransfer();
        $navigationNodeTransfer->fromArray($navigationNodeEntity->toArray(), true);

        $localizedAttributes = $this->findLocalizedAttributes($navigationNodeEntity, $localeTransfer);
        foreach ($localizedAttributes as $navigationNodeLocalizedAttributesEntity) {
            $navigationNodeLocalizedAttributesTransfer = $this->mapNavigationNodeLocalizedAttributesEntityToTransfer($navigationNodeLocalizedAttributesEntity);
            $navigationNodeTransfer->addNavigationNodeLocalizedAttribute($navigationNodeLocalizedAttributesTransfer);
        }

        return $navigationNodeTransfer;
    }

    /**
     * @param \Orm\Zed\Navigation\Persistence\SpyNavigationNode $navigationNodeEntity
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNodeLocalizedAttributes[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findLocalizedAttributes(SpyNavigationNode $navigationNodeEntity, ?LocaleTransfer $localeTransfer = null)
    {
        $criteria = $this->createLocalizedAttributeFilterCriteria($localeTransfer);

        return $navigationNodeEntity->getSpyNavigationNodeLocalizedAttributess($criteria);
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer|null $localeTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\Criteria
     */
    protected function createLocalizedAttributeFilterCriteria(?LocaleTransfer $localeTransfer = null)
    {
        $criteria = new Criteria();

        if ($localeTransfer) {
            $criteria->add(SpyNavigationNodeLocalizedAttributesTableMap::COL_FK_LOCALE, $localeTransfer->getIdLocale());
        }

        return $criteria;
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

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationNode[]|\Propel\Runtime\Collection\ObjectCollection
     */
    protected function findChildrenNavigationNodes(NavigationNodeTransfer $navigationNodeTransfer)
    {
        return $this->navigationQueryContainer
            ->queryNavigationNodesByFkParentNavigationNode($navigationNodeTransfer->getIdNavigationNode())
            ->find();
    }
}
