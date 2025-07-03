<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderAmendmentOms\Communication\Plugin\SalesOrderAmendment;

use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Generated\Shared\Transfer\SalesOrderAmendmentTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentValidatorRulePluginInterface;

/**
 * @method \Spryker\Zed\SalesOrderAmendmentOms\SalesOrderAmendmentOmsConfig getConfig()
 * @method \Spryker\Zed\SalesOrderAmendmentOms\Business\SalesOrderAmendmentOmsFacadeInterface getFacade()
 * @method \Spryker\Zed\SalesOrderAmendmentOms\Communication\SalesOrderAmendmentOmsCommunicationFactory getFactory()
 */
class OrderSalesOrderAmendmentValidatorRulePlugin extends AbstractPlugin implements SalesOrderAmendmentValidatorRulePluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `SalesOrderAmendmentTransfer.originalOrderReference` to be set.
     * - Requires `SalesOrderAmendmentTransfer.amendedOrderReference` to be set.
     * - Validates if order with provided original order reference exists.
     * - Validates if order with provided amended order reference exists.
     * - Returns `ErrorCollectionTransfer` with error messages if validation fails.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(SalesOrderAmendmentTransfer $salesOrderAmendmentTransfer): ErrorCollectionTransfer
    {
        return $this->getFacade()->validateSalesOrderAmendment($salesOrderAmendmentTransfer);
    }
}
