<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business\Model\ImageSet;

use ArrayObject;
use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface;

class Reader implements ReaderInterface
{
    /**
     * @var \Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface
     */
    protected $categoryImageRepository;

    /**
     * @param \Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface $categoryImageRepository
     */
    public function __construct(CategoryImageRepositoryInterface $categoryImageRepository)
    {
        $this->categoryImageRepository = $categoryImageRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function expandCategoryWithImageSetCollection(CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        $categoryImageSetCollection = $this->findCategoryImagesSetCollectionByIdCategory(
            $categoryTransfer->requireIdCategory()->getIdCategory()
        );
        if ($categoryImageSetCollection) {
            $categoryTransfer->setImageSets(
                new ArrayObject($categoryImageSetCollection)
            );
        }

        return $categoryTransfer;
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    public function findCategoryImagesSetCollectionByIdCategory(int $idCategory): array
    {
        return $this->categoryImageRepository->findCategoryImageSetsByCategoryId($idCategory);
    }
}
