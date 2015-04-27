<?php

namespace SprykerFeature\Zed\Category\Business\Model;

use SprykerEngine\Shared\Dto\LocaleDto;
use SprykerFeature\Shared\Category\Transfer\Category as CategoryTransfer;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategory;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryAttribute;
use Propel\Runtime\Exception\PropelException;

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
