<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Calculation\Zed;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\ZedRequest\Stub\BaseStub;

class CalculationStub extends BaseStub
{

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function recalculate(QuoteTransfer $quoteTransfer)
    {
        return $this->zedStub->call('/calculation/gateway/recalculate', $quoteTransfer);
    }

}
