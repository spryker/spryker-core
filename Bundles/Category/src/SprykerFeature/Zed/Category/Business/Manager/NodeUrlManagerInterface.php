<?php

namespace SprykerFeature\Zed\Category\Business\Manager;

use SprykerEngine\Shared\Dto\LocaleDto;
use SprykerFeature\Shared\Category\Transfer\CategoryNode;

interface NodeUrlManagerInterface
{
    /**
     * @param CategoryNode $categoryNode
     * @param LocaleDto $locale
     */
    public function createUrl(CategoryNode $categoryNode, LocaleDto $locale);
}
