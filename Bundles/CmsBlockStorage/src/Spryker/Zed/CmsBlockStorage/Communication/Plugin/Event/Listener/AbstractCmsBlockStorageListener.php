<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockStorage\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\LocaleTransfer;
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
    const FK_CMS_BLOCK = 'fkCmsBlock';

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
        $blockStorageEntities = $this->findCmsBlockStorageEntities($cmsBlockIds);

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
        $blockStorageEntities = $this->findCmsBlockStorageEntities($cmsBlockIds);
        foreach ($blockStorageEntities as $blockStorageEntity) {
            $blockStorageEntity->delete();
        }
    }

    /**
     * @param array $blockEntities
     * @param array $blockStorageEntities
     * @param bool $createIfNotExists
     * @param bool $refresh
     *
     * @return void
     */
    protected function storeData(array $blockEntities, array $blockStorageEntities, $createIfNotExists = true, $refresh = false)
    {
        foreach ($blockEntities as $blockEntityArray) {
            $idCmsBlock = $blockEntityArray[static::ID_CMS_BLOCK];
            if (isset($blockStorageEntities[$idCmsBlock])) {
                $this->storeDataSet($blockEntityArray, $blockStorageEntities[$idCmsBlock], $refresh);
            } elseif ($createIfNotExists) {
                $this->storeDataSet($blockEntityArray, null, $refresh);
            }
        }
    }

    /**
     * @param array $blockEntityArray
     * @param \Orm\Zed\CmsBlockStorage\Persistence\SpyCmsBlockStorage|null $blockStorageEntity
     * @param bool $refresh
     *
     * @return void
     */
    protected function storeDataSet(array $blockEntityArray, SpyCmsBlockStorage $blockStorageEntity = null, $refresh = false)
    {
        $this->saveCmsBlockStorageDataSet($blockEntityArray, $blockStorageEntity, $refresh);
    }

    /**
     * @param array $blockEntityArray
     * @param \Orm\Zed\CmsBlockStorage\Persistence\SpyCmsBlockStorage|null $cmsBlockStorageEntity
     * @param bool $refresh
     *
     * @return void
     */
    protected function saveCmsBlockStorageDataSet(array $blockEntityArray, $cmsBlockStorageEntity = null, $refresh = false)
    {
        if ($cmsBlockStorageEntity === null) {
            $cmsBlockStorageEntity = new SpyCmsBlockStorage();
        }
        $blockEntityArray = $this->getFactory()->getUtilSynchronization()->arrayFilterRecursive($blockEntityArray);

        if ($refresh) {
            $blockEntityArray = array_replace_recursive($cmsBlockStorageEntity->getData(), $blockEntityArray);
        }

        foreach ($this->getFactory()->getContentWidgetDataExpanderPlugins() as $contentWidgetDataExpanderPlugin) {
            $blockEntityArray = $contentWidgetDataExpanderPlugin->expand($blockEntityArray);
        }

        $cmsBlockStorageEntity->setData($blockEntityArray);
        $cmsBlockStorageEntity->setFkCmsBlock($blockEntityArray[static::ID_CMS_BLOCK]);
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
    protected function findCmsBlockStorageEntities(array $cmsBlockIds)
    {
        return $this->getQueryContainer()->queryCmsStorageEntities($cmsBlockIds)->find()->toKeyIndex(static::FK_CMS_BLOCK);
    }
}
