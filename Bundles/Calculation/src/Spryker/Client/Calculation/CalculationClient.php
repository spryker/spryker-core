<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Calculation;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\Calculation\CalculationFactory getFactory()
 */
class CalculationClient extends AbstractClient implements CalculationClientInterface
{
    /**
     * Recalculates the given quote and returns an updated one.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->createZedStub()->recalculate($quoteTransfer);
    }

    /**
     * @api
     *
     * @return \Spryker\Client\Calculation\Zed\CalculationStubInterface
     */
    public function getZedStub()
    {
        return $this->getFactory()->createZedStub();
    }
}
