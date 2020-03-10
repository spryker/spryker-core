<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OrderCustomReference\Communication\Plugin\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteFieldsAllowedForSavingProviderPluginInterface;

/**
 * @method \Spryker\Zed\OrderCustomReference\OrderCustomReferenceConfig getConfig()
 * @method \Spryker\Zed\OrderCustomReference\Business\OrderCustomReferenceFacadeInterface getFacade()
 */
class OrderCustomReferenceQuoteFieldsAllowedForSavingProviderPlugin extends AbstractPlugin implements QuoteFieldsAllowedForSavingProviderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns QuoteTransfer fields related to order custom reference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string[]
     */
    public function execute(QuoteTransfer $quoteTransfer): array
    {
        return [
            QuoteTransfer::ORDER_CUSTOM_REFERENCE,
        ];
    }
}
