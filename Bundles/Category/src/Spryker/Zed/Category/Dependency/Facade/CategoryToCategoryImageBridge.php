<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Dependency\Facade;

class CategoryToCategoryImageBridge implements CategoryToCategoryImageInterface
{
    /**
     * @var \Spryker\Zed\CategoryImage\Business\CategoryImageFacadeInterface
     */
    protected $categoryImageFacade;

    /**
     * @param \Spryker\Zed\CategoryImage\Business\CategoryImageFacadeInterface $categoryImageFacade
     */
    public function __construct($categoryImageFacade)
    {
        $this->categoryImageFacade = $categoryImageFacade;
    }

    /**
     * @param int $idCategory
     *
     * @return array
     */
    public function getCategoryImagesSetCollectionByCategoryId(int $idCategory): array
    {
        return $this->categoryImageFacade->getCategoryImagesSetCollectionByCategoryId($idCategory);
    }
}
