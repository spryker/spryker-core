<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Business\Model;

use Generated\Shared\Category\CategoryInterface;
use Generated\Shared\Transfer\LocaleTransfer;

interface CategoryWriterInterface
{

    /**
     * @param CategoryInterface $category
     * @param LocaleTransfer $locale
     *
     * @return int
     */
    public function create(CategoryInterface $category, LocaleTransfer $locale);

    /**
     * @param CategoryInterface $category
     * @param LocaleTransfer $locale
     *
     * @return void
     */
    public function update(CategoryInterface $category, LocaleTransfer $locale);

    /**
     * @param int $idCategory
     *
     * @return void
     */
    public function delete($idCategory);

}
