<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business\Model;

use Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface;

class CategoryImageSetCombiner implements CategoryImageSetCombinerInterface
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
     * @param int $idCategory
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    public function getCombinedCategoryImageSets(int $idCategory, int $idLocale): array
    {
        $categoryDefaultImageSets = $this->categoryImageRepository
            ->findDefaultCategoryImageSets($idCategory);

        $categoryLocalizedImageSets = $this->categoryImageRepository
            ->findLocalizedCategoryImageSets($idCategory, $idLocale);

        return $this->getImageSetsIndexedByName($categoryLocalizedImageSets)
            + $this->getImageSetsIndexedByName($categoryDefaultImageSets);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer[] $imageSetTransferCollection
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    protected function getImageSetsIndexedByName(array $imageSetTransferCollection)
    {
        $result = [];

        foreach ($imageSetTransferCollection as $imageSetTransfer) {
            $result[$imageSetTransfer->getName()] = $imageSetTransfer;
        }

        return $result;
    }
}
