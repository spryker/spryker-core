<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturn\Communication\Plugin;

use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesReturnExtension\Dependency\Plugin\ReturnCreateRequestValidatorPluginInterface;

/**
 * @method \Spryker\Zed\MerchantSalesReturn\MerchantSalesReturnConfig getConfig()
 * @method \Spryker\Zed\MerchantSalesReturn\Business\MerchantSalesReturnFacade getFacade()
 * @method \Spryker\Zed\MerchantSalesReturn\Communication\MerchantSalesReturnCommunicationFactory getFactory()
 */
class MerchantReturnCreateRequestValidatorPlugin extends AbstractPlugin implements ReturnCreateRequestValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     * - Checks if each item in the `$itemTransfers` has the same merchant reference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function validate(ReturnCreateRequestTransfer $returnCreateRequestTransfer): ReturnResponseTransfer
    {
        return $this->getFacade()
            ->validateReturn($returnCreateRequestTransfer);
    }
}
