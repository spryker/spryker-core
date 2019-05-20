<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business\Validator;

use Generated\Shared\Transfer\QuoteErrorTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;
use Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeInterface;

class QuoteValidator implements QuoteValidatorInterface
{
    protected const ERROR_MESSAGE_STORE_DATA_IS_MISSING = 'quote.validation.error.store_is_missing';
    protected const ERROR_MESSAGE_STORE_NOT_FOUND = 'Store not found.';

    /**
     * @var \Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteValidatorPluginInterface[]
     */
    protected $quoteValidatorPlugins;

    /**
     * @param \Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\QuoteExtension\Dependency\Plugin\QuoteValidatorPluginInterface[] $quoteValidatorPlugins
     */
    public function __construct(QuoteToStoreFacadeInterface $storeFacade, array $quoteValidatorPlugins)
    {
        $this->storeFacade = $storeFacade;
        $this->quoteValidatorPlugins = $quoteValidatorPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    public function validate(QuoteTransfer $quoteTransfer): QuoteValidationResponseTransfer
    {
        $quoteValidationResponseTransfer = (new QuoteValidationResponseTransfer())
            ->setIsSuccessful(true);
        $quoteValidationResponseTransfer = $this->validateStore($quoteTransfer, $quoteValidationResponseTransfer);
        $quoteValidationResponseTransfer = $this->executeQuoteValidatorPlugins($quoteTransfer, $quoteValidationResponseTransfer);

        return $quoteValidationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\QuoteValidationResponseTransfer $quoteValidationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    protected function validateStore(
        QuoteTransfer $quoteTransfer,
        QuoteValidationResponseTransfer $quoteValidationResponseTransfer
    ): QuoteValidationResponseTransfer {
        $storeTransfer = $quoteTransfer->getStore();

        if (!$storeTransfer || !$storeTransfer->getName()) {
            return $this->addValidationError($quoteValidationResponseTransfer, static::ERROR_MESSAGE_STORE_DATA_IS_MISSING);
        }

        $storeTransfer = $this->storeFacade->findStoreByName($storeTransfer->getName());
        if (!$storeTransfer) {
            return $this->addValidationError($quoteValidationResponseTransfer, static::ERROR_MESSAGE_STORE_NOT_FOUND);
        }

        return $quoteValidationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\QuoteValidationResponseTransfer $quoteValidationResponseTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    protected function executeQuoteValidatorPlugins(
        QuoteTransfer $quoteTransfer,
        QuoteValidationResponseTransfer $quoteValidationResponseTransfer
    ): QuoteValidationResponseTransfer {
        foreach ($this->quoteValidatorPlugins as $quoteValidatorPlugin) {
            $quoteValidationResponseTransferFromPlugin = $quoteValidatorPlugin->validate($quoteTransfer);

            if (!$quoteValidationResponseTransferFromPlugin->getIsSuccessful()) {
                foreach ($quoteValidationResponseTransferFromPlugin->getErrors() as $quoteErrorTransfer) {
                    $quoteValidationResponseTransfer->addErrors($quoteErrorTransfer);
                }
                $quoteValidationResponseTransfer->setIsSuccessful(false);
            }
        }

        return $quoteValidationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteValidationResponseTransfer $quoteValidationResponseTransfer
     * @param string $errorMessage
     * @param array $parameters
     *
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    protected function addValidationError(
        QuoteValidationResponseTransfer $quoteValidationResponseTransfer,
        string $errorMessage,
        array $parameters = []
    ): QuoteValidationResponseTransfer {
        $quoteErrorTransfer = (new QuoteErrorTransfer())->setMessage($errorMessage)
            ->setParameters($parameters);

        return $quoteValidationResponseTransfer
            ->addErrors($quoteErrorTransfer)
            ->setIsSuccessful(false);
    }
}
