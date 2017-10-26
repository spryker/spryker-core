<?php

namespace Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

class ProductCategoryFilterGuiToCategoryBridge implements ProductCategoryFilterGuiToCategoryInterface
{
    /**
     * @var \Spryker\Zed\Category\Business\CategoryFacade
     */
    protected $categoryFacade;

    /**
     * @param \Spryker\Zed\Category\Business\CategoryFacadeInterface $categoryFacade
     */
    public function __construct($categoryFacade)
    {
        $this->categoryFacade = $categoryFacade;
    }

    /**
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function getTreeNodeChildrenByIdCategoryAndLocale($idCategory, LocaleTransfer $localeTransfer)
    {
        return $this->categoryFacade->getTreeNodeChildrenByIdCategoryAndLocale($idCategory, $localeTransfer);
    }

    /**
     * @param int $idCategory
     *
     * @throws \Spryker\Zed\Category\Business\Exception\MissingCategoryException
     * @throws \Spryker\Zed\Category\Business\Exception\MissingCategoryNodeException
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function read($idCategory) {
        return $this->categoryFacade->read($idCategory);
    }
}
