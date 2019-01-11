<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Validator;

use Generated\Shared\Transfer\QuickOrderTransfer;
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
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function validateQuickOrder(QuickOrderTransfer $quickOrderTransfer): QuickOrderTransfer
    {
        foreach ($quickOrderTransfer->getItems() as $orderItemTransfer) {
            $productConcreteTransfer = $orderItemTransfer->getProductConcrete();

            if (!$productConcreteTransfer || !$productConcreteTransfer->getIdProductConcrete()) {
                continue;
            }

            $productQuantityTransfer = $this->productQuantityStorageReader
                ->findProductQuantityStorage($productConcreteTransfer->getIdProductConcrete());

            if (!$productQuantityTransfer) {
                continue;
            }

            $min = $productQuantityTransfer->getQuantityMin();
            $max = $productQuantityTransfer->getQuantityMax();
            $interval = $productQuantityTransfer->getQuantityInterval();
            $quantity = $orderItemTransfer->getQuantity();

            if ($quantity !== 0 && $quantity < $min) {
                $orderItemTransfer->addErrorMessages(static::ERROR_MESSAGE_QUANTITY_MIN_NOT_FULFILLED);
            }

            if ($quantity !== 0 && ($quantity - $min) % $interval !== 0) {
                $orderItemTransfer->addErrorMessages(static::ERROR_MESSAGE_QUANTITY_INTERVAL_NOT_FULFILLED);
            }

            if ($max !== null && $quantity > $max) {
                $orderItemTransfer->addErrorMessages(static::ERROR_MESSAGE_QUANTITY_MAX_NOT_FULFILLED);
            }
        }

        return $quickOrderTransfer;
    }
}
