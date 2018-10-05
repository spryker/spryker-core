<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionCartConnector\Business\Validator;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToPriceFacadeInterface;
use Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToProductOptionFacadeInterface;

class ProductOptionValuePriceValidator implements ProductOptionValuePriceValidatorInterface
{
    public const MESSAGE_ERROR_PRODUCT_OPTION_VALUE_PRICE_EXISTS = 'product-cart.validation.error.product-option-value-price-exists';

    public const MESSAGE_PARAM_SKU = '%sku%';

    /**
     * @var \Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToProductOptionFacadeInterface
     */
    protected $productOptionFacade;

    /**
     * @var \Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToPriceFacadeInterface
     */
    protected $priceFacade;

    /**
     * @param \Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToProductOptionFacadeInterface $productOptionFacade
     * @param \Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToPriceFacadeInterface $priceFacade
     */
    public function __construct(
        ProductOptionCartConnectorToProductOptionFacadeInterface $productOptionFacade,
        ProductOptionCartConnectorToPriceFacadeInterface $priceFacade
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
        $responseTransfer->setIsSuccess(true);

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
                if ($this->hasProductOptionValueStorePrice($cartChangeTransfer, $productOptionTransfer)) {
                    continue;
                }

                $responseTransfer->addMessage($this->createMissingPriceViolationMessage($itemTransfer->getSku()));
                $responseTransfer->setIsSuccess(false);
            }
        }

        $responseTransfer->setIsSuccess($responseTransfer->getMessages()->count() === 0);

        return $responseTransfer;
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
        if ($priceMode === $this->priceFacade->getGrossPriceModeIdentifier()) {
            return $productOptionTransfer->getUnitGrossPrice();
        }

        return $productOptionTransfer->getUnitNetPrice();
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer
     *
     * @return bool
     */
    protected function hasProductOptionValueStorePrice(CartChangeTransfer $cartChangeTransfer, ProductOptionTransfer $productOptionTransfer)
    {
        $storePrice = $this->getProductOptionValueStorePrice($cartChangeTransfer, $productOptionTransfer);

        return $storePrice !== null;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createMissingPriceViolationMessage($sku)
    {
        $messageTransfer = (new MessageTransfer())
            ->setValue(static::MESSAGE_ERROR_PRODUCT_OPTION_VALUE_PRICE_EXISTS)
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
