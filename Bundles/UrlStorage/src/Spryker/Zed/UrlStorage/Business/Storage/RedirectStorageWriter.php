<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UrlStorage\Business\Storage;

use Orm\Zed\UrlStorage\Persistence\SpyUrlRedirectStorage;
use Spryker\Zed\UrlStorage\Dependency\Service\UrlStorageToUtilSanitizeServiceInterface;
use Spryker\Zed\UrlStorage\Persistence\UrlStorageQueryContainerInterface;

class RedirectStorageWriter implements RedirectStorageWriterInterface
{
    public const ID_URL_REDIRECT = 'id_url_redirect';
    public const FK_URL_REDIRECT = 'fkUrlRedirect';

    /**
     * @var \Spryker\Zed\UrlStorage\Dependency\Service\UrlStorageToUtilSanitizeServiceInterface
     */
    protected $utilSanitize;

    /**
     * @var \Spryker\Zed\UrlStorage\Persistence\UrlStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @deprecated Use `\Spryker\Zed\SynchronizationBehavior\SynchronizationBehaviorConfig::isSynchronizationEnabled()` instead.
     *
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
     * @param array $redirectIds
     *
     * @return void
     */
    public function publish(array $redirectIds)
    {
        $redirectEntities = $this->findRedirectEntities($redirectIds);
        $redirectStorageEntities = $this->findRedirectStorageEntitiesByIds($redirectIds);

        $this->storeData($redirectEntities, $redirectStorageEntities);
    }

    /**
     * @param array $redirectIds
     *
     * @return void
     */
    public function unpublish(array $redirectIds)
    {
        $redirectStorageEntities = $this->findRedirectStorageEntitiesByIds($redirectIds);
        foreach ($redirectStorageEntities as $redirectStorageEntity) {
            $redirectStorageEntity->delete();
        }
    }

    /**
     * @param array $spyRedirectEntities
     * @param array $spyRedirectStorageEntities
     *
     * @return void
     */
    protected function storeData(array $spyRedirectEntities, array $spyRedirectStorageEntities)
    {
        foreach ($spyRedirectEntities as $spyRedirectEntity) {
            $idUrl = $spyRedirectEntity[static::ID_URL_REDIRECT];
            if (isset($spyRedirectStorageEntities[$idUrl])) {
                $this->storeDataSet($spyRedirectEntity, $spyRedirectStorageEntities[$idUrl]);

                continue;
            }

            $this->storeDataSet($spyRedirectEntity);
        }
    }

    /**
     * @param array $spyRedirectEntity
     * @param \Orm\Zed\UrlStorage\Persistence\SpyUrlRedirectStorage|null $spyUrlRedirectStorage
     *
     * @return void
     */
    protected function storeDataSet(array $spyRedirectEntity, ?SpyUrlRedirectStorage $spyUrlRedirectStorage = null)
    {
        if ($spyUrlRedirectStorage === null) {
            $spyUrlRedirectStorage = new SpyUrlRedirectStorage();
        }

        $spyUrlRedirectStorage->setFkUrlRedirect($spyRedirectEntity[static::ID_URL_REDIRECT]);
        $spyUrlRedirectStorage->setData($this->utilSanitize->arrayFilterRecursive($spyRedirectEntity));
        $spyUrlRedirectStorage->setIsSendingToQueue($this->isSendingToQueue);
        $spyUrlRedirectStorage->save();
    }

    /**
     * @param array $redirectIds
     *
     * @return array
     */
    protected function findRedirectEntities(array $redirectIds)
    {
        return $this->queryContainer->queryRedirects($redirectIds)->find()->getData();
    }

    /**
     * @param array $redirectIds
     *
     * @return array
     */
    protected function findRedirectStorageEntitiesByIds(array $redirectIds)
    {
        return $this->queryContainer->queryRedirectStorageByIds($redirectIds)->find()->toKeyIndex(static::FK_URL_REDIRECT);
    }
}
