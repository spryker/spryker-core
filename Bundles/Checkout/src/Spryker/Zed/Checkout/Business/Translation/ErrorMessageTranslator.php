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
    protected const KEY_PRODUCT_ERROR_MESSAGE = 'product.unavailable';

    protected const PARAMETER_ERROR_MESSAGE_PRODUCT_SKU = '%sku%';
    protected const PARAMETER_ERROR_MESSAGE_PRODUCT_BUNDLE_SKU_BUNDLE = '%bundleSku%';
    protected const PARAMETER_ERROR_MESSAGE_PRODUCT_BUNDLE_SKU_PRODUCT = '%productSku%';

    protected const KEY_GROUPED_PRODUCT_CHECKOUT_ERROR_MESSAGES = 'KEY_GROUPED_PRODUCT_CHECKOUT_ERROR_MESSAGES';
    protected const KEY_GROUPED_PRODUCT_BUNDLE_CHECKOUT_ERROR_MESSAGES = 'KEY_GROUPED_PRODUCT_BUNDLE_CHECKOUT_ERROR_MESSAGES';

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
        $processedProductSkus = [];

        $groupedCheckoutErrorMessages = $this->groupCheckoutErrorMessages($checkoutResponseTransfer);

        $groupedProductBundleCheckoutErrorMessages = $groupedCheckoutErrorMessages[static::KEY_GROUPED_PRODUCT_BUNDLE_CHECKOUT_ERROR_MESSAGES];
        $groupedProductCheckoutErrorMessages = $groupedCheckoutErrorMessages[static::KEY_GROUPED_PRODUCT_CHECKOUT_ERROR_MESSAGES];

        foreach ($groupedProductBundleCheckoutErrorMessages as $sku => $message) {
            $processedProductSkus[] = $sku;
            $translatedCheckoutErrorMessages[] = $message;
        }

        foreach ($groupedProductCheckoutErrorMessages as $sku => $message) {
            if (!in_array($sku, $processedProductSkus, true)) {
                $processedProductSkus[] = $sku;
                $translatedCheckoutErrorMessages[] = $message;
            }
        }

        return $this->mapTranslatedCheckoutErrorMessagesArrayToTransfer(
            array_unique($translatedCheckoutErrorMessages)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return array
     */
    protected function groupCheckoutErrorMessages(CheckoutResponseTransfer $checkoutResponseTransfer): array
    {
        $productErrors = [];
        $productBundleErrors = [];

        foreach ($checkoutResponseTransfer->getErrors() as $checkoutErrorTransfer) {
            if ($this->isProductBundleErrorMessage($checkoutErrorTransfer)) {
                $productBundleProductSku = $this->getProductBundleProductSkuFromErrorMessage($checkoutErrorTransfer);

                $productBundleErrors[$productBundleProductSku] = $this->translateSingleCheckoutErrorMessage(
                    $checkoutErrorTransfer
                );
                continue;
            }

            $productSku = $this->getProductSkuFromErrorMessage($checkoutErrorTransfer);
            $productErrors[$productSku] = $this->translateSingleCheckoutErrorMessage($checkoutErrorTransfer);
        }

        return [
            static::KEY_GROUPED_PRODUCT_CHECKOUT_ERROR_MESSAGES => $productErrors,
            static::KEY_GROUPED_PRODUCT_BUNDLE_CHECKOUT_ERROR_MESSAGES => $productBundleErrors,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutErrorTransfer $checkoutErrorTransfer
     *
     * @return bool
     */
    protected function isProductBundleErrorMessage(CheckoutErrorTransfer $checkoutErrorTransfer): bool
    {
        $checkoutErrorMessageParameters = $checkoutErrorTransfer->getParameters();
        ksort($checkoutErrorMessageParameters);

        $expectedCheckoutErrorMessageParameters = [
            static::PARAMETER_ERROR_MESSAGE_PRODUCT_BUNDLE_SKU_BUNDLE,
            static::PARAMETER_ERROR_MESSAGE_PRODUCT_BUNDLE_SKU_PRODUCT,
        ];

        return array_keys($checkoutErrorMessageParameters) === $expectedCheckoutErrorMessageParameters;
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutErrorTransfer $checkoutErrorTransfer
     *
     * @return string
     */
    protected function getProductSkuFromErrorMessage(CheckoutErrorTransfer $checkoutErrorTransfer): string
    {
        $checkoutErrorMessageParameters = $checkoutErrorTransfer->getParameters();

        return $checkoutErrorMessageParameters[static::PARAMETER_ERROR_MESSAGE_PRODUCT_SKU];
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutErrorTransfer $checkoutErrorTransfer
     *
     * @return string
     */
    protected function getProductBundleProductSkuFromErrorMessage(CheckoutErrorTransfer $checkoutErrorTransfer): string
    {
        $checkoutErrorMessageParameters = $checkoutErrorTransfer->getParameters();

        return $checkoutErrorMessageParameters[static::PARAMETER_ERROR_MESSAGE_PRODUCT_BUNDLE_SKU_PRODUCT];
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutErrorTransfer $checkoutErrorTransfer
     *
     * @return string
     */
    protected function translateSingleCheckoutErrorMessage(CheckoutErrorTransfer $checkoutErrorTransfer): string
    {
        if ($checkoutErrorTransfer->getParameters()) {
            return $this->translateSingleCheckoutErrorMessageWithParameters($checkoutErrorTransfer);
        }

        return $this->translateSingleCheckoutErrorMessageWithoutParameters($checkoutErrorTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutErrorTransfer $checkoutErrorTransfer
     *
     * @return string
     */
    protected function translateSingleCheckoutErrorMessageWithParameters(CheckoutErrorTransfer $checkoutErrorTransfer): string
    {
        return $this->glossaryFacade->translate(
            $checkoutErrorTransfer->getMessage(),
            $checkoutErrorTransfer->getParameters()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutErrorTransfer $checkoutErrorTransfer
     *
     * @return string
     */
    protected function translateSingleCheckoutErrorMessageWithoutParameters(CheckoutErrorTransfer $checkoutErrorTransfer): string
    {
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
