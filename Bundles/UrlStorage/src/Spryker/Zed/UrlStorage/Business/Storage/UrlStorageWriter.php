<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UrlStorage\Business\Storage;

use Orm\Zed\UrlStorage\Persistence\SpyUrlStorage;
use Spryker\Zed\UrlStorage\Dependency\Service\UrlStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\UrlStorage\Persistence\UrlStorageQueryContainerInterface;

class UrlStorageWriter implements UrlStorageWriterInterface
{
    const ID_URL = 'id_url';
    const FK_URL = 'fkUrl';

    /**
     * @var \Spryker\Zed\UrlStorage\Dependency\Service\UrlStorageToUtilSanitizeServiceInterface
     */
    protected $utilSanitize;

    /**
     * @var \Spryker\Zed\UrlStorage\Persistence\UrlStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @param \Spryker\Zed\UrlStorage\Dependency\Service\UrlStorageToUtilSanitizeServiceInterface $utilSanitize
     * @param \Spryker\Zed\UrlStorage\Persistence\UrlStorageQueryContainerInterface $queryContainer
     * @param bool $isSendingToQueue
     */
    public function __construct(UrlStorageToUtilSanitizeServiceInterface $utilSanitize, UrlStorageQueryContainerInterface $queryContainer, $isSendingToQueue)
    {
        $this->utilSanitize = $utilSanitize;
        $this->queryContainer = $queryContainer;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @param array $urlIds
     *
     * @return void
     */
    public function publish(array $urlIds)
    {
        $spyUrlEntities = $this->findUrlEntities($urlIds);
        $spyUrlStorageEntities = $this->findUrlStorageEntitiesByIds($urlIds);
        $this->storeData($spyUrlEntities, $spyUrlStorageEntities);
    }

    /**
     * @param array $urlIds
     *
     * @return void
     */
    public function unpublish(array $urlIds)
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

                    continue;
                }

                $this->storeDataSet($spyUrlEntity);
                $spyUrlStorageEntities[$idUrl]->delete();

                continue;
            }

            $this->storeDataSet($spyUrlEntity);
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
        $spyUrlStorageEntity->setData($this->utilSanitize->arrayFilterRecursive($spyUrlEntity));
        $spyUrlStorageEntity->setIsSendingToQueue($this->isSendingToQueue);
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
        return $this->queryContainer->queryUrls($urlIds)->find()->getData();
    }

    /**
     * @param array $urlIds
     *
     * @return array
     */
    protected function findUrlStorageEntitiesByIds(array $urlIds)
    {
        return $this->queryContainer->queryUrlStorageByIds($urlIds)->find()->toKeyIndex(static::FK_URL);
    }
}
