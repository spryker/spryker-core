<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\TabsViewTransfer;

/**
 * Expands StoreForm with tabs that are needed for the form render.
 *
 * Use this plugin if additional tabs must be present in the StoreForm form view.
 */
interface StoreFormTabExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands tabs for Store form.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    public function expand(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer;
}
