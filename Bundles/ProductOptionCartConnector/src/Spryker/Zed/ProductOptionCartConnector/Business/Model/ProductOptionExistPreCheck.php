<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionCartConnector\Business\Model;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToProductOptionInterface;

class ProductOptionExistPreCheck implements ProductOptionExistPreCheckInterface
{
    const MESSAGE_PARAM_ID = '%id%';
    const MESSAGE_ERROR_PRODUCT_OPTION_EXISTS = 'cart.item.option.pre.check.validation.error.exists';

    /**
     * @var \Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToProductOptionInterface
     */
    protected $productOptionFacade;

    /**
     * @var bool[]
     */
    protected static $productOptionBySkuActiveCache = [];

    /**
     * @param \Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToProductOptionInterface $productOptionFacade
     */
    public function __construct(ProductOptionCartConnectorToProductOptionInterface $productOptionFacade)
    {
        $this->productOptionFacade = $productOptionFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkProductOptionExists(CartChangeTransfer $cartChangeTransfer)
    {
        $cartPreCheckResponseTransfer = new CartPreCheckResponseTransfer();

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (count($itemTransfer->getProductOptions()) === 0) {
                continue;
            }

            $this->validateProductOptions($itemTransfer, $cartPreCheckResponseTransfer);
        }

        return $this->setResponseSuccessful($cartPreCheckResponseTransfer);
    }

    /**
     * @param string $translationKey
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createViolationMessage($translationKey)
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue($translationKey);

        return $messageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function setResponseSuccessful(CartPreCheckResponseTransfer $cartPreCheckResponseTransfer)
    {
        $isSuccessful = count($cartPreCheckResponseTransfer->getMessages()) === 0;
        $cartPreCheckResponseTransfer->setIsSuccess($isSuccessful);

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer
     *
     * @return bool
     */
    public function existsProductOption(ProductOptionTransfer $productOptionTransfer)
    {
        $idProductOptionValue = $productOptionTransfer->getIdProductOptionValue();
        if (isset(static::$productOptionBySkuActiveCache[$idProductOptionValue])) {
            return static::$productOptionBySkuActiveCache[$idProductOptionValue];
        }

        static::$productOptionBySkuActiveCache[$idProductOptionValue] = $this->productOptionFacade->existsProductOptionValueByIdProductOptionValue($idProductOptionValue);

        return static::$productOptionBySkuActiveCache[$idProductOptionValue];
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
     *
     * @return void
     */
    protected function validateProductOptions(
        ItemTransfer $itemTransfer,
        CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
    ) {
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            if ($this->existsProductOption($productOptionTransfer)) {
                continue;
            }

            $message = $this->createViolationMessage(static::MESSAGE_ERROR_PRODUCT_OPTION_EXISTS);
            $message->setParameters([
                static::MESSAGE_PARAM_ID => $productOptionTransfer->getIdProductOptionValue(),
            ]);

            $cartPreCheckResponseTransfer->addMessage($message);
        }
    }
}
