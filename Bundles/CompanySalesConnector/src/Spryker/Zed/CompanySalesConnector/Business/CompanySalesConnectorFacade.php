<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanySalesConnector\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\CompanySalesConnector\Persistence\CompanySalesConnectorEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CompanySalesConnector\Persistence\CompanySalesConnectorRepositoryInterface getRepository()
 * @method \Spryker\Zed\CompanySalesConnector\Business\CompanySalesConnectorBusinessFactory getFactory()
 */
class CompanySalesConnectorFacade extends AbstractFacade implements CompanySalesConnectorFacadeInterface
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
    public function updateOrderCompanyUuid(SaveOrderTransfer $saveOrderTransfer, QuoteTransfer $quoteTransfer): void
    {
        $this->getFactory()
            ->createOrderWriter()
            ->updateOrderCompanyUuid($saveOrderTransfer, $quoteTransfer);
    }
}
