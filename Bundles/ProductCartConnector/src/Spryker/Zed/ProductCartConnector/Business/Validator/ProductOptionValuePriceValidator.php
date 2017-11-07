<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCartConnector\Business\Validator;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToPriceFacadeInterface;
use Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductOptionFacadeInterface;

class ProductOptionValuePriceValidator implements ProductOptionValuePriceValidatorInterface
{
    const MESSAGE_ERROR_PRODUCT_OPTION_VALUE_PRICE_EXISTS = 'product-cart.validation.error.product-option-value-price-exists';

    const MESSAGE_PARAM_SKU = 'sku';

    /**
     * @var \Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductOptionFacadeInterface
     */
    protected $productOptionFacade;

    /**
     * @var \Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToPriceFacadeInterface
     */
    protected $priceFacade;

    /**
     * @param \Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToProductOptionFacadeInterface $productOptionFacade
     * @param \Spryker\Zed\ProductCartConnector\Dependency\Facade\ProductCartConnectorToPriceFacadeInterface $priceFacade
     */
    public function __construct(
        ProductCartConnectorToProductOptionFacadeInterface $productOptionFacade,
        ProductCartConnectorToPriceFacadeInterface $priceFacade
    ) {
        $this->productOptionFacade = $productOptionFacade;
        $this->priceFacade = $priceFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateProductOptionValuePrices(CartChangeTransfer $cartChangeTransfer)
    {
        $responseTransfer = new CartPreCheckResponseTransfer();

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                $this->validateProductOptionValuePrice(
                    $cartChangeTransfer,
                    $itemTransfer,
                    $productOptionTransfer,
                    $responseTransfer
                );
            }
        }

        $responseTransfer->setIsSuccess($responseTransfer->getMessages()->count() === 0);

        return $responseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function validateProductOptionValuePrice(
        CartChangeTransfer $cartChangeTransfer,
        ItemTransfer $itemTransfer,
        ProductOptionTransfer $productOptionTransfer,
        CartPreCheckResponseTransfer $responseTransfer
    ) {
        $storePrice = $this->getProductOptionValueStorePrice($cartChangeTransfer, $productOptionTransfer);
        if (isset($storePrice)) {
            return;
        }

        $responseTransfer->addMessage(
            $this->createViolationMessage(
                static::MESSAGE_ERROR_PRODUCT_OPTION_VALUE_PRICE_EXISTS,
                $itemTransfer->getSku()
            )
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer
     *
     * @return int|null
     */
    protected function getProductOptionValueStorePrice(CartChangeTransfer $cartChangeTransfer, ProductOptionTransfer $productOptionTransfer)
    {
        $productOptionTransfer = $this->productOptionFacade->getProductOptionValueById(
            $productOptionTransfer->getIdProductOptionValue()
        );

        $priceMode = $this->getPriceMode($cartChangeTransfer);
        if ($priceMode === $this->priceFacade->getNetPriceModeIdentifier()) {
            return $productOptionTransfer->getUnitNetPrice();
        }

        return $productOptionTransfer->getUnitGrossPrice();
    }

    /**
     * @param string $translationKey
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createViolationMessage($translationKey, $sku)
    {
        $messageTransfer = (new MessageTransfer())
            ->setValue($translationKey)
            ->setParameters([static::MESSAGE_PARAM_SKU => $sku]);

        return $messageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return string
     */
    protected function getPriceMode(CartChangeTransfer $cartChangeTransfer)
    {
        $priceMode = $cartChangeTransfer->getQuote()->getPriceMode();
        if ($priceMode !== null) {
            return $priceMode;
        }

        return $this->priceFacade->getDefaultPriceMode();
    }
}
