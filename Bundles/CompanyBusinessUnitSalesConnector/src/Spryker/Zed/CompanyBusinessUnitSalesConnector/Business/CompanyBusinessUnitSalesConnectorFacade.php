<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CompanyBusinessUnitSalesConnector\Persistence\CompanyBusinessUnitSalesConnectorEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CompanyBusinessUnitSalesConnector\Persistence\CompanyBusinessUnitSalesConnectorRepositoryInterface getRepository()
 * @method \Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\CompanyBusinessUnitSalesConnectorBusinessFactory getFactory()
 */
class CompanyBusinessUnitSalesConnectorFacade extends AbstractFacade implements CompanyBusinessUnitSalesConnectorFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function updateOrderCompanyBusinessUnitUuid(
        SaveOrderTransfer $saveOrderTransfer,
        QuoteTransfer $quoteTransfer
    ): void {
        $this->getFactory()
            ->createOrderWriter()
            ->updateOrderCompanyBusinessUnitUuid($saveOrderTransfer, $quoteTransfer);
    }
}
