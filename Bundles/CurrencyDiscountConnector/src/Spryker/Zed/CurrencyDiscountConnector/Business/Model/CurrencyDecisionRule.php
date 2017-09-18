<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CurrencyDiscountConnector\Business\Model;

use Generated\Shared\Transfer\ClauseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CurrencyDiscountConnector\Dependency\Facade\CurrencyDiscountConnectorToCurrencyInterface;
use Spryker\Zed\CurrencyDiscountConnector\Dependency\Facade\CurrencyDiscountConnectorToDiscountInterface;

class CurrencyDecisionRule implements CurrencyDecisionRuleInterface
{

    /**
     * @var \Spryker\Zed\CurrencyDiscountConnector\Dependency\Facade\CurrencyDiscountConnectorToCurrencyInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\CurrencyDiscountConnector\Dependency\Facade\CurrencyDiscountConnectorToDiscountInterface
     */
    protected $discountFacade;

    /**
     * @param \Spryker\Zed\CurrencyDiscountConnector\Dependency\Facade\CurrencyDiscountConnectorToCurrencyInterface $currencyFacade
     * @param \Spryker\Zed\CurrencyDiscountConnector\Dependency\Facade\CurrencyDiscountConnectorToDiscountInterface $discountFacade
     */
    public function __construct(
        CurrencyDiscountConnectorToCurrencyInterface $currencyFacade,
        CurrencyDiscountConnectorToDiscountInterface $discountFacade
    ) {
        $this->currencyFacade = $currencyFacade;
        $this->discountFacade = $discountFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ClauseTransfer $clauseTransfer
     *
     * @return bool
     */
    public function isCurrencySatisfiedBy(
        QuoteTransfer $quoteTransfer,
        ItemTransfer $itemTransfer,
        ClauseTransfer $clauseTransfer
    ) {
        $currentCurrencyIsoCode = $this->currencyFacade->getCurrent()->getCode();
        return $this->discountFacade->queryStringCompare($clauseTransfer, $currentCurrencyIsoCode);

    }

}
