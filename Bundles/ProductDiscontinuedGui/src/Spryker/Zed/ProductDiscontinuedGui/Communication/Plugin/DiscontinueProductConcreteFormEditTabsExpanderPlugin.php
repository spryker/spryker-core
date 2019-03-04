<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductDiscontinuedGui\Communication\Plugin;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductConcreteFormEditTabsExpanderPluginInterface;

class DiscontinueProductConcreteFormEditTabsExpanderPlugin implements ProductConcreteFormEditTabsExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands form tabs for ProductConcreteEditForm
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    public function expand(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $tabItemTransfer = (new TabItemTransfer())
            ->setName('discontinue')
            ->setTitle('Discontinue')
            ->setTemplate('@ProductDiscontinuedGui/Tab/product-discontinue/product-discontinue-tab.twig');

        return $tabsViewTransfer->addTab($tabItemTransfer);
    }
}
