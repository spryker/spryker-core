<?php

namespace SprykerFeature\Zed\Category\Business\Manager;

use SprykerEngine\Shared\Locale\Dto\LocaleDto;
use Generated\Shared\Transfer\CategoryCategoryNodeTransfer;

interface NodeUrlManagerInterface
{
    /**
     * @param CategoryNode $categoryNode
     * @param LocaleDto $locale
     */
    public function createUrl(CategoryNode $categoryNode, LocaleDto $locale);
}
