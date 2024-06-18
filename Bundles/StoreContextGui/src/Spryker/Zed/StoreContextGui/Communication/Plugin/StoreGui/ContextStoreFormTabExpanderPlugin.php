<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreContextGui\Communication\Plugin\StoreGui;

use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\StoreGuiExtension\Dependency\Plugin\StoreFormTabExpanderPluginInterface;

/**
 * @method \Spryker\Zed\StoreContextGui\StoreContextGuiConfig getConfig()
 * @method \Spryker\Zed\StoreContextGui\Communication\StoreContextGuiCommunicationFactory getFactory()
 */
class ContextStoreFormTabExpanderPlugin extends AbstractPlugin implements StoreFormTabExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands tabs view with store context tab.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    public function expand(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        return $this->getFactory()->createStoreContextTabExpander()->expandWithContextTab($tabsViewTransfer);
    }
}
