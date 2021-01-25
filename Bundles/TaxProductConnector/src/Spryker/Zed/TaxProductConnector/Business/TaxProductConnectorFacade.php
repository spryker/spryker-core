<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Business;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\TaxSetResponseTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\TaxProductConnector\Business\TaxProductConnectorBusinessFactory getFactory()
 * @method \Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorRepositoryInterface getRepository()
 */
class TaxProductConnectorFacade extends AbstractFacade implements TaxProductConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function saveTaxSetToProductAbstract(ProductAbstractTransfer $productConcreteTransfer)
    {
        return $this->getFactory()
            ->createProductAbstractTaxWriter()
            ->saveTaxSetToProductAbstract($productConcreteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function mapTaxSet(ProductAbstractTransfer $productAbstractTransfer)
    {
        return $this->getFactory()
            ->createProductAbstractTaxSetMapper()
            ->mapTaxSet($productAbstractTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function calculateProductItemTaxRate(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()
            ->createProductItemTaxRateCalculatorStrategyResolver()
            ->resolve($quoteTransfer->getItems(), $quoteTransfer->getShippingAddress())
            ->recalculate($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function calculateProductItemTaxRateForCalculableObjectTransfer(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        return $this->getFactory()
            ->createProductItemTaxRateCalculatorStrategyResolver()
            ->resolve($calculableObjectTransfer->getItems(), $calculableObjectTransfer->getShippingAddress())
            ->recalculateWithCalculableObject($calculableObjectTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Will be removed without replacement.
     *
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\TaxSetResponseTransfer
     */
    public function getTaxSetForProductAbstract(ProductAbstractTransfer $productAbstractTransfer): TaxSetResponseTransfer
    {
        return $this->getFactory()
            ->createProductAbstractTaxReader()
            ->getTaxSetByProductAbstract($productAbstractTransfer);
    }
}
