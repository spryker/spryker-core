<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Orm\Zed\CategoryStorage\Persistence\Base\SpyCategoryNodeStorage;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\CategoryStorage\Dependency\Service\CategoryStorageToUtilSanitizeServiceInterface;

class CategoryNodeStorageMapper
{
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
     * @param \Orm\Zed\CategoryStorage\Persistence\Base\SpyCategoryNodeStorage[]|\Propel\Runtime\Collection\ObjectCollection $categoryNodeStorageEntities
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer[] $categoryNodeStorageTransfers
     *
     * @return \Generated\Shared\Transfer\CategoryNodeStorageTransfer[]
     */
    public function mapCategoryNodeStorageEntitiesToCategoryNodeStorageTransfers(
        ObjectCollection $categoryNodeStorageEntities,
        array $categoryNodeStorageTransfers
    ): array {
        foreach ($categoryNodeStorageEntities as $categoryNodeStorageEntity) {
            $categoryNodeStorageTransfers[] = $this->mapCategoryNodeStorageEntityToCategoryNodeStorageTransfer(
                $categoryNodeStorageEntity,
                new CategoryNodeStorageTransfer()
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
        return $categoryNodeStorageEntity->setData(
            $this->utilSanitizeService->arrayFilterRecursive($categoryNodeStorageTransfer->toArray())
        );
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
