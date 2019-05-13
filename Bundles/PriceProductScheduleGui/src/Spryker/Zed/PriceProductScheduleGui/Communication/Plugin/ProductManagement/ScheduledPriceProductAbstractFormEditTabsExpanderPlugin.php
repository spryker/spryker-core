<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\Plugin\ProductManagement;

use Generated\Shared\Transfer\TabsViewTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductManagementExtension\Dependency\Plugin\ProductAbstractEditFormTabsExpanderPluginInterface;

/**
 * @method \Spryker\Zed\PriceProductScheduleGui\Communication\PriceProductScheduleGuiCommunicationFactory getFactory()
 */
class ScheduledPriceProductAbstractFormEditTabsExpanderPlugin extends AbstractPlugin implements ProductAbstractEditFormTabsExpanderPluginInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    public function expand(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer
    {
        return $this->getFactory()
            ->createTabCreator()
            ->createScheduledPriceTab($tabsViewTransfer);
    }
}
