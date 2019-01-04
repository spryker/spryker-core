<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;
use Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeInterface;
use Spryker\Zed\Store\Business\Model\Exception\StoreNotFoundException;

class QuoteValidator implements QuoteValidatorInterface
{
    protected const MESSAGE_STORE_DATA_IS_MISSING = 'Store data is missing.';

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @var \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    protected $quoteValidationResponseTransfer;

    /**
     * @var \Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeInterface $storeFacade
     */
    public function __construct(QuoteToStoreFacadeInterface $storeFacade)
    {
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    public function validate(QuoteTransfer $quoteTransfer): QuoteValidationResponseTransfer
    {
        $this->initialize($quoteTransfer);
        $this->validateStore();

        return $this->quoteValidationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function initialize(QuoteTransfer $quoteTransfer): void
    {
        $this->quoteTransfer = clone $quoteTransfer;

        $this->quoteValidationResponseTransfer = (new QuoteValidationResponseTransfer())
            ->setIsValid(true)
            ->setErrors(new ArrayObject());
    }

    /**
     * @param string $message
     *
     * @return void
     */
    protected function addValidationError(string $message): void
    {
        $quoteErrorTransfer = (new QuoteErrorTransfer())->setMessage($message);
        $this->quoteValidationResponseTransfer
            ->addErrors($quoteErrorTransfer)
            ->setIsValid(false);
    }

    /**
     * @return void
     */
    protected function validateStore(): void
    {
        $storeTransfer = $this->quoteTransfer->getStore();

        if (!$storeTransfer || !$storeTransfer->getName()) {
            $this->addValidationError(static::MESSAGE_STORE_DATA_IS_MISSING);

            return;
        }

        try {
            $quoteTransfer = $this->storeFacade->getStoreByName($storeTransfer->getName());
        } catch (StoreNotFoundException $exception) {
            $this->addValidationError($exception->getMessage());
        }
    }
}
