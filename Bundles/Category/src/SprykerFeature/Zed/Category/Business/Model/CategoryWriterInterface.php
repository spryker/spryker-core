<?php

namespace SprykerFeature\Zed\Category\Business\Model;

use SprykerEngine\Shared\Locale\Dto\LocaleDto;
use SprykerFeature\Shared\Category\Transfer\Category as CategoryTransfer;

interface CategoryWriterInterface
{
    /**
     * @param CategoryTransfer $category
     * @param LocaleDto $locale
     *
     * @return int
     */
    public function create(CategoryTransfer $category, LocaleDto $locale);

    /**
     * @param CategoryTransfer $category
     * @param LocaleDto $locale
     *
     * @return int
     */
    public function update(CategoryTransfer $category, LocaleDto $locale);

    /**
     * @param int $idCategory
     */
    public function delete($idCategory);
}
