<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductSalesOrderAmendment;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentFactory getFactory()
 */
class PriceProductSalesOrderAmendmentClient extends AbstractClient implements PriceProductSalesOrderAmendmentClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function resolveOrderAmendmentPrice(
        PriceProductTransfer $priceProductTransfer,
        PriceProductFilterTransfer $priceProductFilterTransfer
    ): PriceProductTransfer {
        return $this->getFactory()
            ->createOrderAmendmentPriceResolver()
            ->resolve($priceProductTransfer, $priceProductFilterTransfer);
    }
}
