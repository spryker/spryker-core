<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Currency\Communication\Plugin\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteValidatorPluginInterface;

/**
 * @method \Spryker\Zed\Currency\Business\CurrencyFacade getFacade()
 * @method \Spryker\Zed\Currency\CurrencyConfig getConfig()
 * @method \Spryker\Zed\Currency\Persistence\CurrencyQueryContainer getQueryContainer()()
 */
class QuoteCurrencyValidatorPlugin extends AbstractPlugin implements QuoteValidatorPluginInterface
{
    /**
     * {@inheritdoc}
     * - Validates if provided currency in quote is available.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    public function validate(QuoteTransfer $quoteTransfer): QuoteValidationResponseTransfer
    {
        return $this->getFacade()->validateCurrencyInQuote($quoteTransfer);
    }
}
