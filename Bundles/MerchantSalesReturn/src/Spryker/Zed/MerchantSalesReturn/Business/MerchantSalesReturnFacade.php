<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturn\Business;

use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\MerchantSalesReturn\Business\MerchantSalesReturnBusinessFactory getFactory()
 */
class MerchantSalesReturnFacade extends AbstractFacade implements MerchantSalesReturnFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnTransfer
     */
    public function prepareReturn(ReturnTransfer $returnTransfer): ReturnTransfer
    {
        return $this->getFactory()
            ->createMerchantReturnPreparer()
            ->prepareReturn($returnTransfer);
    }
}
