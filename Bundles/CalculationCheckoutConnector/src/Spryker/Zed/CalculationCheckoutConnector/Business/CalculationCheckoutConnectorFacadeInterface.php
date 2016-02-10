<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\CalculationCheckoutConnector\Business;

use Generated\Shared\Transfer\CheckoutRequestTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Spryker\Zed\Checkout\Business\Calculation\CalculableContainer;

interface CalculationCheckoutConnectorFacadeInterface
{

    /**
     * @param \Generated\Shared\Transfer\CheckoutRequestTransfer $request
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $response
     *
     * @return void
     */
    public function checkCartAmountCorrect(CheckoutRequestTransfer $request, CheckoutResponseTransfer $response);

    /**
     * @param \Spryker\Zed\Checkout\Business\Calculation\CalculableContainer $calculableContainer
     *
     * @return \Spryker\Zed\Calculation\Business\Model\CalculableInterface
     */
    public function recalculate(CalculableContainer $calculableContainer);

}
