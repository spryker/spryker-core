<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\CmsBlockStorage\Persistence\SpyCmsBlockStorage;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\CmsBlockStorage\Communication\CmsBlockStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsBlockStorage\Persistence\CmsBlockStorageQueryContainerInterface getQueryContainer()
 */
class AbstractCmsBlockStorageListener extends AbstractPlugin
{
    use DatabaseTransactionHandlerTrait;

    const ID_CMS_BLOCK = 'id_cms_block';

    /**
     * Specification:
     * - Aggregates all cms block related data for given cms block IDs
     * - Saves aggregated data to database
     * - Sends aggregated data to synchronization queue
     *
     * @param array $cmsBlockIds
     *
     * @return void
     */
    protected function publish(array $cmsBlockIds)
    {
        $blockEntities = $this->findCmsBlockEntities($cmsBlockIds);
        $blockStorageEntities = $this->findCmsStorageEntities($cmsBlockIds);

        $this->storeData($blockEntities, $blockStorageEntities);
    }

    /**
     * Specification:
     * - Delete cms stored block data for given cms block IDs
     * - Sends deleted keys to synchronization queue
     *
     * @param array $cmsBlockIds
     *
     * @return void
     */
    protected function unpublish(array $cmsBlockIds)
    {
        $blockStorageEntities = $this->findCmsStorageEntities($cmsBlockIds);
        foreach ($blockStorageEntities as $blockStorageEntity) {
            $blockStorageEntity->delete();
        }
    }

    /**
     * @param array $blockEntities
     * @param array $blockStorageEntities
     *
     * @return void
     */
    protected function storeData(array $blockEntities, array $blockStorageEntities)
    {
        $localeNames = $this->getStore()->getLocales();

        foreach ($blockEntities as $blockEntityArray) {
            foreach ($localeNames as $localeName) {
                $idCmsBlock = $blockEntityArray[static::ID_CMS_BLOCK];
                if (isset($blockStorageEntities[$idCmsBlock][$localeName])) {
                    $this->storeDataSet($blockEntityArray, $localeName, $blockStorageEntities[$idCmsBlock][$localeName]);
                } else {
                    $this->storeDataSet($blockEntityArray, $localeName);
                }
            }
        }
    }

    /**
     * @param array $blockEntityArray
     * @param string $localeName
     * @param \Orm\Zed\CmsBlockStorage\Persistence\SpyCmsBlockStorage|null $cmsBlockStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(array $blockEntityArray, $localeName, ?SpyCmsBlockStorage $cmsBlockStorageEntity = null)
    {
        if ($cmsBlockStorageEntity === null) {
            $cmsBlockStorageEntity = new SpyCmsBlockStorage();
        }

        if (!$blockEntityArray['is_active']) {
            if (!$cmsBlockStorageEntity->isNew()) {
                $cmsBlockStorageEntity->delete();
            }

            return;
        }

        $blockEntityArray = $this->getFactory()->getUtilSanitize()->arrayFilterRecursive($blockEntityArray);
        foreach ($this->getFactory()->getContentWidgetDataExpanderPlugins() as $contentWidgetDataExpanderPlugin) {
            $blockEntityArray = $contentWidgetDataExpanderPlugin->expand($blockEntityArray, $localeName);
        }

        $cmsBlockStorageEntity->setData($blockEntityArray);
        $cmsBlockStorageEntity->setFkCmsBlock($blockEntityArray[static::ID_CMS_BLOCK]);
        $cmsBlockStorageEntity->setLocale($localeName);
        $cmsBlockStorageEntity->setName($blockEntityArray['name']);
        $cmsBlockStorageEntity->save();
    }

    /**
     * @param array $cmsBlockIds
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock[]
     */
    protected function findCmsBlockEntities(array $cmsBlockIds)
    {
        return $this->getQueryContainer()->queryBlockWithRelationsByIds($cmsBlockIds)->find()->getData();
    }

    /**
     * @param array $cmsBlockIds
     *
     * @return array
     */
    protected function findCmsStorageEntities(array $cmsBlockIds)
    {
        $spyCmsBlockStorageEntities = $this->getQueryContainer()->queryCmsStorageEntities($cmsBlockIds)->find();
        $cmsBlockStorageEntitiesByIdAndLocale = [];
        foreach ($spyCmsBlockStorageEntities as $spyCmsBlockStorageEntity) {
            $cmsBlockStorageEntitiesByIdAndLocale[$spyCmsBlockStorageEntity->getFkCmsBlock()][$spyCmsBlockStorageEntity->getLocale()] = $spyCmsBlockStorageEntity;
        }

        return $cmsBlockStorageEntitiesByIdAndLocale;
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return Store::getInstance();
    }
}
