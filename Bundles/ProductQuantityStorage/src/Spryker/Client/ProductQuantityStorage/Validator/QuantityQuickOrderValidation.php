<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Validator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Spryker\Client\ProductQuantityStorage\Resolver\ProductQuantityResolverInterface;
use Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReaderInterface;

class QuantityQuickOrderValidation implements QuantityQuickOrderValidationInterface
{
    protected const WARNING_MESSAGE_QUANTITY_MIN_NOT_FULFILLED = 'product-quantity.warning.quantity.min.failed';
    protected const WARNING_MESSAGE_QUANTITY_MAX_NOT_FULFILLED = 'product-quantity.warning.quantity.max.failed';
    protected const WARNING_MESSAGE_QUANTITY_INTERVAL_NOT_FULFILLED = 'product-quantity.warning.quantity.interval.failed';
    protected const WARNING_MESSAGE_PARAM_MIN = '%min%';
    protected const WARNING_MESSAGE_PARAM_MAX = '%max%';
    protected const WARNING_MESSAGE_PARAM_STEP = '%step%';

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
            $nearestQuantity = $this->productQuantityResolver->getNearestQuantity($productConcreteTransfer->getIdProductConcrete(), $quantity);
            $quickOrderItemTransfer->setQuantity($nearestQuantity);
            $quickOrderItemTransfer->addWarningMessage((new MessageTransfer())
                ->setValue(static::WARNING_MESSAGE_QUANTITY_MIN_NOT_FULFILLED)
                ->setParameters([
                    static::WARNING_MESSAGE_PARAM_MIN => $nearestQuantity,
                ]));

            return $quickOrderItemTransfer;
        }

        if ($quantity !== 0 && ($quantity - $min) % $interval !== 0) {
            $nearestQuantity = $this->productQuantityResolver->getNearestQuantity($productConcreteTransfer->getIdProductConcrete(), $quantity);
            $quickOrderItemTransfer->setQuantity($nearestQuantity);
            $quickOrderItemTransfer->addWarningMessage((new MessageTransfer())
                ->setValue(static::WARNING_MESSAGE_QUANTITY_INTERVAL_NOT_FULFILLED)
                ->setParameters([
                    static::WARNING_MESSAGE_PARAM_STEP => $interval,
                ]));

            return $quickOrderItemTransfer;
        }

        if ($max !== null && $quantity > $max) {
            $nearestQuantity = $this->productQuantityResolver->getNearestQuantity($productConcreteTransfer->getIdProductConcrete(), $quantity);
            $quickOrderItemTransfer->setQuantity($nearestQuantity);
            $quickOrderItemTransfer->addWarningMessage((new MessageTransfer())
                ->setValue(static::WARNING_MESSAGE_QUANTITY_MAX_NOT_FULFILLED)
                ->setParameters([
                    static::WARNING_MESSAGE_PARAM_MAX => $nearestQuantity,
                ]));

            return $quickOrderItemTransfer;
        }

        return $quickOrderItemTransfer;
    }
}
