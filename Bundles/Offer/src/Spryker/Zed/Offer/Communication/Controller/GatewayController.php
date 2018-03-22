<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Offer\Communication\Controller;

use Generated\Shared\Transfer\OfferToOrderConvertRequestTransfer;
use Spryker\Zed\Kernel\Communication\Controller\AbstractGatewayController;

/**
 * @method \Spryker\Zed\Offer\Business\OfferFacadeInterface getFacade()
 */
class GatewayController extends AbstractGatewayController
{
    /**
     * @param \Generated\Shared\Transfer\OfferToOrderConvertRequestTransfer $requestTransfer
     *
     * @return \Generated\Shared\Transfer\OfferToOrderConvertResponseTransfer
     */
    public function convertOfferToOrderAction(OfferToOrderConvertRequestTransfer $requestTransfer)
    {
        return $this->getFacade()->convertOfferToOrder($requestTransfer->getOrder()->getIdSalesOrder());
    }
}
