<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Quote\Business\Validator;

use ArrayObject;
use Generated\Shared\Transfer\ErrorMessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;
use Spryker\Zed\Quote\Dependency\Facade\QuoteToStoreFacadeInterface;
use Spryker\Zed\Store\Business\Model\Exception\StoreNotFoundException;

class QuoteValidator implements QuoteValidatorInterface
{
    protected const ERROR_MESSAGE_STORE_DATA_IS_MISSING = 'quote.validation.error.store_is_missing';

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
            ->setIsSuccess(true);
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

        try {
            $storeTransfer = $this->storeFacade->getStoreByName($storeTransfer->getName());
        } catch (StoreNotFoundException $exception) {
            return $this->addValidationError($quoteValidationResponseTransfer, $exception->getMessage());
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

            if (!$quoteValidationResponseTransferFromPlugin->getIsSuccess()) {
                $errors = array_merge(
                    $quoteValidationResponseTransfer->getErrors()->getArrayCopy(),
                    $quoteValidationResponseTransferFromPlugin->getErrors()->getArrayCopy()
                );
                $quoteValidationResponseTransfer->setErrors(new ArrayObject($errors))
                    ->setIsSuccess(false);
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
        $errorMessageTransfer = (new ErrorMessageTransfer())->setValue($errorMessage)
            ->setParameters($parameters);

        return $quoteValidationResponseTransfer
            ->addErrors($errorMessageTransfer)
            ->setIsSuccess(false);
    }
}
