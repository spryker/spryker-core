<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Tree;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\NodeTransfer;

/**
 * @deprecated Will be removed with next major release
 */
interface CategoryTreeWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param bool $createUrlPath
     *
     * @return int
     */
    public function createCategoryNode(NodeTransfer $categoryNodeTransfer, LocaleTransfer $localeTransfer, $createUrlPath = true);

    /**
     * @param \Generated\Shared\Transfer\NodeTransfer $categoryNodeTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function updateNode(NodeTransfer $categoryNodeTransfer, LocaleTransfer $localeTransfer);

    /**
     * @param int $idNode
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param bool $deleteChildren
     *
     * @return int
     */
    public function deleteNode($idNode, LocaleTransfer $locale, $deleteChildren = false);

    /**
     * @return void
     */
    public function rebuildClosureTable();
}
