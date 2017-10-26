<?php


namespace Spryker\Zed\ProductCategoryFilterGui\Dependency\Facade;


use Generated\Shared\Transfer\LocaleTransfer;

interface ProductCategoryFilterGuiToCategoryInterface
{
    /**
     * @param int $idCategory
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    public function getTreeNodeChildrenByIdCategoryAndLocale($idCategory, LocaleTransfer $localeTransfer);

    /**
     * @param int $idCategory
     *
     * @throws \Spryker\Zed\Category\Business\Exception\MissingCategoryException
     * @throws \Spryker\Zed\Category\Business\Exception\MissingCategoryNodeException
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function read($idCategory);
}