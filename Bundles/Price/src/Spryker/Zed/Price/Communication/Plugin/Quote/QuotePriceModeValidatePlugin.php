<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Price\Communication\Plugin\Quote;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteValidatePluginInterface;

/**
 * @method \Spryker\Zed\Price\Business\PriceFacade getFacade()
 * @method \Spryker\Zed\Price\Communication\PriceCommunicationFactory getFactory()
 * @method \Spryker\Zed\Price\PriceConfig getConfig()
 */
class QuotePriceModeValidatePlugin extends AbstractPlugin implements QuoteValidatePluginInterface
{
    /**
     * {@inheritdoc}
     * - Validates if provided quote price mode is available.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    public function validate(QuoteTransfer $quoteTransfer): MessageTransfer
    {
        return $this->getFacade()->validatePriceModeInQuote($quoteTransfer);
    }
}
