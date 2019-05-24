<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Model;

use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;

class StoreValidator implements StoreValidatorInterface
{
    protected const ERROR_MESSAGE_STORE_DATA_IS_MISSING = 'Store data is missing';
    protected const ERROR_MESSAGE_STORE_NOT_FOUND = 'Store not found.';

    /**
     * @var \Spryker\Zed\Store\Business\Model\StoreReaderInterface
     */
    protected $storeReader;

    /**
     * @param \Spryker\Zed\Store\Business\Model\StoreReaderInterface $storeReader
     */
    public function __construct(StoreReaderInterface $storeReader)
    {
        $this->storeReader = $storeReader;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    public function validateQuoteStore(QuoteTransfer $quoteTransfer): QuoteValidationResponseTransfer
    {
        $quoteValidationResponseTransfer = (new QuoteValidationResponseTransfer())->setIsSuccessful(true);
        $storeTransfer = $quoteTransfer->getStore();

        if (!$storeTransfer || !$storeTransfer->getName()) {
            return $this->addValidationError($quoteValidationResponseTransfer, static::ERROR_MESSAGE_STORE_DATA_IS_MISSING);
        }

        $storeTransfer = $this->storeReader->findStoreByName($storeTransfer->getName());
        if (!$storeTransfer) {
            return $this->addValidationError($quoteValidationResponseTransfer, static::ERROR_MESSAGE_STORE_NOT_FOUND);
        }

        return $quoteValidationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteValidationResponseTransfer $quoteValidationResponseTransfer
     * @param string $errorMessage
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    protected function addValidationError(
        QuoteValidationResponseTransfer $quoteValidationResponseTransfer,
        string $errorMessage
    ): QuoteValidationResponseTransfer {
        return $quoteValidationResponseTransfer
            ->addErrors((new QuoteErrorTransfer())->setMessage($errorMessage))
            ->setIsSuccessful(false);
    }
}
