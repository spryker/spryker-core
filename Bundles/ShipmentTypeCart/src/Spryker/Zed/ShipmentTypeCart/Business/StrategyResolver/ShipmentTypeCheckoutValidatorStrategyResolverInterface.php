<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeCart\Business\StrategyResolver;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ShipmentTypeCart\Business\Validator\ShipmentTypeCheckoutValidatorInterface;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 */
interface ShipmentTypeCheckoutValidatorStrategyResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\ShipmentTypeCart\Business\Validator\ShipmentTypeCheckoutValidatorInterface
     */
    public function resolve(QuoteTransfer $quoteTransfer): ShipmentTypeCheckoutValidatorInterface;
}
