<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuoteApproval;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\QuoteApproval\StatusCalculator\QuoteApprovalStatusCalculator;
use Spryker\Client\QuoteApproval\StatusCalculator\QuoteApprovalStatusCalculatorInterface;

class QuoteApprovalFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\QuoteApproval\StatusCalculator\QuoteApprovalStatusCalculatorInterface
     */
    public function createQuoteApprovalStatusCalculator(): QuoteApprovalStatusCalculatorInterface
    {
        return new QuoteApprovalStatusCalculator();
    }
}
