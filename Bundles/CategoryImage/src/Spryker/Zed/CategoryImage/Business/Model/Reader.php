<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\CategoryImage\Business\Transfer\CategoryImageTransferMapperInterface;
use Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface;

class Reader implements ReaderInterface
{
    /**
     * @var \Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface
     */
    protected $categoryImageRepository;

    /**
     * @var \Spryker\Zed\CategoryImage\Business\Transfer\CategoryImageTransferMapperInterface
     */
    protected $transferMapper;

    /**
     * @param \Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface $categoryImageRepository
     * @param \Spryker\Zed\CategoryImage\Business\Transfer\CategoryImageTransferMapperInterface $categoryImageTransferMapper
     */
    public function __construct(
        CategoryImageRepositoryInterface $categoryImageRepository,
        CategoryImageTransferMapperInterface $categoryImageTransferMapper
    ) {
        $this->categoryImageRepository = $categoryImageRepository;
        $this->transferMapper = $categoryImageTransferMapper;
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    public function findCategoryImagesSetCollectionByCategoryId(int $idCategory): array
    {
        $categoryImageSetCollection = $this->categoryImageRepository
            ->findCategoryImageSetsByCategoryId($idCategory);

        return $this->transferMapper->mapCategoryImageSetCollection($categoryImageSetCollection);
    }

    /**
     * @param int $idCategoryImageSet
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer|null
     */
    public function findCategoryImagesSetCollectionById(int $idCategoryImageSet): ?CategoryImageSetTransfer
    {
        $categoryImageSetEntity = $this->categoryImageRepository
            ->findImageSetById($idCategoryImageSet);

        if (!$categoryImageSetEntity) {
            return null;
        }

        return $this->transferMapper->mapCategoryImageSet($categoryImageSetEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function expandCategoryWithImageSets(CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        $imageSetCollection = $this->findCategoryImagesSetCollectionByCategoryId(
            $categoryTransfer->requireIdCategory()->getIdCategory()
        );

        if (!$imageSetCollection) {
            return $categoryTransfer;
        }

        $categoryTransfer->setImageSets(new ArrayObject($imageSetCollection));

        return $categoryTransfer;
    }
}
