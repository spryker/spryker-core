<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContextGui\Communication\Expander;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;

class StoreContextTabExpander implements StoreContextTabExpanderInterface
{
    /**
     * @var string
     */
    protected const STORE_CONTEXT_TAB_NAME = 'store_context';

    /**
     * @var string
     */
    protected const STORE_CONTEXT_TAB_TITLE = 'Context';

    /**
     * @var string
     */
    protected const STORE_CONTEXT_TAB_TEMPLATE = '@StoreContextGui/_partials/store-context-tab.twig';

    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    public function expandWithContextTab(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $tabItemTransfer = (new TabItemTransfer())
            ->setName(static::STORE_CONTEXT_TAB_NAME)
            ->setTitle(static::STORE_CONTEXT_TAB_TITLE)
            ->setTemplate(static::STORE_CONTEXT_TAB_TEMPLATE);

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $tabsViewTransfer;
    }
}
