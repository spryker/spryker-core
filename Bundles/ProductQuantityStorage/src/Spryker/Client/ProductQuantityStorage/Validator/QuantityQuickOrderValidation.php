<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Validator;

use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReaderInterface;

class QuantityQuickOrderValidation implements QuantityQuickOrderValidationInterface
{
    protected const ERROR_MESSAGE_QUANTITY_MIN_NOT_FULFILLED = 'quick-order.upload-order.errors.upload-order-invalid-quantity-min';
    protected const ERROR_MESSAGE_QUANTITY_MAX_NOT_FULFILLED = 'quick-order.upload-order.errors.upload-order-invalid-quantity-max';
    protected const ERROR_MESSAGE_QUANTITY_INTERVAL_NOT_FULFILLED = 'quick-order.upload-order.errors.upload-order-invalid-quantity-interval';

    /**
     * @var \Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReaderInterface
     */
    protected $productQuantityStorageReader;

    /**
     * @param \Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReaderInterface $productQuantityStorageReader
     */
    public function __construct(ProductQuantityStorageReaderInterface $productQuantityStorageReader)
    {
        $this->productQuantityStorageReader = $productQuantityStorageReader;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer
     */
    public function validateQuickOrderItem(QuickOrderItemTransfer $quickOrderItemTransfer): QuickOrderItemTransfer
    {
        $productConcreteTransfer = $quickOrderItemTransfer->getProductConcrete();

        if (!$productConcreteTransfer || !$productConcreteTransfer->getIdProductConcrete()) {
            return $quickOrderItemTransfer;
        }

        $productQuantityTransfer = $this->productQuantityStorageReader
            ->findProductQuantityStorage($productConcreteTransfer->getIdProductConcrete());

        if (!$productQuantityTransfer) {
            return $quickOrderItemTransfer;
        }

        $min = $productQuantityTransfer->getQuantityMin();
        $max = $productQuantityTransfer->getQuantityMax();
        $interval = $productQuantityTransfer->getQuantityInterval();
        $quantity = $quickOrderItemTransfer->getQuantity();

        if ($quantity !== 0 && $quantity < $min) {
            $quickOrderItemTransfer->addErrorMessages(static::ERROR_MESSAGE_QUANTITY_MIN_NOT_FULFILLED);
        }

        if ($quantity !== 0 && ($quantity - $min) % $interval !== 0) {
            $quickOrderItemTransfer->addErrorMessages(static::ERROR_MESSAGE_QUANTITY_INTERVAL_NOT_FULFILLED);
        }

        if ($max !== null && $quantity > $max) {
            $quickOrderItemTransfer->addErrorMessages(static::ERROR_MESSAGE_QUANTITY_MAX_NOT_FULFILLED);
        }

        return $quickOrderItemTransfer;
    }
}
