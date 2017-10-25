<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardMailConnector\Business;

interface GiftCardMailConnectorFacadeInterface
{
    /**
     * Specification:
     * - Finds data about related to the order item gift card
     * - Finds data about related to the order item customer
     * - Uses Mail facade to send an email with a gift card code
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer
     */
    public function deliverGiftCardByEmail($idSalesOrderItem);
}
