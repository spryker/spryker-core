<?php

namespace SprykerFeature\Zed\Category\Business\Manager;

use SprykerFeature\Shared\Category\Transfer\CategoryNode;

interface NodeUrlManagerInterface
{
    /**
     * @param CategoryNode $categoryNode
     * @param int $idLocale
     */
    public function createUrl(CategoryNode $categoryNode, $idLocale);
}
