<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Dependency\Facade;

class CategoryImageStorageToCategoryImageBridge implements CategoryImageStorageToCategoryImageInterface
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
     * @param int $idLocale
     *
     * @return \Generated\Shared\Transfer\CategoryImageSetTransfer[]
     */
    public function getCombinedAbstractImageSets($idCategory, $idLocale)
    {
        return $this->categoryImageFacade->getCombinedCategoryImageSets($idCategory, $idLocale);
    }
}
