<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UrlStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\UrlStorage\Persistence\SpyUrlRedirectStorage;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\UrlStorage\Persistence\UrlStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\UrlStorage\Communication\UrlStorageCommunicationFactory getFactory()
 */
class AbstractRedirectStorageListener extends AbstractPlugin
{
    const ID_URL_REDIRECT = 'id_url_redirect';
    const FK_URL_REDIRECT = 'fkUrlRedirect';

    /**
     * @param array $redirectIds
     *
     * @return void
     */
    protected function publish(array $redirectIds)
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
    protected function unpublish(array $redirectIds)
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
            } else {
                $this->storeDataSet($spyRedirectEntity);
            }
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
        $spyUrlRedirectStorage->setData($this->getFactory()->getUtilSanitizeService()->arrayFilterRecursive($spyRedirectEntity));
        $spyUrlRedirectStorage->setStore($this->getStoreName());
        $spyUrlRedirectStorage->save();
    }

    /**
     * @param array $redirectIds
     *
     * @return array
     */
    protected function findRedirectEntities(array $redirectIds)
    {
        return $this->getQueryContainer()->queryRedirects($redirectIds)->find()->getData();
    }

    /**
     * @param array $redirectIds
     *
     * @return array
     */
    protected function findRedirectStorageEntitiesByIds(array $redirectIds)
    {
        return $this->getQueryContainer()->queryRedirectStorageByIds($redirectIds)->find()->toKeyIndex(static::FK_URL_REDIRECT);
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return $this->getFactory()->getStore()->getStoreName();
    }
}
