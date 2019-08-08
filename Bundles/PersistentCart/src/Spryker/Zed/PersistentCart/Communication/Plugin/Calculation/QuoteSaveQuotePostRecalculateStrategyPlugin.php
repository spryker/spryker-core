<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Communication\Plugin\Calculation;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;
use Spryker\Zed\CalculationExtension\Dependency\Plugin\QuotePostRecalculatePluginStrategyInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\PersistentCart\Business\PersistentCartFacadeInterface getFacade()
 * @method \Spryker\Zed\PersistentCart\Communication\PersistentCartCommunicationFactory getFactory()
 * @method \Spryker\Zed\PersistentCart\PersistentCartConfig getConfig()
 */
class QuoteSaveQuotePostRecalculateStrategyPlugin extends AbstractPlugin implements QuotePostRecalculatePluginStrategyInterface
{
    /**
     * @uses \Spryker\Shared\Quote\QuoteConfig::STORAGE_STRATEGY_DATABASE
     */
    protected const STORAGE_STRATEGY_DATABASE = 'database';

    /**
     * {@inheritdoc}
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
        $this->getFacade()
            ->updateQuote($this->prepareQuoteUpdateRequestTransfer($quoteTransfer));

        return $quoteTransfer;
    }

    /**
     * {@inheritdoc}
     * - Returns false if quote does't have ID.
     * - Returns true if `DatabaseStorageStrategy` is enabled.
     * - Returns false if `DatabaseStorageStrategy` is disabled.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    public function isApplicable(QuoteTransfer $quoteTransfer): bool
    {
        if ($quoteTransfer->getIdQuote() === null) {
            return false;
        }

        if (!$this->isStorageStrategyDatabase()) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    protected function isStorageStrategyDatabase(): bool
    {
        $storageStrategy = $this->getFactory()
            ->getQuoteFacade()
            ->getStorageStrategy();

        return $storageStrategy === static::STORAGE_STRATEGY_DATABASE;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteUpdateRequestTransfer
     */
    protected function prepareQuoteUpdateRequestTransfer(QuoteTransfer $quoteTransfer): QuoteUpdateRequestTransfer
    {
        $quoteUpdateRequestAttributesTransfer = (new QuoteUpdateRequestAttributesTransfer())
            ->fromArray($quoteTransfer->modifiedToArray(), true);

        $quoteUpdateRequestTransfer = (new QuoteUpdateRequestTransfer())
            ->setIdQuote($quoteTransfer->getIdQuote())
            ->setCustomer($quoteTransfer->getCustomer())
            ->setQuoteUpdateRequestAttributes($quoteUpdateRequestAttributesTransfer);

        return $quoteUpdateRequestTransfer;
    }
}
