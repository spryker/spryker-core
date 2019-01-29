<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Communication\Plugin\Calculation;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CalculationExtension\Dependency\Plugin\PostQuoteRecalculatePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\PersistentCart\Business\PersistentCartFacade getFacade()
 * @method \Spryker\Zed\PersistentCart\PersistentCartConfig getConfig()
 */
class QuoteAttributesSavePostQuoteRecalculatePlugin extends AbstractPlugin implements PostQuoteRecalculatePluginInterface
{
    /**
     * {@inheritdoc}
     * - Does nothing if `DatabaseStorageStrategy` is disabled.
     * - Does nothing if quote does't have ID.
     * - Saves quote attributes described in QuoteUpdateRequestAttributesTransfer to database.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFacade()->updateQuoteAttributes($quoteTransfer);
    }
}
