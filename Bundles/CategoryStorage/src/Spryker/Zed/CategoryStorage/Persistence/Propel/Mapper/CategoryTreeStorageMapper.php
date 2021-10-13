<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CategoryTreeStorageTransfer;
use Orm\Zed\CategoryStorage\Persistence\Base\SpyCategoryTreeStorage;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\CategoryStorage\Dependency\Service\CategoryStorageToUtilSanitizeServiceInterface;

class CategoryTreeStorageMapper
{
    /**
     * @var string
     */
    public const KEY_CATEGORY_NODES_STORAGE = 'category_nodes_storage';

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
     * @param \Propel\Runtime\Collection\ObjectCollection $categoryTreeStorageEntities
     * @param array<\Generated\Shared\Transfer\CategoryTreeStorageTransfer> $categoryTreeStorageTransfers
     *
     * @return array<\Generated\Shared\Transfer\CategoryTreeStorageTransfer>
     */
    public function mapCategoryTreeStorageEntitiesToCategoryTreeStorageTransfers(
        ObjectCollection $categoryTreeStorageEntities,
        array $categoryTreeStorageTransfers
    ): array {
        foreach ($categoryTreeStorageEntities as $categoryTreeStorageEntity) {
            $categoryTreeStorageTransfers[] = $this->mapCategoryTreeStorageEntityToCategoryTreeStorageTransfer(
                $categoryTreeStorageEntity,
                new CategoryTreeStorageTransfer()
            );
        }

        return $categoryTreeStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTreeStorageTransfer $categoryTreeStorageTransfer
     * @param \Orm\Zed\CategoryStorage\Persistence\Base\SpyCategoryTreeStorage $categoryTreeStorageEntity
     *
     * @return \Orm\Zed\CategoryStorage\Persistence\Base\SpyCategoryTreeStorage
     */
    public function mapCategoryTreeStorageTransferToCategoryTreeStorageEntity(
        CategoryTreeStorageTransfer $categoryTreeStorageTransfer,
        SpyCategoryTreeStorage $categoryTreeStorageEntity
    ): SpyCategoryTreeStorage {
        $categoryTreeStorageEntity->fromArray($categoryTreeStorageTransfer->toArray());
        $categoryTreeStorageData = $this->utilSanitizeService->arrayFilterRecursive(
            array_intersect_key(
                $categoryTreeStorageTransfer->modifiedToArray(),
                [static::KEY_CATEGORY_NODES_STORAGE => []]
            )
        );
        $categoryTreeStorageEntity->setData($categoryTreeStorageData);

        return $categoryTreeStorageEntity;
    }

    /**
     * @param \Orm\Zed\CategoryStorage\Persistence\Base\SpyCategoryTreeStorage $categoryTreeStorageEntity
     * @param \Generated\Shared\Transfer\CategoryTreeStorageTransfer $categoryTreeStorageTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTreeStorageTransfer
     */
    protected function mapCategoryTreeStorageEntityToCategoryTreeStorageTransfer(
        SpyCategoryTreeStorage $categoryTreeStorageEntity,
        CategoryTreeStorageTransfer $categoryTreeStorageTransfer
    ): CategoryTreeStorageTransfer {
        $categoryTreeStorageTransfer->fromArray($categoryTreeStorageEntity->toArray(), true);
        $categoryTreeStorageTransfer->fromArray($categoryTreeStorageEntity->getData()[static::KEY_CATEGORY_NODES_STORAGE], true);

        return $categoryTreeStorageTransfer;
    }
}
