<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantityStorage\Validator;

use Generated\Shared\Transfer\ItemValidationTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Client\ProductQuantityStorage\Dependency\Service\ProductQuantityStorageToUtilQuantityServiceInterface;
use Spryker\Client\ProductQuantityStorage\Resolver\ProductQuantityResolverInterface;
use Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReaderInterface;

class ProductQuantityItemValidator implements ProductQuantityItemValidatorInterface
{
    protected const WARNING_MESSAGE_QUANTITY_MIN_NOT_FULFILLED = 'product-quantity.warning.quantity.min.failed';
    protected const WARNING_MESSAGE_QUANTITY_MAX_NOT_FULFILLED = 'product-quantity.warning.quantity.max.failed';
    protected const WARNING_MESSAGE_QUANTITY_INTERVAL_NOT_FULFILLED = 'product-quantity.warning.quantity.interval.failed';
    protected const WARNING_MESSAGE_PARAM_MIN = '%min%';
    protected const WARNING_MESSAGE_PARAM_MAX = '%max%';
    protected const WARNING_MESSAGE_PARAM_STEP = '%step%';
    protected const MESSAGE_TYPE_WARNING = 'warning';

    /**
     * @var \Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReaderInterface
     */
    protected $productQuantityStorageReader;

    /**
     * @var \Spryker\Client\ProductQuantityStorage\Resolver\ProductQuantityResolverInterface
     */
    protected $productQuantityResolver;

    /**
     * @var \Spryker\Client\ProductQuantityStorage\Dependency\Service\ProductQuantityStorageToUtilQuantityServiceInterface
     */
    protected $utilQuantityService;

    /**
     * @param \Spryker\Client\ProductQuantityStorage\Storage\ProductQuantityStorageReaderInterface $productQuantityStorageReader
     * @param \Spryker\Client\ProductQuantityStorage\Resolver\ProductQuantityResolverInterface $productQuantityResolver
     * @param \Spryker\Client\ProductQuantityStorage\Dependency\Service\ProductQuantityStorageToUtilQuantityServiceInterface $utilQuantityService
     */
    public function __construct(
        ProductQuantityStorageReaderInterface $productQuantityStorageReader,
        ProductQuantityResolverInterface $productQuantityResolver,
        ProductQuantityStorageToUtilQuantityServiceInterface $utilQuantityService
    ) {
        $this->productQuantityStorageReader = $productQuantityStorageReader;
        $this->productQuantityResolver = $productQuantityResolver;
        $this->utilQuantityService = $utilQuantityService;
    }

    /**
     * @param float $firstQuantity
     * @param float $secondQuantity
     *
     * @return bool
     */
    protected function isQuantityEqual(float $firstQuantity, float $secondQuantity): bool
    {
        return $this->utilQuantityService->isQuantityEqual($firstQuantity, $secondQuantity);
    }

    /**
     * @param float $dividentQuantity
     * @param float $divisorQuantity
     * @param float $remainder
     *
     * @return bool
     */
    protected function isQuantityModuloEqual(float $dividentQuantity, float $divisorQuantity, float $remainder): bool
    {
        return $this->utilQuantityService->isQuantityModuloEqual($dividentQuantity, $divisorQuantity, $remainder);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemValidationTransfer $itemValidationTransfer
     *
     * @return \Generated\Shared\Transfer\ItemValidationTransfer
     */
    public function validate(ItemValidationTransfer $itemValidationTransfer): ItemValidationTransfer
    {
        $itemValidationTransfer->requireItem();

        if (!$itemValidationTransfer->getItem()->getId()) {
            return $itemValidationTransfer;
        }

        $itemTransfer = $itemValidationTransfer->getItem();

        $productQuantityTransfer = $this->productQuantityStorageReader
            ->findProductQuantityStorage($itemTransfer->getId());

        if (!$productQuantityTransfer) {
            return $itemValidationTransfer;
        }

        $min = $productQuantityTransfer->getQuantityMin();
        $max = $productQuantityTransfer->getQuantityMax();
        $interval = $productQuantityTransfer->getQuantityInterval();
        $quantity = $itemTransfer->requireQuantity()->getQuantity();

        if (!$this->isQuantityEqual($quantity, 0) && $quantity < $min) {
            $nearestQuantity = $this->productQuantityResolver->getNearestQuantity($itemTransfer->getId(), $quantity);

            $itemValidationTransfer->addMessage((new MessageTransfer())
                ->setType(static::MESSAGE_TYPE_WARNING)
                ->setValue(static::WARNING_MESSAGE_QUANTITY_MIN_NOT_FULFILLED)
                ->setParameters([static::WARNING_MESSAGE_PARAM_MIN => $nearestQuantity]));

            $itemValidationTransfer->getSuggestedValues()
                ->setQuantity($nearestQuantity);

            return $itemValidationTransfer;
        }

        $quantityMinusMin = $quantity - $min;

        if (!$this->isQuantityEqual($quantity, 0) && !$this->isQuantityModuloEqual($quantityMinusMin, $interval, 0)) {
            $nearestQuantity = $this->productQuantityResolver->getNearestQuantity($itemTransfer->getId(), $quantity);

            $itemValidationTransfer->addMessage((new MessageTransfer())
                ->setType(static::MESSAGE_TYPE_WARNING)
                ->setValue(static::WARNING_MESSAGE_QUANTITY_INTERVAL_NOT_FULFILLED)
                ->setParameters([static::WARNING_MESSAGE_PARAM_STEP => $interval]));

            $itemValidationTransfer->getSuggestedValues()
                ->setQuantity($nearestQuantity);

            return $itemValidationTransfer;
        }

        if ($max !== null && $quantity > $max) {
            $nearestQuantity = $this->productQuantityResolver->getNearestQuantity($itemTransfer->getId(), $quantity);

            $itemValidationTransfer->addMessage((new MessageTransfer())
                ->setType(static::MESSAGE_TYPE_WARNING)
                ->setValue(static::WARNING_MESSAGE_QUANTITY_MAX_NOT_FULFILLED)
                ->setParameters([static::WARNING_MESSAGE_PARAM_MAX => $nearestQuantity]));

            $itemValidationTransfer->getSuggestedValues()
                ->setQuantity($nearestQuantity);

            return $itemValidationTransfer;
        }

        return $itemValidationTransfer;
    }
}
