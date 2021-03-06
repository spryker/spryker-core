<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\TabsViewTransfer;

/**
 * Provides extension capabilities for the TabsViewTransfer expanding during build of the MerchantFormTabs.
 */
interface MerchantFormTabExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands tabs for Merchant form.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    public function expand(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer;
}
