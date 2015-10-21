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
     * @param NodeTransfer $categoryNodeTransfer
     * @param LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function createUrl(NodeTransfer $categoryNodeTransfer, LocaleTransfer $localeTransfer);

    /**
     * @param NodeTransfer $categoryNodeTransfer
     * @param LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function updateUrl(NodeTransfer $categoryNodeTransfer, LocaleTransfer $localeTransfer);

    /**
     * @param NodeTransfer $categoryNodeTransfer
     * @param LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function removeUrl(NodeTransfer $categoryNodeTransfer, LocaleTransfer $localeTransfer);

}
