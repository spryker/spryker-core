<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardMailConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\GiftCardMailConnector\Business\GiftCardMailConnectorBusinessFactory getFactory()
 */
class GiftCardMailConnectorFacade extends AbstractFacade implements GiftCardMailConnectorFacadeInterface
{
    /**
     * @api
     *
     * @inheritdoc
     *
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer
     */
    public function deliverGiftCardByEmail($idSalesOrderItem)
    {
        return $this->getFactory()
            ->createGiftCardCarrier()
            ->deliverByIdSalesOrderItem($idSalesOrderItem);
    }
}
