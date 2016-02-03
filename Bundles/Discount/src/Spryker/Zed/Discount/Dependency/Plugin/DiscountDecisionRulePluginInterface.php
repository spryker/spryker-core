<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Discount\Dependency\Plugin;

use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\ModelResult;

interface DiscountDecisionRulePluginInterface
{

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Spryker\Zed\Kernel\Business\ModelResult
     */
    public function check(DiscountTransfer $discountTransfer, QuoteTransfer $quoteTransfer);

    /**
     * @param array $context
     */
    public function setContext(array $context);

    /**
     * @return array
     */
    public function getContext();

}
