<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Model;

use Generated\Shared\Transfer\CategoryTransfer;

interface CategoryPluginExecutorInterface
{
    /**
     * Specification:
     * - execute category post update plugin stack
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function executePostUpdatePlugins(CategoryTransfer $categoryTransfer): CategoryTransfer;

    /**
     * Specification:
     * - execute category post create plugin stack
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function executePostCreatePlugins(CategoryTransfer $categoryTransfer): CategoryTransfer;

    /**
     * Specification:
     * - execute category post read plugin stack
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function executePostReadPlugins(CategoryTransfer $categoryTransfer): CategoryTransfer;
}
