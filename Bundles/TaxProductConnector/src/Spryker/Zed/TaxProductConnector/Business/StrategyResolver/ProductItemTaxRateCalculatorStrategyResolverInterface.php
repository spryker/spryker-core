<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Business\StrategyResolver;

use ArrayObject;
use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Zed\TaxProductConnector\Business\Calculator\CalculatorInterface;

/**
 * @deprecated Exists for Backward Compatibility reasons only.
 */
interface ProductItemTaxRateCalculatorStrategyResolverInterface
{
    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     * @param \Generated\Shared\Transfer\AddressTransfer|null $shippingAddressTransfer
     *
     * @return \Spryker\Zed\TaxProductConnector\Business\Calculator\CalculatorInterface
     */
    public function resolve(ArrayObject $itemTransfers, ?AddressTransfer $shippingAddressTransfer): CalculatorInterface;
}
