<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\PluginExecutor;

use Generated\Shared\Transfer\CategoryTransfer;

interface CategoryPluginExecutorInterface
{
    /**
     * Specification:
     * - execute category post update plugin stack
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function executePostUpdatePlugins(CategoryTransfer $categoryTransfer): void;

    /**
     * Specification:
     * - Execute category post create plugin stack.
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function executePostCreatePlugins(CategoryTransfer $categoryTransfer): void;

    /**
     * Specification:
     * - execute category post read plugin stack
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function executePostReadPlugins(CategoryTransfer $categoryTransfer): CategoryTransfer;
}
