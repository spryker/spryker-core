<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\SitemapUrlTransfer;
use Orm\Zed\CategoryStorage\Persistence\Base\SpyCategoryNodeStorage;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\CategoryStorage\Dependency\Service\CategoryStorageToUtilSanitizeServiceInterface;

class CategoryNodeStorageMapper
{
    /**
     * @var string
     */
    protected const COLUMN_URL = 'url';

    /**
     * @var string
     */
    protected const COLUMN_CHILDREN = 'children';

    /**
     * @var string
     */
    protected const COLUMN_NODE_ID = 'node_id';

    /**
     * @var string
     */
    protected const DATE_FORMAT = 'Y-m-d';

    /**
     * @var \Spryker\Zed\CategoryStorage\Dependency\Service\CategoryStorageToUtilSanitizeServiceInterface
     */
    protected $utilSanitizeService;

    /**
     * @param \Spryker\Zed\CategoryStorage\Dependency\Service\CategoryStorageToUtilSanitizeServiceInterface $utilSanitizeService
     */
    public function __construct(CategoryStorageToUtilSanitizeServiceInterface $utilSanitizeService)
    {
        $this->utilSanitizeService = $utilSanitizeService;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection<\Orm\Zed\CategoryStorage\Persistence\Base\SpyCategoryNodeStorage> $categoryNodeStorageEntities
     * @param array<\Generated\Shared\Transfer\CategoryNodeStorageTransfer> $categoryNodeStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\CategoryNodeStorageTransfer>
     */
    public function mapCategoryNodeStorageEntitiesToCategoryNodeStorageTransfers(
        ObjectCollection $categoryNodeStorageEntities,
        array $categoryNodeStorageTransfers
    ): array {
        foreach ($categoryNodeStorageEntities as $categoryNodeStorageEntity) {
            $categoryNodeStorageTransfers[] = $this->mapCategoryNodeStorageEntityToCategoryNodeStorageTransfer(
                $categoryNodeStorageEntity,
                new CategoryNodeStorageTransfer(),
            );
        }

        return $categoryNodeStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer $categoryNodeStorageTransfer
     * @param \Orm\Zed\CategoryStorage\Persistence\Base\SpyCategoryNodeStorage $categoryNodeStorageEntity
     *
     * @return \Orm\Zed\CategoryStorage\Persistence\Base\SpyCategoryNodeStorage
     */
    public function mapCategoryNodeStorageTransferToCategoryNodeStorageEntity(
        CategoryNodeStorageTransfer $categoryNodeStorageTransfer,
        SpyCategoryNodeStorage $categoryNodeStorageEntity
    ): SpyCategoryNodeStorage {
        $categoryNodeStorageEntityData = $this->utilSanitizeService->arrayFilterRecursive(
            $categoryNodeStorageTransfer->toArray(),
        );

        $categoryNodeStorageEntityData[CategoryNodeStorageTransfer::ORDER] = $categoryNodeStorageTransfer->getOrder();

        return $categoryNodeStorageEntity->setData($categoryNodeStorageEntityData);
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\CategoryStorage\Persistence\SpyCategoryNodeStorage> $categoryNodeStorageQueryEntities
     *
     * @return array<\Generated\Shared\Transfer\SitemapUrlTransfer>
     */
    public function mapCategoryNodeStorageEntitiesToSitemapUrlTransfers(Collection $categoryNodeStorageQueryEntities): array
    {
        $sitemapUrlTransfers = [];

        foreach ($categoryNodeStorageQueryEntities as $categoryNodeStorageEntity) {
            $categoryNodeStorageData = $categoryNodeStorageEntity->getData();

            if (!isset($categoryNodeStorageData[static::COLUMN_URL])) {
                continue;
            }

            $sitemapUrlTransfers[] = $this->mapCategoryNodeStorageEntityToSitemapUrlTransfer($categoryNodeStorageEntity, $categoryNodeStorageData);

            if (isset($categoryNodeStorageData[static::COLUMN_CHILDREN])) {
                $sitemapUrlTransfers = $this->mapChildCategoryNodeStorageEntityToSitemapUrlTransfer($categoryNodeStorageData[static::COLUMN_CHILDREN], $sitemapUrlTransfers, $categoryNodeStorageEntity);
            }
        }

        return $sitemapUrlTransfers;
    }

    /**
     * @param array $children
     * @param array $sitemapUrlTransfers
     * @param \Orm\Zed\CategoryStorage\Persistence\Base\SpyCategoryNodeStorage $parentCategoryNodeStorageEntity
     *
     * @return array<\Generated\Shared\Transfer\SitemapUrlTransfer>
     */
    protected function mapChildCategoryNodeStorageEntityToSitemapUrlTransfer(
        array $children,
        array $sitemapUrlTransfers,
        SpyCategoryNodeStorage $parentCategoryNodeStorageEntity
    ) {
        foreach ($children as $child) {
            if (!isset($child[static::COLUMN_URL])) {
                continue;
            }

            $sitemapUrlTransfers[] = $this->mapCategoryNodeStorageEntityToSitemapUrlTransfer($parentCategoryNodeStorageEntity, $child);

            if (!empty($child[static::COLUMN_CHILDREN])) {
                $sitemapUrlTransfers = $this->mapChildCategoryNodeStorageEntityToSitemapUrlTransfer($child[static::COLUMN_CHILDREN], $sitemapUrlTransfers, $parentCategoryNodeStorageEntity);
            }
        }

        return $sitemapUrlTransfers;
    }

    /**
     * @param \Orm\Zed\CategoryStorage\Persistence\Base\SpyCategoryNodeStorage $categoryNodeStorageEntity
     * @param array $categoryNodeStorageData
     *
     * @return \Generated\Shared\Transfer\SitemapUrlTransfer
     */
    protected function mapCategoryNodeStorageEntityToSitemapUrlTransfer(
        SpyCategoryNodeStorage $categoryNodeStorageEntity,
        array $categoryNodeStorageData
    ): SitemapUrlTransfer {
        $sitemapUrlTransfer = (new SitemapUrlTransfer())
            ->setUrl($categoryNodeStorageData[static::COLUMN_URL])
            ->setUpdatedAt($categoryNodeStorageEntity->getUpdatedAt(static::DATE_FORMAT))
            ->setLanguageCode($categoryNodeStorageEntity->getLocale())
            ->setStoreName($categoryNodeStorageEntity->getStore())
            ->setIdEntity($categoryNodeStorageData[static::COLUMN_NODE_ID]);

        return $sitemapUrlTransfer;
    }

    /**
     * @param \Orm\Zed\CategoryStorage\Persistence\Base\SpyCategoryNodeStorage $categoryNodeStorageEntity
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer $categoryNodeStorageTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer
     */
    protected function mapCategoryNodeStorageEntityToCategoryNodeStorageTransfer(
        SpyCategoryNodeStorage $categoryNodeStorageEntity,
        CategoryNodeStorageTransfer $categoryNodeStorageTransfer
    ): CategoryNodeStorageTransfer {
        return $categoryNodeStorageTransfer->fromArray($categoryNodeStorageEntity->getData(), true);
    }
}
