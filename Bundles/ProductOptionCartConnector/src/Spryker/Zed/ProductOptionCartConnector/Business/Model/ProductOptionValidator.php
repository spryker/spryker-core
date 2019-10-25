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
use Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToProductOptionFacadeInterface;

class ProductOptionValidator implements ProductOptionValidatorInterface
{
    protected const MESSAGE_PARAM_NAME = '%name%';
    protected const MESSAGE_ERROR_PRODUCT_OPTION_EXISTS = 'cart.item.option.pre.check.validation.error.exists';

    /**
     * @var \Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToProductOptionFacadeInterface
     */
    protected $productOptionFacade;

    /**
     * @var bool[]
     */
    protected static $idProductOptionCache = [];

    /**
     * @param \Spryker\Zed\ProductOptionCartConnector\Dependency\Facade\ProductOptionCartConnectorToProductOptionFacadeInterface $productOptionFacade
     */
    public function __construct(ProductOptionCartConnectorToProductOptionFacadeInterface $productOptionFacade)
    {
        $this->productOptionFacade = $productOptionFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkProductOptionExistence(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $cartPreCheckResponseTransfer = new CartPreCheckResponseTransfer();

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (count($itemTransfer->getProductOptions()) === 0) {
                continue;
            }

            $cartPreCheckResponseTransfer = $this->validateProductOptions($itemTransfer, $cartPreCheckResponseTransfer);
        }

        return $this->setResponseIsSuccess($cartPreCheckResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOptionTransfer $productOptionTransfer
     *
     * @return bool
     */
    public function productOptionExists(ProductOptionTransfer $productOptionTransfer): bool
    {
        $idProductOptionValue = (int)$productOptionTransfer->getIdProductOptionValue();
        if (isset(static::$idProductOptionCache[$idProductOptionValue])) {
            return static::$idProductOptionCache[$idProductOptionValue];
        }

        static::$idProductOptionCache[$idProductOptionValue] = $this->productOptionFacade->checkProductOptionGroupExistenceByProductOptionValueId($idProductOptionValue);

        return static::$idProductOptionCache[$idProductOptionValue];
    }

    /**
     * @param string $translationKey
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createViolationMessage(string $translationKey): MessageTransfer
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
    protected function setResponseIsSuccess(CartPreCheckResponseTransfer $cartPreCheckResponseTransfer): CartPreCheckResponseTransfer
    {
        $isSuccessful = count($cartPreCheckResponseTransfer->getMessages()) === 0;
        $cartPreCheckResponseTransfer->setIsSuccess($isSuccessful);

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function validateProductOptions(
        ItemTransfer $itemTransfer,
        CartPreCheckResponseTransfer $cartPreCheckResponseTransfer
    ): CartPreCheckResponseTransfer {
        foreach ($itemTransfer->getProductOptions() as $productOptionTransfer) {
            if ($this->productOptionExists($productOptionTransfer)) {
                continue;
            }

            $message = $this->createViolationMessage(static::MESSAGE_ERROR_PRODUCT_OPTION_EXISTS);
            $message->setParameters([
                static::MESSAGE_PARAM_NAME => $itemTransfer->getName(),
            ]);

            $cartPreCheckResponseTransfer->addMessage($message);
        }

        return $cartPreCheckResponseTransfer;
    }
}
