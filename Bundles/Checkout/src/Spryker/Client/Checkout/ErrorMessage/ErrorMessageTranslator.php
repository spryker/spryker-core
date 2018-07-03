<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Checkout\ErrorMessage;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\TranslatedCheckoutErrorMessagesTransfer;
use Spryker\Client\Checkout\Dependency\Client\CheckoutToGlossaryStorageClientInterface;
use Spryker\Client\Checkout\Dependency\Client\CheckoutToLocaleClientInterface;

class ErrorMessageTranslator implements ErrorMessageTranslatorInterface
{
    protected const KEY_PRODUCT_ERROR_MESSAGE = 'product.unavailable';

    protected const PARAMETER_ERROR_MESSAGE_PRODUCT_SKU = '%sku%';
    protected const PARAMETER_ERROR_MESSAGE_PRODUCT_BUNDLE_SKU_BUNDLE = '%bundleSku%';
    protected const PARAMETER_ERROR_MESSAGE_PRODUCT_BUNDLE_SKU_PRODUCT = '%productSku%';

    protected const KEY_GROUPED_PRODUCT_CHECKOUT_ERROR_MESSAGES = 'KEY_GROUPED_PRODUCT_CHECKOUT_ERROR_MESSAGES';
    protected const KEY_GROUPED_PRODUCT_BUNDLE_CHECKOUT_ERROR_MESSAGES = 'KEY_GROUPED_PRODUCT_BUNDLE_CHECKOUT_ERROR_MESSAGES';

    /**
     * @var \Spryker\Client\Checkout\Dependency\Client\CheckoutToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @var \Spryker\Client\Checkout\Dependency\Client\CheckoutToLocaleClientInterface
     */
    protected $localeClient;

    /**
     * @param \Spryker\Client\Checkout\Dependency\Client\CheckoutToGlossaryStorageClientInterface $glossaryStorageClient
     * @param \Spryker\Client\Checkout\Dependency\Client\CheckoutToLocaleClientInterface $localeClient
     */
    public function __construct(
        CheckoutToGlossaryStorageClientInterface $glossaryStorageClient,
        CheckoutToLocaleClientInterface $localeClient
    ) {
        $this->glossaryStorageClient = $glossaryStorageClient;
        $this->localeClient = $localeClient;
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
        return $this->glossaryStorageClient->translate(
            $checkoutErrorTransfer->getMessage(),
            $this->getCurrentLocale(),
            $checkoutErrorTransfer->getParameters() ?: []
        );
    }

    /**
     * @return string
     */
    protected function getCurrentLocale(): string
    {
        return $this->localeClient->getCurrentLocale();
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
