<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CategoryTransfer;

interface CategoryCreateAfterPluginInterface
{
    /**
     * Specification:
     * - Execute actions after category is created.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function execute(CategoryTransfer $categoryTransfer): void;
}
