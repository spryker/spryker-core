<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationStorage\Communication\Plugin\Event\Listener;

use ArrayObject;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer;
use Generated\Shared\Transfer\NavigationNodeStorageTransfer;
use Generated\Shared\Transfer\NavigationStorageTransfer;
use Generated\Shared\Transfer\NavigationTransfer;
use Generated\Shared\Transfer\NavigationTreeTransfer;
use Orm\Zed\NavigationStorage\Persistence\SpyNavigationStorage;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\NavigationStorage\Persistence\NavigationStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\NavigationStorage\Communication\NavigationStorageCommunicationFactory getFactory()
 */
abstract class AbstractNavigationStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @param array $navigationIds
     *
     * @return void
     */
    protected function publish(array $navigationIds)
    {
        $navigationTreeTransfers = $this->getNavigationTreeTransfer($navigationIds);
        $spyNavigationStorageEntities = $this->findNavigationStorageEntitiesByNavigationIds($navigationIds);

        $this->storeData($navigationTreeTransfers, $spyNavigationStorageEntities);
    }

    /**
     * @param array $navigationMenuKeyIds
     *
     * @return void
     */
    protected function unpublish(array $navigationMenuKeyIds)
    {
        $spyNavigationMenuTranslationStorageEntities = $this->findNavigationStorageEntitiesByNavigationIds($navigationMenuKeyIds);
        foreach ($spyNavigationMenuTranslationStorageEntities as $spyNavigationMenuTranslationStorageLocalizedEntities) {
            foreach ($spyNavigationMenuTranslationStorageLocalizedEntities as $spyNavigationMenuTranslationStorageLocalizedEntity) {
                $spyNavigationMenuTranslationStorageLocalizedEntity->delete();
            }
        }
    }

    /**
     * @param array $navigationTreeTransfers
     * @param array $spyNavigationMenuTranslationStorageEntities
     *
     * @return void
     */
    protected function storeData(array $navigationTreeTransfers, array $spyNavigationMenuTranslationStorageEntities)
    {
        foreach ($navigationTreeTransfers as $navigationTreeTransfer) {
            foreach ($navigationTreeTransfer as $localeName => $navigationTreeByLocaleTransfer) {
                if (isset($spyNavigationMenuTranslationStorageEntities[$navigationTreeByLocaleTransfer->getNavigation()->getIdNavigation()][$localeName])) {
                    $this->storeDataSet($navigationTreeByLocaleTransfer, $localeName, $spyNavigationMenuTranslationStorageEntities[$navigationTreeByLocaleTransfer->getNavigation()->getIdNavigation()][$localeName]);
                } else {
                    $this->storeDataSet($navigationTreeByLocaleTransfer, $localeName);
                }
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTreeTransfer $navigationTreeByLocaleTransfer
     * @param string $localeName
     * @param \Orm\Zed\NavigationStorage\Persistence\SpyNavigationStorage|null $spyNavigationStorage
     *
     * @return void
     */
    protected function storeDataSet(NavigationTreeTransfer $navigationTreeByLocaleTransfer, $localeName, ?SpyNavigationStorage $spyNavigationStorage = null)
    {
        if ($spyNavigationStorage === null) {
            $spyNavigationStorage = new SpyNavigationStorage();
        }

        $newTransfer = $this->mapToNavigationStorageTransfer($navigationTreeByLocaleTransfer);
        $data = $this->getFactory()->getUtilSanitizeService()->arrayFilterRecursive($newTransfer->toArray());

        $spyNavigationStorage->setFkNavigation($navigationTreeByLocaleTransfer->getNavigation()->getIdNavigation());
        $spyNavigationStorage->setNavigationKey($navigationTreeByLocaleTransfer->getNavigation()->getKey());
        $spyNavigationStorage->setLocale($localeName);
        $spyNavigationStorage->setStore($this->getFactory()->getStore()->getStoreName());
        $spyNavigationStorage->setData($data);
        $spyNavigationStorage->save();
    }

    /**
     * @param array $navigationIds
     *
     * @return array
     */
    protected function getNavigationTreeTransfer(array $navigationIds)
    {
        $navigationTrees = [];
        $localeNames = $this->getFactory()->getStore()->getLocales();
        $locales = $this->getQueryContainer()->queryLocalesWithLocaleNames($localeNames)->find()->getData();
        foreach ($navigationIds as $navigationId) {
            $navigationTransfer = new NavigationTransfer();
            $navigationTransfer->setIdNavigation($navigationId);
            foreach ($locales as $locale) {
                $localeTransfer = (new LocaleTransfer())->fromArray($locale->toArray(), true);
                $navigationTrees[$navigationId][$localeTransfer->getLocaleName()] = $this->getFactory()->getNavigationFacade()->findNavigationTree($navigationTransfer, $localeTransfer);
            }
        }

        return $navigationTrees;
    }

    /**
     * @param array $navigationIds
     *
     * @return array
     */
    protected function findNavigationStorageEntitiesByNavigationIds(array $navigationIds)
    {
        $spyNavigationStorageEntities = $this->getQueryContainer()->queryNavigationStorageByNavigationIds($navigationIds)->find();
        $navigationStorageEntitiesByIdAndLocale = [];
        foreach ($spyNavigationStorageEntities as $spyNavigationStorageEntity) {
            $navigationStorageEntitiesByIdAndLocale[$spyNavigationStorageEntity->getFkNavigation()][$spyNavigationStorageEntity->getLocale()] = $spyNavigationStorageEntity;
        }

        return $navigationStorageEntitiesByIdAndLocale;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTreeTransfer $navigationTreeTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationStorageTransfer
     */
    protected function mapToNavigationStorageTransfer(NavigationTreeTransfer $navigationTreeTransfer)
    {
        $navigationStorageTransfer = (new NavigationStorageTransfer())
            ->setName($navigationTreeTransfer->getNavigation()->getName())
            ->setKey($navigationTreeTransfer->getNavigation()->getKey())
            ->setIsActive($navigationTreeTransfer->getNavigation()->getIsActive())
            ->setId($navigationTreeTransfer->getNavigation()->getIdNavigation());

        $nodes = $this->mapToNavigationNodeStorageTransfer($navigationTreeTransfer->getNodes());
        $navigationStorageTransfer->setNodes($nodes);

        return $navigationStorageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTreeNodeTransfer[]|\ArrayObject $navigationTreeNodeTransfers
     *
     * @return array
     */
    protected function mapToNavigationNodeStorageTransfer(ArrayObject $navigationTreeNodeTransfers)
    {
        $nodes = new ArrayObject();
        foreach ($navigationTreeNodeTransfers as $navigationTreeNodeTransfer) {
            $nodeTransfer = new NavigationNodeStorageTransfer();
            $nodeTransfer->setId($navigationTreeNodeTransfer->getNavigationNode()->getIdNavigationNode());
            $nodeTransfer->setTitle($navigationTreeNodeTransfer->getNavigationNode()->getNavigationNodeLocalizedAttributes()[0]->getTitle());
            $nodeTransfer->setCssClass($navigationTreeNodeTransfer->getNavigationNode()->getNavigationNodeLocalizedAttributes()[0]->getCssClass());
            $nodeTransfer->setUrl($this->getNavigationNodeUrl($navigationTreeNodeTransfer->getNavigationNode()->getNavigationNodeLocalizedAttributes()[0]));
            $nodeTransfer->setNodeType($navigationTreeNodeTransfer->getNavigationNode()->getNodeType());
            $nodeTransfer->setIsActive($navigationTreeNodeTransfer->getNavigationNode()->getIsActive());
            $nodeTransfer->setValidFrom($navigationTreeNodeTransfer->getNavigationNode()->getValidFrom());
            $nodeTransfer->setValidTo($navigationTreeNodeTransfer->getNavigationNode()->getValidTo());

            if (!empty($navigationTreeNodeTransfer->getChildren())) {
                $nodeTransfer->setChildren($this->mapToNavigationNodeStorageTransfer($navigationTreeNodeTransfer->getChildren()));
            }

            $nodes[] = $nodeTransfer;
        }

        return $nodes;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeLocalizedAttributesTransfer $navigationNodeLocalizedAttributesTransfer
     *
     * @return string
     */
    protected function getNavigationNodeUrl(NavigationNodeLocalizedAttributesTransfer $navigationNodeLocalizedAttributesTransfer)
    {
        $url = $navigationNodeLocalizedAttributesTransfer->getLink() ?? '';
        $url = $navigationNodeLocalizedAttributesTransfer->getExternalUrl() ?? $url;
        $url = $navigationNodeLocalizedAttributesTransfer->getCategoryUrl() ?? $url;
        $url = $navigationNodeLocalizedAttributesTransfer->getCmsPageUrl() ?? $url;
        $url = $navigationNodeLocalizedAttributesTransfer->getUrl() ?? $url;

        return $url;
    }
}
