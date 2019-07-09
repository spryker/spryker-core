<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Business\Calculator;

use Spryker\Zed\Tax\Business\Model\CalculatorInterface as DeprecatedCalculatorInterface;

/**
 * Added in BC manner to avoid cross module dependency violations.
 * In the next major should be removed inheritance of \Spryker\Zed\Tax\Business\Model\CalculatorInterface.
 * And recalculate() method declaration should be copied into current \Spryker\Zed\TaxProductConnector\Business\Calculator\CalculatorInterface.
 */
interface CalculatorInterface extends DeprecatedCalculatorInterface
{
}
