<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Communication\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CalculationExtension\Dependency\Plugin\QuoteAfterCalculatePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\PersistentCart\Business\PersistentCartFacade getFacade()
 * @method \Spryker\Zed\PersistentCart\PersistentCartConfig getConfig()
 */
class QuoteSaveAfterQuoteCalculatePlugin extends AbstractPlugin implements QuoteAfterCalculatePluginInterface
{
    /**
     * {@inheritdoc}
     * - Updates quote after calculation.
     * - Does nothing if not `DatabaseStorageStrategy` is disabled.
     * - Does nothing if quote does't have ID.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function afterCalculate(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()->updateQuoteAfterCalculate($quoteTransfer);
    }
}
