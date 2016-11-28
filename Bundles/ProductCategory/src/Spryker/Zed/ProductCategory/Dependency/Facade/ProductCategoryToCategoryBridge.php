<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategory\Dependency\Facade;

use Spryker\Zed\Category\Business\CategoryFacadeInterface;

class ProductCategoryToCategoryBridge implements ProductCategoryToCategoryInterface
{

    /**
     * @var \Spryker\Zed\Category\Business\CategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @param \Spryker\Zed\Category\Business\CategoryFacadeInterface $categoryFacade
     */
    public function __construct(CategoryFacadeInterface $categoryFacade)
    {
        $this->categoryFacade = $categoryFacade;
    }

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function touchCategoryActive($idCategory)
    {
        $this->categoryFacade->touchCategoryActive($idCategory);
    }

}
