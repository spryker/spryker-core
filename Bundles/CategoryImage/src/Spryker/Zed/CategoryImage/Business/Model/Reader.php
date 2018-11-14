<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business\Model;

use Generated\Shared\Transfer\CategoryTransfer;
use Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface;

class Reader implements ReaderInterface
{
    /**
     * @var \Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface
     */
    protected $categoryImageRepository;

    /**
     * @var \Spryker\Zed\CategoryImage\Business\Model\ImageSetLocalizerInterface
     */
    private $imageSetLocalizer;

    /**
     * @param \Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface $categoryImageRepository
     * @param \Spryker\Zed\CategoryImage\Business\Model\ImageSetLocalizerInterface $imageSetLocalizer
     */
    public function __construct(
        CategoryImageRepositoryInterface $categoryImageRepository,
        ImageSetLocalizerInterface $imageSetLocalizer
    ) {
        $this->categoryImageRepository = $categoryImageRepository;
        $this->imageSetLocalizer = $imageSetLocalizer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function expandCategoryWithImageSets(CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        $categoryImageSetCollection = $this->findCategoryImagesSetCollectionByCategoryId(
            $categoryTransfer->requireIdCategory()->getIdCategory()
        );

        if ($categoryImageSetCollection) {
            $localizedCategoryImageSetCollection = $this->buildLocalizedImageSetCollection($categoryImageSetCollection);
            $categoryTransfer->setFormImageSets($localizedCategoryImageSetCollection);
        }

        return $categoryTransfer;
    }

    /**
     * @param int $idCategory
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    public function findCategoryImagesSetCollectionByCategoryId(int $idCategory): array
    {
        return $this->categoryImageRepository->findCategoryImageSetsByCategoryId($idCategory);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer[] $categoryImageSetCollection
     *
     * @return array
     */
    protected function buildLocalizedImageSetCollection(array $categoryImageSetCollection): array
    {
        return $this->imageSetLocalizer->buildFormImageSetCollection($categoryImageSetCollection);
    }
}
