<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business\ImageSet;

use ArrayObject;
use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\CategoryImage\Persistence\CategoryImageEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class ImageSetCreator implements ImageSetCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\CategoryImage\Persistence\CategoryImageEntityManagerInterface
     */
    protected $categoryImageEntityManager;

    /**
     * @param \Spryker\Zed\CategoryImage\Persistence\CategoryImageEntityManagerInterface $categoryImageEntityManager
     */
    public function __construct(CategoryImageEntityManagerInterface $categoryImageEntityManager)
    {
        $this->categoryImageEntityManager = $categoryImageEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    public function createCategoryImageSetsForCategory(CategoryTransfer $categoryTransfer): ArrayObject
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($categoryTransfer) {
            return $this->executeCreateCategoryImageSetsForCategoryTransaction($categoryTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    protected function executeCreateCategoryImageSetsForCategoryTransaction(CategoryTransfer $categoryTransfer): ArrayObject
    {
        foreach ($categoryTransfer->getImageSets() as $categoryImageSetTransfer) {
            $categoryImageSetTransfer->setIdCategory(
                $categoryTransfer->requireIdCategory()->getIdCategory()
            );

            $this->categoryImageEntityManager->saveCategoryImageSet($categoryImageSetTransfer);
        }

        return $categoryTransfer->getImageSets();
    }
}
