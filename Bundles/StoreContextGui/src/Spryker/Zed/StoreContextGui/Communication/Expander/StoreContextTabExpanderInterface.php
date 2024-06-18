<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContextGui\Communication\Expander;

use Generated\Shared\Transfer\TabsViewTransfer;

interface StoreContextTabExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    public function expandWithContextTab(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer;
}
