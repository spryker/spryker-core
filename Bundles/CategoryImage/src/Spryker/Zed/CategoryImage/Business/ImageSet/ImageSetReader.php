<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business\ImageSet;

use Spryker\Zed\CategoryImage\Persistence\CategoryImageRepositoryInterface;

class ImageSetReader implements ImageSetReaderInterface
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
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    public function getCategoryImageSetsByIdCategory(int $idCategory): array
    {
        return $this->categoryImageRepository->getCategoryImageSetsByIdCategory($idCategory);
    }
}
