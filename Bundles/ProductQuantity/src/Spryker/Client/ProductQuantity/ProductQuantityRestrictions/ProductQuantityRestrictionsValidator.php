<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductQuantity\ProductQuantityRestrictions;

use Generated\Shared\Transfer\ProductQuantityTransfer;
use Generated\Shared\Transfer\ProductQuantityValidationResponseTransfer;
use Spryker\Client\ProductQuantity\Dependency\Client\ProductQuantityToGlossaryStorageClientInterface;
use Spryker\Client\ProductQuantity\Dependency\Client\ProductQuantityToLocaleClientInterface;

class ProductQuantityRestrictionsValidator implements ProductQuantityRestrictionsValidatorInterface
{
    protected const ERROR_QUANTITY_MIN_NOT_FULFILLED = 'product-quantity.errors.quantity.min.failed';
    protected const ERROR_QUANTITY_MAX_NOT_FULFILLED = 'product-quantity.errors.quantity.max.failed';
    protected const ERROR_QUANTITY_INTERVAL_NOT_FULFILLED = 'product-quantity.errors.quantity.interval.failed';

    /**
     * @var \Spryker\Client\ProductQuantity\Dependency\Client\ProductQuantityToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @var \Spryker\Client\ProductQuantity\Dependency\Client\ProductQuantityToLocaleClientInterface
     */
    protected $localeClient;

    /**
     * @param \Spryker\Client\ProductQuantity\Dependency\Client\ProductQuantityToGlossaryStorageClientInterface $glossaryStorageClient
     * @param \Spryker\Client\ProductQuantity\Dependency\Client\ProductQuantityToLocaleClientInterface $localeClient
     */
    public function __construct(
        ProductQuantityToGlossaryStorageClientInterface $glossaryStorageClient,
        ProductQuantityToLocaleClientInterface $localeClient
    ) {
        $this->glossaryStorageClient = $glossaryStorageClient;
        $this->localeClient = $localeClient;
    }

    /**
     * @param int $quantity
     * @param \Generated\Shared\Transfer\ProductQuantityTransfer $productQuantityTransfer
     *
     * @return \Generated\Shared\Transfer\ProductQuantityValidationResponseTransfer
     */
    public function validateProductQuantityRestrictions(int $quantity, ProductQuantityTransfer $productQuantityTransfer): ProductQuantityValidationResponseTransfer
    {
        $quantityMin = $productQuantityTransfer->getQuantityMin();
        $quantityMax = $productQuantityTransfer->getQuantityMax();
        $quantityInterval = $productQuantityTransfer->getQuantityInterval();

        $productQuantityValidationResponseTransfer = new ProductQuantityValidationResponseTransfer();

        $this->validateQuantityMin($quantity, $quantityMin, $productQuantityValidationResponseTransfer);
        $this->validateQuantityInterval($quantity, $quantityMin, $quantityInterval, $productQuantityValidationResponseTransfer);
        $this->validateQuantityMax($quantity, $quantityMax, $productQuantityValidationResponseTransfer);

        $productQuantityValidationResponseTransfer->setIsValid(
            !$productQuantityValidationResponseTransfer->getMessages()
        );

        return $productQuantityValidationResponseTransfer;
    }

    /**
     * @param int $quantity
     * @param int $quantityMin
     * @param \Generated\Shared\Transfer\ProductQuantityValidationResponseTransfer $productQuantityValidationResponseTransfer
     *
     * @return void
     */
    protected function validateQuantityMin(int $quantity, int $quantityMin, ProductQuantityValidationResponseTransfer $productQuantityValidationResponseTransfer): void
    {
        if ($quantity !== 0 && $quantity < $quantityMin) {
            $this->addViolationMessage(
                static::ERROR_QUANTITY_MIN_NOT_FULFILLED,
                [$quantity, $quantityMin],
                $productQuantityValidationResponseTransfer
            );
        }
    }

    /**
     * @param int $quantity
     * @param int|null $quantityMin
     * @param int $quantityInterval
     * @param \Generated\Shared\Transfer\ProductQuantityValidationResponseTransfer $productQuantityValidationResponseTransfer
     *
     * @return void
     */
    protected function validateQuantityInterval(int $quantity, ?int $quantityMin, int $quantityInterval, ProductQuantityValidationResponseTransfer $productQuantityValidationResponseTransfer): void
    {
        if ($quantity !== 0 && ($quantity - $quantityMin) % $quantityInterval !== 0) {
            $this->addViolationMessage(
                static::ERROR_QUANTITY_INTERVAL_NOT_FULFILLED,
                [$quantity, $quantityInterval],
                $productQuantityValidationResponseTransfer
            );
        }
    }

    /**
     * @param int $quantity
     * @param int|null $quantityMax
     * @param \Generated\Shared\Transfer\ProductQuantityValidationResponseTransfer $productQuantityValidationResponseTransfer
     *
     * @return void
     */
    protected function validateQuantityMax(int $quantity, ?int $quantityMax, ProductQuantityValidationResponseTransfer $productQuantityValidationResponseTransfer): void
    {
        if ($quantityMax !== null && $quantity > $quantityMax) {
            $this->addViolationMessage(
                static::ERROR_QUANTITY_MAX_NOT_FULFILLED,
                [$quantity, $quantityMax],
                $productQuantityValidationResponseTransfer
            );
        }
    }

    /**
     * @param string $message
     * @param array $replacements
     * @param \Generated\Shared\Transfer\ProductQuantityValidationResponseTransfer $productQuantityValidationResponseTransfer
     *
     * @return void
     */
    protected function addViolationMessage(string $message, array $replacements, ProductQuantityValidationResponseTransfer $productQuantityValidationResponseTransfer): void
    {
        $productQuantityValidationResponseTransfer->addMessage(
            sprintf(
                $this->glossaryStorageClient->translate($message, $this->localeClient->getCurrentLocale()),
                ...$replacements
            )
        );
    }
}
