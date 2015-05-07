<?php

namespace SprykerFeature\Zed\Category\Business\Model;

use Generated\Shared\Transfer\CategoryCategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;

interface CategoryWriterInterface
{
    /**
     * @param CategoryCategoryTransfer $category
     * @param LocaleTransfer $locale
     *
     * @return int
     */
    public function create(CategoryCategoryTransfer $category, LocaleTransfer $locale);

    /**
     * @param CategoryCategoryTransfer $category
     * @param LocaleTransfer $locale
     *
     * @return int
     */
    public function update(CategoryCategoryTransfer $category, LocaleTransfer $locale);

    /**
     * @param int $idCategory
     */
    public function delete($idCategory);
}
