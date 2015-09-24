<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Category\Business\Manager;

use Generated\Shared\Transfer\NodeTransfer;
use Generated\Shared\Transfer\LocaleTransfer;

interface NodeUrlManagerInterface
{

    /**
     * @param NodeTransfer $categoryNode
     * @param LocaleTransfer $locale
     */
    public function createUrl(NodeTransfer $categoryNode, LocaleTransfer $locale);

    /**
     * @param NodeTransfer   $categoryNode
     * @param LocaleTransfer $locale
     */
    public function updateUrl(NodeTransfer $categoryNode, LocaleTransfer $locale);

    /**
     * @param NodeTransfer $categoryNode
     * @param LocaleTransfer $locale
     */
    public function removeUrl(NodeTransfer $categoryNode, LocaleTransfer $locale);

}
