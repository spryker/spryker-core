<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesOrderAmendment\Checker;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\SalesOrderAmendment\Dependency\Client\SalesOrderAmendmentToMessengerClientInterface;

class CurrentPriceModeChecker implements CurrentPriceModeCheckerInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PRE_CHECK_CANNOT_CHANGE_PRICE_MODE = 'sales_order_amendment.pre_check.cannot_change_price_mode';

    /**
     * @param \Spryker\Client\SalesOrderAmendment\Dependency\Client\SalesOrderAmendmentToMessengerClientInterface $messengerClient
     */
    public function __construct(
        protected SalesOrderAmendmentToMessengerClientInterface $messengerClient
    ) {
    }

    /**
     * @param string $priceMode
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function execute(string $priceMode, QuoteTransfer $quoteTransfer): bool
    {
        if ($quoteTransfer->getAmendmentOrderReference() && $quoteTransfer->getPriceMode() !== $priceMode) {
            $this->messengerClient->addErrorMessage(static::GLOSSARY_KEY_PRE_CHECK_CANNOT_CHANGE_PRICE_MODE);

            return false;
        }

        return true;
    }
}
