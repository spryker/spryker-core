<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CalculationCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\Checkout\Business\Calculation\CalculableContainer;

interface CalculationCheckoutConnectorFacadeInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $request
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $response
     *
     * @return void
     */
    public function checkCartAmountCorrect(CheckoutRequestTransfer $request, CheckoutResponseTransfer $response);

    /**
     * @api
     *
     * @param \Spryker\Zed\Checkout\Business\Calculation\CalculableContainer $calculableContainer
     *
     * @return \Spryker\Zed\Calculation\Business\Model\CalculableInterface
     */
    public function recalculate(CalculableContainer $calculableContainer);

}
