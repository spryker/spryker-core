<?php

namespace SprykerFeature\Zed\Category\Business\Manager;

use Generated\Shared\Transfer\CategoryCategoryNodeTransfer;
use Generated\Shared\Transfer\LocaleTransfer;

interface NodeUrlManagerInterface
{
    /**
     * @param CategoryCategoryNodeTransfer $categoryNode
     * @param LocaleTransfer $locale
     */
    public function createUrl(CategoryCategoryNodeTransfer $categoryNode, LocaleTransfer $locale);
}
