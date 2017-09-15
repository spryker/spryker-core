<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;

class ProductFormAddTabs extends AbstractProductFormTabs
{

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function build(TabsViewTransfer $tabsViewTransfer)
    {
        $this
            ->addGeneralTab($tabsViewTransfer)
            ->addPriceAndTaxTab($tabsViewTransfer)
            ->addVariantsTab($tabsViewTransfer)
            ->addSeoTab($tabsViewTransfer)
            ->addImageTab($tabsViewTransfer)
            ->setFooter($tabsViewTransfer);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addVariantsTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = new TabItemTransfer();
        $tabItemTransfer
            ->setName('variants')
            ->setTitle('Variants')
            ->setTemplate('@ProductManagement/Product/_partials/variant-tab-adding.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }

}
