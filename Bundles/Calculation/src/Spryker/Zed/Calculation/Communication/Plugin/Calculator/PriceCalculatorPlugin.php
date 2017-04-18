<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Calculation\Communication\Plugin\Calculator;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;

/**
 * @method \Spryker\Zed\Calculation\Business\CalculationFacade getFacade()
 * @method \Spryker\Zed\Calculation\Communication\CalculationCommunicationFactory getFactory()
 */
class PriceCalculatorPlugin extends AbstractPlugin implements CalculatorPluginInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $this->getFacade()
            ->calculateItemPrice($quoteTransfer);
    }
}
