<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturn\Communication\Plugin;

use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Zed\SalesReturnExtension\Dependency\Plugin\ReturnPreCreatePluginInterface;

class MerchantReturnPreCreatePlugin implements ReturnPreCreatePluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnTransfer
     */
    public function preCreate(ReturnTransfer $returnTransfer): ReturnTransfer
    {
        // TODO: Implement preCreate() method.
    }
}
