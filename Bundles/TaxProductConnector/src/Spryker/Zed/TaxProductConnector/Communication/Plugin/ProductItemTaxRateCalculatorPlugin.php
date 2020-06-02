<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Communication\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Calculation\Dependency\Plugin\CalculatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @deprecated Use {@link \Spryker\Zed\TaxProductConnector\Communication\Plugin\Calculation\ProductItemTaxRateCalculatorPlugin} instead.
 *
 * @method \Spryker\Zed\TaxProductConnector\Business\TaxProductConnectorFacadeInterface getFacade()
 * @method \Spryker\Zed\TaxProductConnector\TaxProductConnectorConfig getConfig()
 * @method \Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorQueryContainerInterface getQueryContainer()
 */
class ProductItemTaxRateCalculatorPlugin extends AbstractPlugin implements CalculatorPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        $this->getFacade()->calculateProductItemTaxRate($quoteTransfer);
        
        return $quoteTransfer;
    }
}
