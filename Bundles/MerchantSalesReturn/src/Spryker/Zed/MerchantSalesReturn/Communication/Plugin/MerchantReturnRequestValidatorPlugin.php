<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSalesReturn\Communication\Plugin;

use ArrayObject;
use Generated\Shared\Transfer\ReturnCreateRequestTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesReturnExtension\Dependency\Plugin\ReturnRequestValidatorPluginInterface;

/**
 * @method \Spryker\Zed\MerchantSalesReturn\Persistence\MerchantSalesReturnQueryContainer getQueryContainer()
 * @method \Spryker\Zed\MerchantSalesReturn\MerchantSalesReturnConfig getConfig()
 * @method \Spryker\Zed\MerchantSalesReturn\Business\MerchantSalesReturnFacade getFacade()
 * @method \Spryker\Zed\MerchantSalesReturn\Communication\MerchantSalesReturnCommunicationFactory getFactory()
 */
class MerchantReturnRequestValidatorPlugin extends AbstractPlugin implements ReturnRequestValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ReturnCreateRequestTransfer $returnCreateRequestTransfer
     * @param \ArrayObject $itemTransfers
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function validate(
        ReturnCreateRequestTransfer $returnCreateRequestTransfer,
        ArrayObject $itemTransfers
    ): ReturnResponseTransfer {
        return $this->getFacade()
            ->validateReturn($returnCreateRequestTransfer, $itemTransfers);
    }
}
