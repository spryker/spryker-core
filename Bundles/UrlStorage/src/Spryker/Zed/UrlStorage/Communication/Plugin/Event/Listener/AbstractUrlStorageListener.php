<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UrlStorage\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\UrlTransfer;
use Orm\Zed\Url\Persistence\SpyUrl;
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
        $urlEntities = $this->findUrlEntities($urlIds);
        $urlEntities = $this->appendLocaleUrlsToUrlEntities($urlEntities);
        $urlStorageEntities = $this->findUrlStorageEntitiesByIds($urlIds);

        $this->storeData($urlEntities, $urlStorageEntities);
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
     * @param \Orm\Zed\Url\Persistence\SpyUrl[] $urlEntities
     *
     * @return array
     */
    protected function appendLocaleUrlsToUrlEntities(array $urlEntities)
    {
        $urlResources = $this->getUrlResourcesFromEntities($urlEntities);
        $urls = $this->getUrlsByResources($urlResources);

        return $this->appendLocaleUrlsToUrls($urls);
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl[] $urlEntities
     *
     * @return array
     */
    protected function getUrlResourcesFromEntities(array $urlEntities)
    {
        $urlResources = [];
        foreach ($urlEntities as $urlEntity) {
            if (isset($urlResources[$urlEntity->getResourceType()])) {
                $urlResources[$urlEntity->getResourceType()][] = $urlEntity->getResourceId();
                continue;
            }

            $urlResources[$urlEntity->getResourceType()] = [$urlEntity->getResourceId()];
        }

        return $urlResources;
    }

    /**
     * @param array $urls
     *
     * @return array
     */
    protected function appendLocaleUrlsToUrls(array $urls)
    {
        $urlsWithLocaleUrls = [];
        foreach ($urls as $urlEntity) {
            $urlsWithLocaleUrls[] = $this->getUrlArrayFromEntity(
                $urlEntity,
                $urls[$urlEntity->getResourceType()]->getData()
            );
        }

        return $urlsWithLocaleUrls;
    }

    /**
     * @param array $urlResources
     *
     * @return array
     */
    protected function getUrlsByResources(array $urlResources)
    {
        $urls = [];
        foreach ($urlResources as $resourceType => $resourceIds) {
            $urls[$resourceType] = $this->getQueryContainer()
                ->queryUrlsByResourceTypeAndIds($resourceType, $resourceIds)
                ->find();
        }

        return $urls;
    }

    /**
     * @param \Orm\Zed\Url\Persistence\SpyUrl $urlEntity
     * @param \Orm\Zed\Url\Persistence\SpyUrl[] $urlResources
     *
     * @return array
     */
    protected function getUrlArrayFromEntity(SpyUrl $urlEntity, array $urlResources)
    {
        $urlArray = $urlEntity->toArray();
        foreach ($urlResources as $urlResource) {
            if ($urlEntity->getResourceId() === $urlResource->getResourceId()) {
                $urlArray[UrlTransfer::LOCALE_URLS][] = $urlResource->toArray();
            }
        }

        return $urlArray;
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
