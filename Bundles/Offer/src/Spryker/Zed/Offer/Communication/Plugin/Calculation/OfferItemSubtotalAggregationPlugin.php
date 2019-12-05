<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Communication\Plugin\Calculation;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Spryker\Zed\Calculation\Dependency\Plugin\CalculationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Offer\Business\OfferFacadeInterface getFacade()
 * @method \Spryker\Zed\Offer\Communication\OfferCommunicationFactory getFactory()
 * @method \Spryker\Zed\Offer\OfferConfig getConfig()
 */
class OfferItemSubtotalAggregationPlugin extends AbstractPlugin implements CalculationPluginInterface
{
    /**
     * Guide: This calculator plugin must be placed under ItemSubtotalAggregatorPlugin
     * after the generic logic is executed.
     *
     *
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
         $this->getFacade()->aggregateOfferItemSubtotal($calculableObjectTransfer);
    }
}
