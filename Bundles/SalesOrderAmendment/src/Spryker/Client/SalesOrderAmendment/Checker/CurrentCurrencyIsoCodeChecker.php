<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SalesOrderAmendment\Checker;

use Generated\Shared\Transfer\CurrencyTransfer;
use Spryker\Client\SalesOrderAmendment\Dependency\Client\SalesOrderAmendmentToMessengerClientInterface;
use Spryker\Client\SalesOrderAmendment\Dependency\Client\SalesOrderAmendmentToQuoteClientInterface;

class CurrentCurrencyIsoCodeChecker implements CurrentCurrencyIsoCodeCheckerInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PRE_CHECK_CANNOT_CHANGE_CURRENCY = 'sales_order_amendment.pre_check.cannot_change_currency';

    /**
     * @param \Spryker\Client\SalesOrderAmendment\Dependency\Client\SalesOrderAmendmentToQuoteClientInterface $quoteClient
     * @param \Spryker\Client\SalesOrderAmendment\Dependency\Client\SalesOrderAmendmentToMessengerClientInterface $messengerClient
     */
    public function __construct(
        protected SalesOrderAmendmentToQuoteClientInterface $quoteClient,
        protected SalesOrderAmendmentToMessengerClientInterface $messengerClient
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\CurrencyTransfer $currencyTransfer
     *
     * @return bool
     */
    public function execute(CurrencyTransfer $currencyTransfer): bool
    {
        $quoteTransfer = $this->quoteClient->getQuote();

        if ($quoteTransfer->getAmendmentOrderReference() && $quoteTransfer->getCurrencyOrFail()->getCode() !== $currencyTransfer->getCode()) {
            $this->messengerClient->addErrorMessage(static::GLOSSARY_KEY_PRE_CHECK_CANNOT_CHANGE_CURRENCY);

            return false;
        }

        return true;
    }
}
