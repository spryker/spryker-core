<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesShipmentType\Business;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SalesShipmentType\Business\SalesShipmentTypeBusinessFactory getFactory()
 * @method \Spryker\Zed\SalesShipmentType\Persistence\SalesShipmentTypeEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesShipmentType\Persistence\SalesShipmentTypeRepositoryInterface getRepository()
 */
class SalesShipmentTypeFacade extends AbstractFacade implements SalesShipmentTypeFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function updateSalesShipmentsWithSalesShipmentType(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): SaveOrderTransfer {
        return $this->getFactory()
            ->createSalesShipmentUpdater()
            ->updateSalesShipmentsWithSalesShipmentType($saveOrderTransfer);
    }
}
