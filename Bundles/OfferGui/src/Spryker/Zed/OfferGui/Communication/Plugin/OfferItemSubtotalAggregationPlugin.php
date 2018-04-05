<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Plugin;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Spryker\Zed\Calculation\Dependency\Plugin\CalculationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\OfferGui\Communication\OfferGuiCommunicationFactory getFactory()
 */
class OfferItemSubtotalAggregationPlugin extends AbstractPlugin implements CalculationPluginInterface
{
    //TODO: deal with guide
    //Guide: This calculator plugin must be placed under ItemSubtotalAggregatorPlugin
    //after the generic logic is executed
    /**
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
         $this->getFactory()->getOfferFacade()->aggregateOfferItemSubtotal($calculableObjectTransfer);
    }
}
