<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Validator;

use Generated\Shared\Transfer\CartChangeItemValidationResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Client\ProductQuantityStorage\Resolver\ProductQuantityResolverInterface;
use Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReaderInterface;

class QuantityCartChangeItemValidator implements QuantityCartChangeItemValidatorInterface
{
    protected const WARNING_MESSAGE_QUANTITY_MIN_NOT_FULFILLED = 'product-quantity.warning.quantity.min.failed';
    protected const WARNING_MESSAGE_QUANTITY_MAX_NOT_FULFILLED = 'product-quantity.warning.quantity.max.failed';
    protected const WARNING_MESSAGE_QUANTITY_INTERVAL_NOT_FULFILLED = 'product-quantity.warning.quantity.interval.failed';
    protected const WARNING_MESSAGE_PARAM_MIN = '%min%';
    protected const WARNING_MESSAGE_PARAM_MAX = '%max%';
    protected const WARNING_MESSAGE_PARAM_STEP = '%step%';
    protected const FIELD_QUANTITY = 'quantity';

    /**
     * @var \Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReaderInterface
     */
    protected $productQuantityStorageReader;

    /**
     * @var \Spryker\Client\ProductQuantityStorage\Resolver\ProductQuantityResolverInterface
     */
    protected $productQuantityResolver;

    /**
     * @param \Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReaderInterface $productQuantityStorageReader
     * @param \Spryker\Client\ProductQuantityStorage\Resolver\ProductQuantityResolverInterface $productQuantityResolver
     */
    public function __construct(
        ProductQuantityStorageReaderInterface $productQuantityStorageReader,
        ProductQuantityResolverInterface $productQuantityResolver
    ) {
        $this->productQuantityStorageReader = $productQuantityStorageReader;
        $this->productQuantityResolver = $productQuantityResolver;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeItemValidationResponseTransfer
     */
    public function validateCartChangeItem(ItemTransfer $itemTransfer): CartChangeItemValidationResponseTransfer
    {
        $quickOrderValidationResponseTransfer = new CartChangeItemValidationResponseTransfer();
        $productConcreteTransfer = $itemTransfer->getProductConcrete();

        if (!$productConcreteTransfer || !$productConcreteTransfer->getIdProductConcrete()) {
            return $quickOrderValidationResponseTransfer;
        }

        $productQuantityTransfer = $this->productQuantityStorageReader
            ->findProductQuantityStorage($productConcreteTransfer->getIdProductConcrete());

        if (!$productQuantityTransfer) {
            return $quickOrderValidationResponseTransfer;
        }

        $min = $productQuantityTransfer->getQuantityMin();
        $max = $productQuantityTransfer->getQuantityMax();
        $interval = $productQuantityTransfer->getQuantityInterval();
        $quantity = $itemTransfer->getQuantity();

        if ($quantity !== 0 && $quantity < $min) {
            $nearestQuantity = $this->productQuantityResolver->getNearestQuantity($productConcreteTransfer->getIdProductConcrete(), $quantity);

            $quickOrderValidationResponseTransfer = $this->addWarningMessage(
                $quickOrderValidationResponseTransfer,
                static::WARNING_MESSAGE_QUANTITY_MIN_NOT_FULFILLED,
                [static::WARNING_MESSAGE_PARAM_MIN => $nearestQuantity],
                [static::FIELD_QUANTITY => $nearestQuantity]
            );

            return $quickOrderValidationResponseTransfer;
        }

        if ($quantity !== 0 && ($quantity - $min) % $interval !== 0) {
            $nearestQuantity = $this->productQuantityResolver->getNearestQuantity($productConcreteTransfer->getIdProductConcrete(), $quantity);

            $quickOrderValidationResponseTransfer = $this->addWarningMessage(
                $quickOrderValidationResponseTransfer,
                static::WARNING_MESSAGE_QUANTITY_INTERVAL_NOT_FULFILLED,
                [static::WARNING_MESSAGE_PARAM_STEP => $interval],
                [static::FIELD_QUANTITY => $nearestQuantity]
            );

            return $quickOrderValidationResponseTransfer;
        }

        if ($max !== null && $quantity > $max) {
            $nearestQuantity = $this->productQuantityResolver->getNearestQuantity($productConcreteTransfer->getIdProductConcrete(), $quantity);

            $quickOrderValidationResponseTransfer = $this->addWarningMessage(
                $quickOrderValidationResponseTransfer,
                static::WARNING_MESSAGE_QUANTITY_MAX_NOT_FULFILLED,
                [static::WARNING_MESSAGE_PARAM_MAX => $nearestQuantity],
                [static::FIELD_QUANTITY => $nearestQuantity]
            );

            return $quickOrderValidationResponseTransfer;
        }

        return $quickOrderValidationResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeItemValidationResponseTransfer $quickOrderValidationResponseTransfer
     * @param string $messageTransferValue
     * @param array $messageTransferParameters
     * @param array $messageTransferCorrectValues
     *
     * @return \Generated\Shared\Transfer\CartChangeItemValidationResponseTransfer
     */
    protected function addWarningMessage(
        CartChangeItemValidationResponseTransfer $quickOrderValidationResponseTransfer,
        string $messageTransferValue,
        array $messageTransferParameters,
        array $messageTransferCorrectValues
    ): CartChangeItemValidationResponseTransfer {
        $quickOrderValidationResponseTransfer->addWarningMessage((new MessageTransfer())
            ->setValue($messageTransferValue)
            ->setParameters($messageTransferParameters));

        $quickOrderValidationResponseTransfer->addCorrectValue($messageTransferCorrectValues);

        return $quickOrderValidationResponseTransfer;
    }
}
