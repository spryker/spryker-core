<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeCart\Business\Validator;

use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;

class MultiShipmentShipmentTypeCheckoutValidator implements ShipmentTypeCheckoutValidatorInterface
{
    /**
     * @var list<\Spryker\Zed\ShipmentTypeCart\Business\Validator\Rule\ShipmentTypeCheckoutValidationRuleInterface>
     */
    protected array $shipmentTypeCheckoutValidationRules;

    /**
     * @param list<\Spryker\Zed\ShipmentTypeCart\Business\Validator\Rule\ShipmentTypeCheckoutValidationRuleInterface> $shipmentTypeCheckoutValidationRules
     */
    public function __construct(array $shipmentTypeCheckoutValidationRules)
    {
        $this->shipmentTypeCheckoutValidationRules = $shipmentTypeCheckoutValidationRules;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function isQuoteReadyForCheckout(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        foreach ($this->shipmentTypeCheckoutValidationRules as $shipmentTypeCheckoutValidationRule) {
            $validationResult = $shipmentTypeCheckoutValidationRule->isQuoteReadyForCheckout(
                $quoteTransfer,
                $checkoutResponseTransfer,
            );

            if (!$validationResult) {
                return false;
            }
        }

        return true;
    }
}
