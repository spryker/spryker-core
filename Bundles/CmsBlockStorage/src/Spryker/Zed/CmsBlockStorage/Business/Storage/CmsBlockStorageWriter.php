<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockStorage\Business\Storage;

use Orm\Zed\CmsBlockStorage\Persistence\SpyCmsBlockStorage;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\CmsBlockStorage\Dependency\Service\CmsBlockStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\CmsBlockStorage\Persistence\CmsBlockStorageQueryContainerInterface;

class CmsBlockStorageWriter implements CmsBlockStorageWriterInterface
{
    const ID_CMS_BLOCK = 'id_cms_block';

    /**
     * @var \Spryker\Zed\CmsBlockStorage\Persistence\CmsBlockStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\CmsBlockStorage\Dependency\Service\CmsBlockStorageToUtilSanitizeServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @var \Spryker\Zed\CmsBlockStorage\Dependency\Plugin\CmsBlockStorageDataExpanderPluginInterface[]
     */
    protected $contentWidgetDataExpanderPlugins = [];

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @param \Spryker\Zed\CmsBlockStorage\Persistence\CmsBlockStorageQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\CmsBlockStorage\Dependency\Service\CmsBlockStorageToUtilSanitizeServiceInterface $utilEncodingService
     * @param \Spryker\Zed\CmsBlockStorage\Dependency\Plugin\CmsBlockStorageDataExpanderPluginInterface[] $contentWidgetDataExpanderPlugins
     * @param \Spryker\Shared\Kernel\Store $store
     * @param bool $isSendingToQueue
     */
    public function __construct(
        CmsBlockStorageQueryContainerInterface $queryContainer,
        CmsBlockStorageToUtilSanitizeServiceInterface $utilEncodingService,
        array $contentWidgetDataExpanderPlugins,
        Store $store,
        $isSendingToQueue
    ) {
        $this->queryContainer = $queryContainer;
        $this->utilEncodingService = $utilEncodingService;
        $this->contentWidgetDataExpanderPlugins = $contentWidgetDataExpanderPlugins;
        $this->store = $store;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param array $cmsBlockIds
     *
     * @return void
     */
    public function publish(array $cmsBlockIds)
    {
        $blockEntities = $this->findCmsBlockEntities($cmsBlockIds);
        $blockStorageEntities = $this->findCmsStorageEntities($cmsBlockIds);

        $this->storeData($blockEntities, $blockStorageEntities);
    }

    /**
     * @param array $cmsBlockIds
     *
     * @return void
     */
    public function unpublish(array $cmsBlockIds)
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
        $localeNames = $this->store->getLocales();

        foreach ($blockEntities as $blockEntityArray) {
            foreach ($localeNames as $localeName) {
                $idCmsBlock = $blockEntityArray[static::ID_CMS_BLOCK];
                if (isset($blockStorageEntities[$idCmsBlock][$localeName])) {
                    $this->storeDataSet($blockEntityArray, $localeName, $blockStorageEntities[$idCmsBlock][$localeName]);

                    continue;
                }

                $this->storeDataSet($blockEntityArray, $localeName);
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
    protected function storeDataSet(array $blockEntityArray, $localeName, SpyCmsBlockStorage $cmsBlockStorageEntity = null)
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

        $blockEntityArray = $this->utilEncodingService->arrayFilterRecursive($blockEntityArray);
        foreach ($this->contentWidgetDataExpanderPlugins as $contentWidgetDataExpanderPlugin) {
            $blockEntityArray = $contentWidgetDataExpanderPlugin->expand($blockEntityArray, $localeName);
        }

        $cmsBlockStorageEntity->setData($blockEntityArray);
        $cmsBlockStorageEntity->setFkCmsBlock($blockEntityArray[static::ID_CMS_BLOCK]);
        $cmsBlockStorageEntity->setLocale($localeName);
        $cmsBlockStorageEntity->setName($blockEntityArray['name']);
        $cmsBlockStorageEntity->setIsSendingToQueue($this->isSendingToQueue);
        $cmsBlockStorageEntity->save();
    }

    /**
     * @param array $cmsBlockIds
     *
     * @return \Orm\Zed\CmsBlock\Persistence\SpyCmsBlock[]
     */
    protected function findCmsBlockEntities(array $cmsBlockIds)
    {
        return $this->queryContainer->queryBlockWithRelationsByIds($cmsBlockIds)->find()->getData();
    }

    /**
     * @param array $cmsBlockIds
     *
     * @return array
     */
    protected function findCmsStorageEntities(array $cmsBlockIds)
    {
        $spyCmsBlockStorageEntities = $this->queryContainer->queryCmsStorageEntities($cmsBlockIds)->find();
        $cmsBlockStorageEntitiesByIdAndLocale = [];
        foreach ($spyCmsBlockStorageEntities as $spyCmsBlockStorageEntity) {
            $cmsBlockStorageEntitiesByIdAndLocale[$spyCmsBlockStorageEntity->getFkCmsBlock()][$spyCmsBlockStorageEntity->getLocale()] = $spyCmsBlockStorageEntity;
        }

        return $cmsBlockStorageEntitiesByIdAndLocale;
    }
}
