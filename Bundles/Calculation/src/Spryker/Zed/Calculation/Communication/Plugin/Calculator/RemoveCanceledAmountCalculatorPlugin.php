<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Communication\Plugin\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Spryker\Zed\Calculation\Dependency\Plugin\CalculationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Calculation\Business\CalculationFacadeInterface getFacade()
 * @method \Spryker\Zed\Calculation\Communication\CalculationCommunicationFactory getFactory()
 * @method \Spryker\Zed\Calculation\CalculationConfig getConfig()
 */
class RemoveCanceledAmountCalculatorPlugin extends AbstractPlugin implements CalculationPluginInterface
{
    /**
     * @api
     *
     * @see \Spryker\Zed\Calculation\Business\CalculationFacadeInterface::removeCanceledAmount()
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->getFacade()->removeCanceledAmount($calculableObjectTransfer);
    }
}
