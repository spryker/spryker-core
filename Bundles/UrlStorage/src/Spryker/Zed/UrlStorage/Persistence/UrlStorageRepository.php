<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UrlStorage\Persistence;

use Orm\Zed\UrlStorage\Persistence\Map\SpyUrlStorageTableMap;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\UrlStorage\Persistence\UrlStoragePersistenceFactory getFactory()
 */
class UrlStorageRepository extends AbstractRepository implements UrlStorageRepositoryInterface
{
    /**
     * @param array<int> $urlIds
     * @param array<string> $localeNames
     *
     * @return array<array<\Orm\Zed\Url\Persistence\SpyUrl>>
     */
    public function findLocalizedUrlsByUrlIds(array $urlIds, array $localeNames): array
    {
        $resourceTypeAndIds = $this->getResourceIdsGroupedByResourceTypeForUrlIds($urlIds);
        $localizedUrlEntities = [];
        foreach ($resourceTypeAndIds as $resourceType => $resourceIds) {
            $resourceTypeEntities = $this->findUrlsByResourceTypeAndIds($resourceType, $resourceIds, $localeNames);

            foreach ($resourceTypeEntities as $urlEntity) {
                $localizedUrlEntities[$urlEntity->getResourceType() . $urlEntity->getResourceId()][$urlEntity->getIdUrl()] = $urlEntity;
            }
        }

        return $localizedUrlEntities;
    }

    /**
     * @param array<string> $urls
     *
     * @return array<\Orm\Zed\Url\Persistence\SpyUrl>
     */
    public function findUrlEntitiesByUrls(array $urls): array
    {
        if (count($urls) === 0) {
            return [];
        }

        return $this->getFactory()
            ->getUrlQuery()
            ->filterByUrl_In($urls)
            ->find()
            ->getData();
    }

    /**
     * @param array<int> $urlIds
     *
     * @return array<\Orm\Zed\UrlStorage\Persistence\SpyUrlStorage>
     */
    public function findUrlStorageByUrlIds(array $urlIds): array
    {
        /** @var \Propel\Runtime\Collection\ObjectCollection $storageUrlCollection */
        $storageUrlCollection = $this->getFactory()
            ->createSpyStorageUrlQuery()
            ->filterByFkUrl_In($urlIds)
            ->find();

        return $storageUrlCollection->toKeyIndex(SpyUrlStorageTableMap::getTableMap()->getColumn(SpyUrlStorageTableMap::COL_FK_URL)->getPhpName());
    }

    /**
     * @param array<int> $urlIds
     *
     * @return array
     */
    protected function getResourceIdsGroupedByResourceTypeForUrlIds(array $urlIds): array
    {
        $urlEntities = $this->getFactory()
            ->getUrlQuery()
            ->filterByIdUrl_In($urlIds)
            ->find();

        $resources = [];
        foreach ($urlEntities as $urlEntity) {
            $resources[$urlEntity->getResourceType()][$urlEntity->getResourceId()] = $urlEntity->getResourceId();
        }

        return $resources;
    }

    /**
     * @param string $resourceType
     * @param array<int> $resourceIds
     * @param array<string> $localeNames
     *
     * @return array<\Orm\Zed\Url\Persistence\SpyUrl>
     */
    protected function findUrlsByResourceTypeAndIds(string $resourceType, array $resourceIds, array $localeNames): array
    {
        return $this->getFactory()->getUrlQuery()
            ->filterByResourceTypeAndIds($resourceType, $resourceIds)
            ->joinWithSpyLocale()
            ->useSpyLocaleQuery()
                ->filterByLocaleName_In($localeNames)
            ->endUse()
            ->find()
            ->getData();
    }
}
