<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContextGui\Communication\Plugin\StoreGui;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StoreGuiExtension\Dependency\Plugin\StoreFormTabExpanderPluginInterface;

/**
 * @method \Spryker\Zed\StoreContextGui\StoreContextGuiConfig getConfig()
 * @method \Spryker\Zed\StoreContextGui\Communication\StoreContextGuiCommunicationFactory getFactory()
 */
class StoreContextStoreFormTabExpanderPlugin extends AbstractPlugin implements StoreFormTabExpanderPluginInterface
{
    /**
     * @var string
     */
    protected const STORE_CONTEXT_TAB_NAME = 'store_context';

    /**
     * @var string
     */
    protected const STORE_CONTEXT_TAB_TITLE = 'Store Context';

    /**
     * @var string
     */
    protected const STORE_CONTEXT_TAB_TEMPLATE = '@StoreContextGui/_partials/_tabs/store-context.twig';

    /**
     * {@inheritDoc}
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
            ->setName(static::STORE_CONTEXT_TAB_NAME)
            ->setTitle(static::STORE_CONTEXT_TAB_TITLE)
            ->setTemplate(static::STORE_CONTEXT_TAB_TEMPLATE);

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $tabsViewTransfer;
    }
}
