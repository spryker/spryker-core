<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Checkout\Business\Translation;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\TranslatedCheckoutErrorMessagesTransfer;
use Spryker\Zed\Checkout\Dependency\Facade\CheckoutToGlossaryFacadeInterface;

class ErrorMessageTranslator implements ErrorMessageTranslatorInterface
{
    /**
     * @var \Spryker\Zed\Checkout\Dependency\Facade\CheckoutToGlossaryFacadeInterface
     */
    protected $glossaryFacade;

    /**
     * @param \Spryker\Zed\Checkout\Dependency\Facade\CheckoutToGlossaryFacadeInterface $glossaryFacade
     */
    public function __construct(CheckoutToGlossaryFacadeInterface $glossaryFacade)
    {
        $this->glossaryFacade = $glossaryFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return \Generated\Shared\Transfer\TranslatedCheckoutErrorMessagesTransfer
     */
    public function translateCheckoutErrorMessages(CheckoutResponseTransfer $checkoutResponseTransfer): TranslatedCheckoutErrorMessagesTransfer
    {
        $translatedCheckoutErrorMessages = [];

        foreach ($checkoutResponseTransfer->getErrors() as $checkoutErrorTransfer) {
            $translatedCheckoutErrorMessages[] = $this->translateSingleCheckoutErrorMessage($checkoutErrorTransfer);
        }

        return $this->mapTranslatedCheckoutErrorMessagesArrayToTransfer($translatedCheckoutErrorMessages);
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutErrorTransfer $checkoutErrorTransfer
     *
     * @return string
     */
    protected function translateSingleCheckoutErrorMessage(CheckoutErrorTransfer $checkoutErrorTransfer): string
    {
        if ($checkoutErrorTransfer->getParameters()) {
            return $this->glossaryFacade->translate(
                $checkoutErrorTransfer->getMessage(),
                $checkoutErrorTransfer->getParameters()
            );
        }

        return $this->glossaryFacade->translate(
            $checkoutErrorTransfer->getMessage()
        );
    }

    /**
     * @param array $translatedCheckoutErrorMessages
     *
     * @return \Generated\Shared\Transfer\TranslatedCheckoutErrorMessagesTransfer
     */
    protected function mapTranslatedCheckoutErrorMessagesArrayToTransfer(array $translatedCheckoutErrorMessages): TranslatedCheckoutErrorMessagesTransfer
    {
        return (new TranslatedCheckoutErrorMessagesTransfer())
            ->setErrorMessages($translatedCheckoutErrorMessages);
    }
}
