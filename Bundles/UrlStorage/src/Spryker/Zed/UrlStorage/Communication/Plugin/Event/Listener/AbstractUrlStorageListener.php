<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UrlStorage\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Orm\Zed\UrlStorage\Persistence\SpyUrlStorage;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\UrlStorage\Persistence\UrlStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\UrlStorage\Communication\UrlStorageCommunicationFactory getFactory()
 */
class AbstractUrlStorageListener extends AbstractPlugin
{
    const ID_URL = 'id_url';
    const FK_URL = 'fkUrl';

    /**
     * @param array $urlIds
     *
     * @return void
     */
    protected function publish(array $urlIds)
    {
        $spyUrlEntities = $this->findUrlEntities($urlIds);
        $spyUrlEntities = $this->appendLocaleUrlsToUrlEntities($spyUrlEntities);
        $spyUrlStorageEntities = $this->findUrlStorageEntitiesByIds($urlIds);

        $this->storeData($spyUrlEntities, $spyUrlStorageEntities);
    }

    /**
     * @param array $urlIds
     *
     * @return void
     */
    protected function unpublish(array $urlIds)
    {
        $spyUrlStorageEntities = $this->findUrlStorageEntitiesByIds($urlIds);
        foreach ($spyUrlStorageEntities as $spyUrlStorageEntity) {
            $spyUrlStorageEntity->delete();
        }
    }

    /**
     * @param array $spyUrlEntities
     * @param array $spyUrlStorageEntities
     *
     * @return void
     */
    protected function storeData(array $spyUrlEntities, array $spyUrlStorageEntities)
    {
        foreach ($spyUrlEntities as $spyUrlEntity) {
            $idUrl = $spyUrlEntity[static::ID_URL];
            if (isset($spyUrlStorageEntities[$idUrl])) {
                if ($spyUrlStorageEntities[$idUrl]->getUrl() === $spyUrlEntity['url']) {
                    $this->storeDataSet($spyUrlEntity, $spyUrlStorageEntities[$idUrl]);
                } else {
                    $this->storeDataSet($spyUrlEntity);
                    $spyUrlStorageEntities[$idUrl]->delete();
                }
            } else {
                $this->storeDataSet($spyUrlEntity);
            }
        }
    }

    /**
     * @param array $spyUrlEntity
     * @param \Orm\Zed\UrlStorage\Persistence\SpyUrlStorage|null $spyUrlStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(array $spyUrlEntity, SpyUrlStorage $spyUrlStorageEntity = null)
    {
        if ($spyUrlStorageEntity === null) {
            $spyUrlStorageEntity = new SpyUrlStorage();
        }

        $resource = $this->findResourceArguments($spyUrlEntity);
        $spyUrlStorageEntity->setByName('fk_' . $resource['type'], $resource['value']);
        $spyUrlStorageEntity->setUrl($spyUrlEntity['url']);
        $spyUrlStorageEntity->setFkUrl($spyUrlEntity[static::ID_URL]);
        $spyUrlStorageEntity->setData($this->getFactory()->getUtilSanitizeService()->arrayFilterRecursive($spyUrlEntity));
        $spyUrlStorageEntity->setStore($this->getStoreName());
        $spyUrlStorageEntity->save();
    }

    /**
     * @param array $data
     *
     * @return array|bool
     */
    protected function findResourceArguments(array $data)
    {
        foreach ($data as $columnName => $value) {
            if (!$this->isFkResourceUrl($columnName, $value) || $columnName === 'fk_locale') {
                continue;
            }

            $type = str_replace('fk_resource_', '', $columnName);

            return [
                'type' => $type,
                'value' => $value,
            ];
        }

        return false;
    }

    /**
     * @param string $columnName
     * @param string $value
     *
     * @return bool
     */
    protected function isFkResourceUrl($columnName, $value)
    {
        return $value !== null && strpos($columnName, 'fk_resource_') === 0;
    }

    /**
     * @param array $urlIds
     *
     * @return array
     */
    protected function findUrlEntities(array $urlIds)
    {
        return $this->getQueryContainer()->queryUrls($urlIds)->find();
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl[] $spyUrlEntities
     *
     * @return array
     */
    protected function appendLocaleUrlsToUrlEntities($spyUrlEntities)
    {
        $urlResources = [];
        foreach ($spyUrlEntities as $spyUrlEntity) {
            if (isset($urlResources[$spyUrlEntity->getResourceType()])) {
                $urlResources[$spyUrlEntity->getResourceType()][] = $spyUrlEntity->getResourceId();
                continue;
            }

            $urlResources[$spyUrlEntity->getResourceType()] = [$spyUrlEntity->getResourceId()];
        }

        foreach ($urlResources as $resourceType => $resourceIds) {
            $urlResources[$resourceType] = $this->getQueryContainer()
                ->queryUrlsByResourceTypeAndIds($resourceType, $resourceIds)
                ->withColumn(SpyLocaleTableMap::COL_LOCALE_NAME, UrlTransfer::LOCALE_NAME)
                ->find()
                ->getData();
        }

        $spyUrlEntitiesWithLocaleUrls = [];
        foreach ($spyUrlEntities as $spyUrlEntity) {
            $localeUrls = $urlResources[$spyUrlEntity->getResourceType()];
            $spyUrlEntity = $spyUrlEntity->toArray();
            $spyUrlEntity[UrlTransfer::LOCALE_URLS] = $localeUrls;
            $spyUrlEntitiesWithLocaleUrls[] = $spyUrlEntity;
        }

        return $spyUrlEntitiesWithLocaleUrls;
    }

    /**
     * @param array $urlIds
     *
     * @return array
     */
    protected function findUrlStorageEntitiesByIds(array $urlIds)
    {
        return $this->getQueryContainer()->queryUrlStorageByIds($urlIds)->find()->toKeyIndex(static::FK_URL);
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return $this->getFactory()->getStore()->getStoreName();
    }
}
