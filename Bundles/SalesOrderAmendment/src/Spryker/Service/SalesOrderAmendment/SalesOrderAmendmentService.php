<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\SalesOrderAmendment;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\SalesOrderAmendment\SalesOrderAmendmentServiceFactory getFactory()
 */
class SalesOrderAmendmentService extends AbstractService implements SalesOrderAmendmentServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    public function buildOriginalSalesOrderItemGroupKey(ItemTransfer $itemTransfer): string
    {
        return $this->getFactory()
            ->createOriginalSalesOrderItemGroupKeyBuilder()
            ->buildOriginalSalesOrderItemGroupKey($itemTransfer);
    }
}
