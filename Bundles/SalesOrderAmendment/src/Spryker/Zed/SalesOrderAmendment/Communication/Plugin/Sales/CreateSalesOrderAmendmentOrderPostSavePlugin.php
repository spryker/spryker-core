<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendment\Communication\Plugin\Sales;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentRequestTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesExtension\Dependency\Plugin\OrderPostSavePluginInterface;

/**
 * @method \Spryker\Zed\SalesOrderAmendment\SalesOrderAmendmentConfig getConfig()
 * @method \Spryker\Zed\SalesOrderAmendment\Business\SalesOrderAmendmentFacadeInterface getFacade()
 */
class CreateSalesOrderAmendmentOrderPostSavePlugin extends AbstractPlugin implements OrderPostSavePluginInterface
{
    /**
     * {@inheritDoc}
     * - Does nothing if `QuoteTransfer.amendmentOrderReference` is not set.
     * - Requires `SalesOrderAmendmentRequestTransfer.originalOrderReference` to be set.
     * - Requires `SalesOrderAmendmentRequestTransfer.amendedOrderReference` to be set.
     * - Executes a stack of {@link \Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentValidatorRulePluginInterface} plugins.
     * - Executes a stack of {@link \Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPreCreatePluginInterface} plugins.
     * - Persists sales order amendment entity.
     * - Executes a stack of {@link \Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentPostCreatePluginInterface} plugins.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function execute(SaveOrderTransfer $saveOrderTransfer, QuoteTransfer $quoteTransfer): SaveOrderTransfer
    {
        if ($quoteTransfer->getAmendmentOrderReference() === null) {
            return $saveOrderTransfer;
        }

        $salesOrderAmendmentRequestTransfer = (new SalesOrderAmendmentRequestTransfer())
            ->setOriginalOrderReference($quoteTransfer->getAmendmentOrderReferenceOrFail())
            ->setAmendedOrderReference($saveOrderTransfer->getOrderReferenceOrFail());
        $this->getFacade()->createSalesOrderAmendment($salesOrderAmendmentRequestTransfer);

        return $saveOrderTransfer;
    }
}
