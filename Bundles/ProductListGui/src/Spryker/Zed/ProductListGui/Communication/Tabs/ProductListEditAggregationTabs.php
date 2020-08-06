<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Tabs;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;

class ProductListEditAggregationTabs extends AbstractProductListAggregationTabs
{
    protected const USED_BY_TAB_NAME = 'used_by';
    protected const USED_BY_TAB_TITLE = 'Used By';
    protected const USED_BY_TAB_TEMPLATE = '@ProductListGui/_partials/_tabs/used-by.twig';

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    protected function build(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $tabsViewTransfer = parent::build($tabsViewTransfer);

        $this->addUsedByTab($tabsViewTransfer);

        return $tabsViewTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return $this
     */
    protected function addUsedByTab(TabsViewTransfer $tabsViewTransfer)
    {
        $tabItemTransfer = (new TabItemTransfer())
            ->setName(static::USED_BY_TAB_NAME)
            ->setTitle(static::USED_BY_TAB_TITLE)
            ->setTemplate(static::USED_BY_TAB_TEMPLATE);

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $this;
    }
}
