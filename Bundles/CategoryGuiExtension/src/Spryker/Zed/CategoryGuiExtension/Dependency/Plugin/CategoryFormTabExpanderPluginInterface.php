<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\TabsViewTransfer;

/**
 * Implement this plugin interface for category form tab expansion.
 */
interface CategoryFormTabExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands form tabs for CategoryType.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    public function expand(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer;
}
