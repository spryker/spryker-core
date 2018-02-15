<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UrlStorage\Communication\Plugin\Event\Listener;

use ArrayObject;
use Generated\Shared\Transfer\UrlStorageTransfer;
use Orm\Zed\UrlStorage\Persistence\SpyUrlStorage;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Url\Persistence\Propel\AbstractSpyUrl;
use Spryker\Zed\UrlStorage\Communication\Plugin\Event\Exception\MissingResourceException;

/**
 * @method \Spryker\Zed\UrlStorage\Persistence\UrlStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\UrlStorage\Communication\UrlStorageCommunicationFactory getFactory()
 */
class AbstractUrlStorageListener extends AbstractPlugin
{
    const FK_URL = 'fkUrl';

    const RESOURCE_TYPE = 'type';
    const RESOURCE_VALUE = 'value';

    /**
     * @param array $urlIds
     *
     * @return void
     */
    protected function publish(array $urlIds)
    {
        $urls = $this->findUrls($urlIds);
        $urlStorageTransfers = $this->mapUrlsToUrlStorageTransfers($urls);

        $urlStorageEntities = $this->findUrlStorageEntitiesByIds($urlIds);

        $this->storeData($urlStorageTransfers, $urlStorageEntities);
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
     * @param \Generated\Shared\Transfer\UrlStorageTransfer[] $urlStorageTransfers
     * @param \Orm\Zed\UrlStorage\Persistence\SpyUrlStorage[] $urlStorageEntities
     *
     * @return void
     */
    protected function storeData(array $urlStorageTransfers, array $urlStorageEntities)
    {
        foreach ($urlStorageTransfers as $urlStorageTransfer) {
            $idUrl = $urlStorageTransfer->getIdUrl();
            if (isset($urlStorageEntities[$idUrl])) {
                if ($urlStorageEntities[$idUrl]->getUrl() === $urlStorageTransfer->getUrl()) {
                    $this->storeDataSet($urlStorageTransfer, $urlStorageEntities[$idUrl]);
                } else {
                    $this->storeDataSet($urlStorageTransfer);
                    $urlStorageEntities[$idUrl]->delete();
                }
            } else {
                $this->storeDataSet($urlStorageTransfer);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     * @param \Orm\Zed\UrlStorage\Persistence\SpyUrlStorage|null $urlStorageEntity
     *
     * @return void
     */
    protected function storeDataSet(UrlStorageTransfer $urlStorageTransfer, SpyUrlStorage $urlStorageEntity = null)
    {
        if ($urlStorageEntity === null) {
            $urlStorageEntity = new SpyUrlStorage();
        }

        $resource = $this->findResourceArguments($urlStorageTransfer->toArray());

        $urlStorageEntity->setByName('fk_' . $resource[static::RESOURCE_TYPE], $resource[static::RESOURCE_VALUE]);
        $urlStorageEntity->setUrl($urlStorageTransfer->getUrl());
        $urlStorageEntity->setFkUrl($urlStorageTransfer->getIdUrl());
        $urlStorageEntity->setData($urlStorageTransfer->modifiedToArray());
        $urlStorageEntity->setStore($this->getStoreName());
        $urlStorageEntity->save();
    }

    /**
     * @param array $data
     *
     * @throws \Spryker\Zed\UrlStorage\Communication\Plugin\Event\Exception\MissingResourceException
     *
     * @return array
     */
    protected function findResourceArguments(array $data)
    {
        foreach ($data as $columnName => $value) {
            if (!$this->isFkResourceUrl($columnName, $value)) {
                continue;
            }

            $type = str_replace(AbstractSpyUrl::RESOURCE_PREFIX, '', $columnName);

            return [
                static::RESOURCE_TYPE => $type,
                static::RESOURCE_VALUE => $value,
            ];
        }

        throw new MissingResourceException(
            sprintf(
                'Encountered a URL entity that is missing a resource: %s',
                json_encode($data)
            )
        );
    }

    /**
     * @param string $columnName
     * @param string $value
     *
     * @return bool
     */
    protected function isFkResourceUrl($columnName, $value)
    {
        return $value !== null && strpos($columnName, AbstractSpyUrl::RESOURCE_PREFIX) === 0;
    }

    /**
     * @param array $urls
     *
     * @return \Generated\Shared\Transfer\UrlStorageTransfer[]
     */
    protected function mapUrlsToUrlStorageTransfers(array $urls)
    {
        $localeUrls = $this->findLocaleUrls($urls);

        $urlStorageTransfers = [];
        foreach ($urls as $url) {
            $urlResource = $this->findResourceArguments($url);
            $urlStorageTransfer = (new UrlStorageTransfer())->fromArray($url, true);
            $urlStorageTransfer->setLocaleUrls(
                $this->getLocaleUrlsForUrl($localeUrls[$urlResource[static::RESOURCE_TYPE]], $urlResource)
            );

            $urlStorageTransfers[] = $urlStorageTransfer;
        }

        return $urlStorageTransfers;
    }

    /**
     * @param array $localeUrls
     * @param array $urlResourceArguments
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\UrlStorageTransfer[]
     */
    protected function getLocaleUrlsForUrl(array $localeUrls, array $urlResourceArguments)
    {
        $siblingUrls = new ArrayObject();
        foreach ($localeUrls as $localeUrl) {
            $resourceArguments = $this->findResourceArguments($localeUrl);
            if ($urlResourceArguments[static::RESOURCE_VALUE] === $resourceArguments[static::RESOURCE_VALUE]) {
                $siblingUrls[] = $localeUrl;
            }
        }

        return $siblingUrls;
    }

    /**
     * @param array $urls
     *
     * @return array
     */
    protected function findLocaleUrls(array $urls)
    {
        $localeUrls = [];
        foreach ($urls as $url) {
            $resourceArguments = $this->findResourceArguments($url);
            if (isset($localeUrls[$resourceArguments[static::RESOURCE_TYPE]])) {
                $localeUrls[$resourceArguments[static::RESOURCE_TYPE]][] = $resourceArguments[static::RESOURCE_VALUE];
                continue;
            }

            $localeUrls[$resourceArguments[static::RESOURCE_TYPE]] = [$resourceArguments[static::RESOURCE_VALUE]];
        }

        foreach ($localeUrls as $resourceType => $resourceIds) {
            $localeUrls[$resourceType] = $this->getQueryContainer()
                ->queryUrlsByResourceTypeAndIds($resourceType, $resourceIds)
                ->find()
                ->getData();
        }

        return $localeUrls;
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
     * @param array $urlIds
     *
     * @return array
     */
    protected function findUrls(array $urlIds)
    {
        return $this->getQueryContainer()->queryUrls($urlIds)->find()->getData();
    }

    /**
     * @return string
     */
    protected function getStoreName()
    {
        return $this->getFactory()->getStore()->getStoreName();
    }
}
