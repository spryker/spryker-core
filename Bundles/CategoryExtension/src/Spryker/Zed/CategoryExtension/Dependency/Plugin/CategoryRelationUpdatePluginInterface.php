<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryExtension\Dependency\Plugin;

use Generated\Shared\Transfer\CategoryTransfer;

/**
 * Implement this plugin interface to update relations during creating/updating category.
 */
interface CategoryRelationUpdatePluginInterface
{
    /**
     * Specification:
     * - Update relations on category update.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function update(CategoryTransfer $categoryTransfer);
}
