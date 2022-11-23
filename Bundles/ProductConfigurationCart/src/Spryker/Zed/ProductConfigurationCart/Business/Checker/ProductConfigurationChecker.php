<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationCart\Business\Checker;

use Generated\Shared\Transfer\CheckoutErrorTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\ProductConfigurationCollectionTransfer;
use Generated\Shared\Transfer\ProductConfigurationConditionsTransfer;
use Generated\Shared\Transfer\ProductConfigurationCriteriaTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductConfigurationCart\Dependency\Facade\ProductConfigurationCartToProductConfigurationFacadeInterface;

class ProductConfigurationChecker implements ProductConfigurationCheckerInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PRODUCT_CONFIGURATION_IS_NOT_COMPLETE = 'product_configuration.checkout.validation.error.is_not_complete';

    /**
     * @var \Spryker\Zed\ProductConfigurationCart\Dependency\Facade\ProductConfigurationCartToProductConfigurationFacadeInterface
     */
    protected $productConfigurationFacade;

    /**
     * @param \Spryker\Zed\ProductConfigurationCart\Dependency\Facade\ProductConfigurationCartToProductConfigurationFacadeInterface $productConfigurationFacade
     */
    public function __construct(ProductConfigurationCartToProductConfigurationFacadeInterface $productConfigurationFacade)
    {
        $this->productConfigurationFacade = $productConfigurationFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     *
     * @return bool
     */
    public function isQuoteProductConfigurationValid(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponseTransfer): bool
    {
        $indexedProductConfigurations = $this->getProductConfigurationsIndexedBySku($quoteTransfer);

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $productConfigurationInstanceTransfer = $itemTransfer->getProductConfigurationInstance();

            if (!$productConfigurationInstanceTransfer) {
                continue;
            }

            $productConfigurationTransfer = $indexedProductConfigurations[$itemTransfer->getSkuOrFail()] ?? null;

            if (!$productConfigurationTransfer) {
                $this->addCheckoutError($checkoutResponseTransfer, static::GLOSSARY_KEY_PRODUCT_CONFIGURATION_IS_NOT_COMPLETE);

                return false;
            }

            if (!$productConfigurationInstanceTransfer->getIsComplete()) {
                $this->addCheckoutError($checkoutResponseTransfer, static::GLOSSARY_KEY_PRODUCT_CONFIGURATION_IS_NOT_COMPLETE);

                return false;
            }
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<string, \Generated\Shared\Transfer\ProductConfigurationTransfer>
     */
    protected function getProductConfigurationsIndexedBySku(QuoteTransfer $quoteTransfer): array
    {
        $indexedProductConfigurations = [];
        $skusUniqueFromQuoteTransfer = $this->extractUniqueSkusFromQuote($quoteTransfer);
        $productConfigurationCollectionTransfer = $this->getProductConfigurationCollection($skusUniqueFromQuoteTransfer);

        foreach ($productConfigurationCollectionTransfer->getProductConfigurations() as $productConfigurationTransfer) {
            $indexedProductConfigurations[$productConfigurationTransfer->getSkuOrFail()] = $productConfigurationTransfer;
        }

        return $indexedProductConfigurations;
    }

    /**
     * @param array<string> $skus
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationCollectionTransfer
     */
    protected function getProductConfigurationCollection(array $skus): ProductConfigurationCollectionTransfer
    {
        $productConfigurationConditionsTransfer = (new ProductConfigurationConditionsTransfer())->setSkus($skus);
        $productConfigurationCriteriaTransfer = (new ProductConfigurationCriteriaTransfer())->setProductConfigurationConditions($productConfigurationConditionsTransfer);

        return $this->productConfigurationFacade->getProductConfigurationCollection($productConfigurationCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return array<string>
     */
    protected function extractUniqueSkusFromQuote(QuoteTransfer $quoteTransfer): array
    {
        $skus = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $skus[] = $itemTransfer->getSkuOrFail();
        }

        return array_unique($skus);
    }

    /**
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponseTransfer
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\CheckoutResponseTransfer
     */
    protected function addCheckoutError(CheckoutResponseTransfer $checkoutResponseTransfer, string $message): CheckoutResponseTransfer
    {
        $checkoutResponseTransfer
            ->addError((new CheckoutErrorTransfer())->setMessage($message))
            ->setIsSuccess(false);

        return $checkoutResponseTransfer;
    }
}
