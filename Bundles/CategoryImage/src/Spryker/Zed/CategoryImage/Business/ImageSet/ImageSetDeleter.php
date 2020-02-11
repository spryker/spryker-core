<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business\ImageSet;

use Spryker\Zed\CategoryImage\Persistence\CategoryImageEntityManagerInterface;
use Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class ImageSetDeleter implements ImageSetDeleterInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface
     */
    protected $categoryImageRepository;

    /**
     * @var \Spryker\Zed\CategoryImage\Persistence\CategoryImageEntityManagerInterface
     */
    protected $categoryImageEntityManager;

    /**
     * @param \Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface $categoryImageRepository
     * @param \Spryker\Zed\CategoryImage\Persistence\CategoryImageEntityManagerInterface $categoryImageEntityManager
     */
    public function __construct(
        CategoryImageRepositoryInterface $categoryImageRepository,
        CategoryImageEntityManagerInterface $categoryImageEntityManager
    ) {
        $this->categoryImageRepository = $categoryImageRepository;
        $this->categoryImageEntityManager = $categoryImageEntityManager;
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function deleteCategoryImageSetsByIdCategory(int $idCategory): void
    {
        $this->getTransactionHandler()->handleTransaction(function () use ($idCategory) {
            $this->executeDeleteCategoryImageSetsByIdCategoryTransaction($idCategory);
        });
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    protected function executeDeleteCategoryImageSetsByIdCategoryTransaction(int $idCategory): void
    {
        $categoryImageSets = $this->categoryImageRepository
            ->getCategoryImageSetsByIdCategory($idCategory);

        foreach ($categoryImageSets as $categoryImageSetTransfer) {
            $categoryImageSetTransfer->requireIdCategoryImageSet();
            $this->categoryImageEntityManager->deleteCategoryImageSetById(
                $categoryImageSetTransfer->getIdCategoryImageSet()
            );
        }
    }
}
