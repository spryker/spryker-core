<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageGui\Communication\Expander;

use Generated\Shared\Transfer\TabItemTransfer;
use Generated\Shared\Transfer\TabsViewTransfer;

class CategoryImageTabExpander implements CategoryImageTabExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    public function expandWithImageTab(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        $tabItemTransfer = (new TabItemTransfer())->setName('image')
            ->setTitle('Image')
            ->setTemplate('@CategoryImageGui/_partials/image-tab.twig');

        $tabsViewTransfer->addTab($tabItemTransfer);

        return $tabsViewTransfer;
    }
}
