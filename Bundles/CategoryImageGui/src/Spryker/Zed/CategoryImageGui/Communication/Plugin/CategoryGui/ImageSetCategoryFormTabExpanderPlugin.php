<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageGui\Communication\Plugin\CategoryGui;

use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\CategoryGuiExtension\Dependency\Plugin\CategoryFormTabExpanderPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\CategoryImageGui\CategoryImageGuiConfig getConfig()
 * @method \Spryker\Zed\CategoryImageGui\Communication\CategoryImageGuiCommunicationFactory getFactory()
 */
class ImageSetCategoryFormTabExpanderPlugin extends AbstractPlugin implements CategoryFormTabExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds image tab to CategoryFormTabs.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    public function expand(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        return $this->getFactory()
            ->createCategoryImageTabExpander()
            ->expandWithImageTab($tabsViewTransfer);
    }
}
