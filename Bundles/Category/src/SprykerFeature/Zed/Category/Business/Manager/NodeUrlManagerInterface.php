<?php

namespace SprykerFeature\Zed\Category\Business\Manager;

use SprykerEngine\Shared\Locale\Dto\LocaleDto;
use Generated\Shared\Transfer\CategoryCategoryNodeTransfer;

interface NodeUrlManagerInterface
{
    /**
     * @param CategoryCategoryNodeTransfer $categoryNode
     * @param LocaleDto $locale
     */
    public function createUrl(CategoryCategoryNodeTransfer $categoryNode, LocaleDto $locale);
}
