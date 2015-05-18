<?php

namespace SprykerFeature\Zed\Category\Business\Model;

use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocaleTransfer;

interface CategoryWriterInterface
{
    /**
     * @param CategoryTransfer $category
     * @param LocaleTransfer $locale
     *
     * @return int
     */
    public function create(CategoryTransfer $category, LocaleTransfer $locale);

    /**
     * @param CategoryTransfer $category
     * @param LocaleTransfer $locale
     *
     * @return int
     */
    public function update(CategoryTransfer $category, LocaleTransfer $locale);

    /**
     * @param int $idCategory
     */
    public function delete($idCategory);
}
