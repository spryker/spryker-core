<?php

namespace SprykerFeature\Zed\Category\Business\Model;

use SprykerFeature\Shared\Category\Transfer\Category as CategoryTransfer;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategory;
use SprykerFeature\Zed\Category\Persistence\Propel\SpyCategoryAttribute;
use Propel\Runtime\Exception\PropelException;

interface CategoryWriterInterface
{
    /**
     * @param CategoryTransfer $category
     * @param string $idLocale
     *
     * @return int
     */
    public function create(CategoryTransfer $category, $idLocale);

    /**
     * @param CategoryTransfer $category
     * @param string $idLocale
     *
     * @return int
     */
    public function update(CategoryTransfer $category, $idLocale);

    /**
     * @param int $idCategory
     */
    public function delete($idCategory);
}
