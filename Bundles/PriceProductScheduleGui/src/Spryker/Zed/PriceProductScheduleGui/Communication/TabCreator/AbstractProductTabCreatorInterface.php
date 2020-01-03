<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Communication\TabCreator;

use Generated\Shared\Transfer\TabsViewTransfer;

interface AbstractProductTabCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\TabsViewTransfer $tabsViewTransfer
     *
     * @return \Generated\Shared\Transfer\TabsViewTransfer
     */
    public function createScheduledPriceTabForProductAbstract(TabsViewTransfer $tabsViewTransfer): TabsViewTransfer;
}
