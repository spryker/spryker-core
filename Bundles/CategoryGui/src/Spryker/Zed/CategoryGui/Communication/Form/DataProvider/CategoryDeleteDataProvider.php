<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface;

class CategoryDeleteDataProvider
{
    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface
     */
    protected $categoryFacade;

    /**
     * @param \Spryker\Zed\CategoryGui\Dependency\Facade\CategoryGuiToCategoryFacadeInterface $categoryFacade
     */
    public function __construct(CategoryGuiToCategoryFacadeInterface $categoryFacade)
    {
        $this->categoryFacade = $categoryFacade;
    }

    /**
     * @param int $idCategory
     *
     * @return array
     */
    public function getData(int $idCategory): array
    {
        $categoryCriteriaTransfer = (new CategoryCriteriaTransfer())
            ->setIdCategory($idCategory)
            ->setIsMain(true);

        $categoryTransfer = $this->categoryFacade->findCategory($categoryCriteriaTransfer);

        return [
            'id_category_node' => $categoryTransfer->getCategoryNode()->getIdCategoryNode(),
            'fk_category' => $categoryTransfer->getIdCategory(),
        ];
    }
}
