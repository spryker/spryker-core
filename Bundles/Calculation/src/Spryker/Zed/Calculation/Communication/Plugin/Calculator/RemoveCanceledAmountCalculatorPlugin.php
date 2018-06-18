<?php
/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\Calculation\Communication\Plugin\Calculator;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Spryker\Zed\Calculation\Dependency\Plugin\CalculationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Calculation\Business\CalculationFacadeInterface getFacade()
 * @method \Spryker\Zed\Calculation\Communication\CalculationCommunicationFactory getFactory()
 */
class RemoveCanceledAmountCalculatorPlugin extends AbstractPlugin implements CalculationPluginInterface
{
    /**
     * @api
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
