<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageGui\Communication\Plugin;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\CategoryExtension\Dependency\Plugin\CategoryFormTabExpanderPluginInterface;

class CategoryImageFormTabExpanderPlugin implements CategoryFormTabExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    public function expand(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $tabItemTransfer = (new TabItemTransfer())->setName('image')
            ->setTitle('Image')
            ->setTemplate('@CategoryImageGui/_partials/image-tab.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $tabsViewTransfer;
    }
}
