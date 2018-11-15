<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CategoryTransfer;

interface CategoryTransferExpanderPluginInterface
{
    /**
     * Specification:
     * - Expand CategoryTransfer with additional subtransfers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function expandCategory(CategoryTransfer $categoryTransfer): CategoryTransfer;
}
