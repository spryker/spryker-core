<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesMerchantCommission\Communication\Plugin\Calculation;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Spryker\Zed\CalculationExtension\Dependency\Plugin\CalculationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\SalesMerchantCommission\Communication\SalesMerchantCommissionCommunicationFactory getFactory()
 * @method \Spryker\Zed\SalesMerchantCommission\Business\SalesMerchantCommissionFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesMerchantCommission\SalesMerchantCommissionConfig getConfig()
 */
class MerchantCommissionCalculatorPlugin extends AbstractPlugin implements CalculationPluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `CalculableObjectTransfer.totals` to be set.
     * - Expects `CalculableObjectTransfer.originalOrder.idSalesOrder` to be provided.
     * - Expects `CalculableObjectTransfer.items` to be provided.
     * - Retrieves sales merchant commissions for the order from Persistence.
     * - Updates `CalculableObjectTransfer` with merchant commissions for items and totals.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer): void
    {
        $this->getFacade()->recalculateMerchantCommissions($calculableObjectTransfer);
    }
}
