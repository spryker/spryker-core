<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Expander;

use Generated\Shared\Transfer\TabsViewTransfer;

interface ProductListCreateAggregationTabsExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    public function expandWithProductListAssignmentTabs(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer;
}
