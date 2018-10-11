<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business\Model;

use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\CategoryImage\Business\Transfer\CategoryImageTransferMapperInterface;
use Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface;

class CategoryImageSetCombiner implements CategoryImageSetCombinerInterface
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
     * @param \Spryker\Zed\CategoryImage\Business\Transfer\CategoryImageTransferMapperInterface $transferMapper
     */
    public function __construct(
        CategoryImageRepositoryInterface $categoryImageRepository,
        CategoryImageTransferMapperInterface $transferMapper
    ) {
        $this->categoryImageRepository = $categoryImageRepository;
        $this->transferMapper = $transferMapper;
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
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSet[] $imageSets
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    protected function getImageSetsIndexedByName(ObjectCollection $imageSets)
    {
        $result = [];

        foreach ($imageSets as $imageSetEntity) {
            $imageSetTransfer = $this->transferMapper->mapCategoryImageSet($imageSetEntity);
            $result[$imageSetEntity->getName()] = $imageSetTransfer;
        }

        return $result;
    }
}
