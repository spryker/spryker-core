<?php

namespace SprykerFeature\Zed\Category\Business\Model;

use Generated\Shared\Transfer\CategoryCategoryTransfer;
use SprykerEngine\Shared\Locale\Dto\LocaleDto;

interface CategoryWriterInterface
{
    /**
     * @param CategoryCategoryTransfer $category
     * @param LocaleDto $locale
     *
     * @return int
     */
    public function create(CategoryCategoryTransfer $category, LocaleDto $locale);

    /**
     * @param CategoryCategoryTransfer $category
     * @param LocaleDto $locale
     *
     * @return int
     */
    public function update(CategoryCategoryTransfer $category, LocaleDto $locale);

    /**
     * @param int $idCategory
     */
    public function delete($idCategory);
}
