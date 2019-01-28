<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PersistentCart\Business\Quote;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestAttributesTransfer;
use Generated\Shared\Transfer\QuoteUpdateRequestTransfer;
use Spryker\Zed\PersistentCart\Business\Model\QuoteWriterInterface;
use Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface;

class QuoteAfterCalculateWriter implements QuoteAfterCalculateWriterInterface
{
    /**
     * @uses Spryker\Shared\Quote\QuoteConfig::STORAGE_STRATEGY_DATABASE
     */
    protected const STORAGE_STRATEGY_DATABASE = 'database';

    /**
     * @var \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface
     */
    protected $quoteFacade;

    /**
     * @var \Spryker\Zed\PersistentCart\Business\Model\QuoteWriterInterface
     */
    protected $quoteWriter;

    /**
     * @param \Spryker\Zed\PersistentCart\Dependency\Facade\PersistentCartToQuoteFacadeInterface $quoteFacade
     * @param \Spryker\Zed\PersistentCart\Business\Model\QuoteWriterInterface $quoteWriter
     */
    public function __construct(
        PersistentCartToQuoteFacadeInterface $quoteFacade,
        QuoteWriterInterface $quoteWriter
    ) {
        $this->quoteFacade = $quoteFacade;
        $this->quoteWriter = $quoteWriter;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function updateQuote(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        if (!$this->isQuoteNeedUpdate($quoteTransfer)) {
            return $quoteTransfer;
        }

        $quoteUpdateRequestTransfer = $this->prepareQuoteUpdateRequestTransfer($quoteTransfer);

        return $this->quoteWriter->updateQuote(
            $quoteUpdateRequestTransfer
        )->getQuoteTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isQuoteNeedUpdate(QuoteTransfer $quoteTransfer): bool
    {
        if ($quoteTransfer->getIdQuote() === null) {
            return false;
        }

        return $this->quoteFacade->getStorageStrategy() === static::STORAGE_STRATEGY_DATABASE;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteUpdateRequestTransfer
     */
    protected function prepareQuoteUpdateRequestTransfer(QuoteTransfer $quoteTransfer): QuoteUpdateRequestTransfer
    {
        $quoteUpdateRequestAttributesTransfer = new QuoteUpdateRequestAttributesTransfer();
        $quoteUpdateRequestAttributesTransfer->fromArray($quoteTransfer->modifiedToArray(), true);

        $quoteUpdateRequestTransfer = new QuoteUpdateRequestTransfer();
        $quoteUpdateRequestTransfer->setIdQuote($quoteTransfer->getIdQuote());
        $quoteUpdateRequestTransfer->setCustomer($quoteTransfer->getCustomer());
        $quoteUpdateRequestTransfer->setQuoteUpdateRequestAttributes($quoteUpdateRequestAttributesTransfer);

        return $quoteUpdateRequestTransfer;
    }
}
