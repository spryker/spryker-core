<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantity\Business\Model\Validator;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\SpyProductQuantityEntityTransfer;
use Spryker\Zed\ProductQuantity\Business\Model\ProductQuantityReaderInterface;

class ProductQuantityRestrictionValidator implements ProductQuantityRestrictionValidatorInterface
{
    const ERROR_QUANTITY_MIN_NOT_FULFILLED = 'cart.pre.check.quantity.min.failed';
    const ERROR_QUANTITY_MAX_NOT_FULFILLED = 'cart.pre.check.quantity.max.failed';
    const ERROR_QUANTITY_INTERVAL_NOT_FULFILLED = 'cart.pre.check.quantity.interval.failed';

    const RESTRICTION_MIN = 'min';
    const RESTRICTION_MAX = 'max';
    const RESTRICTION_INTERVAL = 'interval';

    /**
     * @var \Spryker\Zed\ProductQuantity\Business\Model\ProductQuantityReaderInterface
     */
    protected $productQuantityReader;

    /**
     * @param \Spryker\Zed\ProductQuantity\Business\Model\ProductQuantityReaderInterface $productQuantityReader
     */
    public function __construct(ProductQuantityReaderInterface $productQuantityReader)
    {
        $this->productQuantityReader = $productQuantityReader;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateItemAddition(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $responseTransfer = new CartPreCheckResponseTransfer();

        $cartQuantityMap = $this->getItemAddCartQuantityMap($cartChangeTransfer);
        $productQuantityEntityMap = $this->getProductQuantityEntityMap($cartChangeTransfer);

        foreach ($cartQuantityMap as $productSku => $productQuantity) {
            $this->validateItem($productSku, $productQuantity, $productQuantityEntityMap[$productSku], $responseTransfer);
        }

        return $this->setResponseIsSuccess($responseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateItemRemoval(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $responseTransfer = new CartPreCheckResponseTransfer();

        $cartQuantityMap = $this->getItemRemoveCartQuantityMap($cartChangeTransfer);
        $productQuantityEntityMap = $this->getProductQuantityEntityMap($cartChangeTransfer);

        foreach ($cartQuantityMap as $productSku => $productQuantity) {
            $this->validateItem($productSku, $productQuantity, $productQuantityEntityMap[$productSku], $responseTransfer);
        }

        return $this->setResponseIsSuccess($responseTransfer);
    }

    /**
     * @param string $sku
     * @param int $quantity
     * @param \Generated\Shared\Transfer\SpyProductQuantityEntityTransfer $productQuantityEntity
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function validateItem(string $sku, int $quantity, SpyProductQuantityEntityTransfer $productQuantityEntity, CartPreCheckResponseTransfer $responseTransfer): void
    {
        $min = $productQuantityEntity->getQuantityMin();
        $max = $productQuantityEntity->getQuantityMax();
        $interval = $productQuantityEntity->getQuantityInterval();

        if ($quantity < $min) {
            $this->addViolationMessage(static::ERROR_QUANTITY_MIN_NOT_FULFILLED, $sku, $min, $responseTransfer);
        }

        if ($max !== null && $quantity > $max) {
            $this->addViolationMessage(static::ERROR_QUANTITY_MAX_NOT_FULFILLED, $sku, $max, $responseTransfer);
        }

        if (($quantity - $min) % $interval !== 0) {
            $this->addViolationMessage(static::ERROR_QUANTITY_INTERVAL_NOT_FULFILLED, $sku, $interval, $responseTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return int[] Keys are product SKUs, values are product quantities as 'quote.quantity + change.quantity'
     */
    protected function getItemAddCartQuantityMap(CartChangeTransfer $cartChangeTransfer): array
    {
        $quoteQuantityMap = $this->getQuoteQuantityMap($cartChangeTransfer);

        $cartQuantityMap = [];
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $productSku = $itemTransfer->getSku();
            $cartQuantityMap[$productSku] = $itemTransfer->getQuantity();

            if (isset($quoteQuantityMap[$productSku])) {
                $cartQuantityMap[$productSku] += $quoteQuantityMap[$productSku];
            }
        }

        return $cartQuantityMap;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return int[] Keys are product SKUs, values are product quantities as 'quote.quantity - change.quantity'
     */
    protected function getItemRemoveCartQuantityMap(CartChangeTransfer $cartChangeTransfer): array
    {
        $quoteQuantityMap = $this->getQuoteQuantityMap($cartChangeTransfer);

        $cartQuantityMap = [];
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $productSku = $itemTransfer->getSku();
            $cartQuantityMap[$productSku] = -$itemTransfer->getQuantity();

            if (isset($quoteQuantityMap[$productSku])) {
                $cartQuantityMap[$productSku] += $quoteQuantityMap[$productSku];
            }
        }

        return $cartQuantityMap;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return array
     */
    protected function getQuoteQuantityMap(CartChangeTransfer $cartChangeTransfer): array
    {
        $quoteQuantityMap = [];
        foreach ($cartChangeTransfer->getQuote()->getItems() as $itemTransfer) {
            $quoteQuantityMap[$itemTransfer->getSku()] = $itemTransfer->getQuantity();
        }

        return $quoteQuantityMap;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\SpyProductQuantityEntityTransfer[] Keys are product SKUs.
     */
    protected function getProductQuantityEntityMap(CartChangeTransfer $cartChangeTransfer): array
    {
        $skus = $this->getChangedSkus($cartChangeTransfer);
        $productQuantityEntities = $this->productQuantityReader->findProductQuantityEntitiesByProductSku($skus);

        $productQuantityEntityMap = $this->mapProductQuantityEntitiesBySku($productQuantityEntities);
        $productQuantityEntityMap = $this->replaceMissingSkus($productQuantityEntityMap, $skus);

        return $productQuantityEntityMap;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return string[]
     */
    protected function getChangedSkus(CartChangeTransfer $cartChangeTransfer): array
    {
        $skus = [];
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $skus[] = $itemTransfer->getSku();
        }

        return $skus;
    }

    /**
     * @return \Generated\Shared\Transfer\SpyProductQuantityEntityTransfer
     */
    protected function getDefaultProductQuantityEntity()
    {
        return (new SpyProductQuantityEntityTransfer())
            ->setQuantityInterval(1)
            ->setQuantityMin(1);
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductQuantityEntityTransfer[] $productQuantityEntityMap
     * @param string[] $requiredSkus
     *
     * @return \Generated\Shared\Transfer\SpyProductQuantityEntityTransfer[]
     */
    protected function replaceMissingSkus($productQuantityEntityMap, $requiredSkus)
    {
        $defaultProductQuantityEntity = $this->getDefaultProductQuantityEntity();

        foreach ($requiredSkus as $sku) {
            if (isset($productQuantityEntityMap[$sku])) {
                continue;
            }

            $productQuantityEntityMap[$sku] = $defaultProductQuantityEntity;
        }

        return $productQuantityEntityMap;
    }

    /**
     * @param \Generated\Shared\Transfer\SpyProductQuantityEntityTransfer[] $productQuantityEntities
     *
     * @return \Generated\Shared\Transfer\SpyProductQuantityEntityTransfer[]
     */
    protected function mapProductQuantityEntitiesBySku(array $productQuantityEntities)
    {
        $productQuantityEntityMap = [];
        foreach ($productQuantityEntities as $productQuantityEntity) {
            $productQuantityEntityMap[$productQuantityEntity->getProduct()->getSku()] = $productQuantityEntity;
        }

        return $productQuantityEntityMap;
    }

    /**
     * @param string $message
     * @param string $sku
     * @param int $restrictionValue
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function addViolationMessage(string $message, string $sku, int $restrictionValue, CartPreCheckResponseTransfer $responseTransfer): void
    {
        $responseTransfer->addMessage(
            (new MessageTransfer())
                ->setValue($message)
                ->setParameters(['sku' => $sku, 'restrictionValue' => $restrictionValue])
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function setResponseIsSuccess(CartPreCheckResponseTransfer $responseTransfer): CartPreCheckResponseTransfer
    {
        $isSuccessful = count($responseTransfer->getMessages()) === 0;
        $responseTransfer->setIsSuccess($isSuccessful);

        return $responseTransfer;
    }
}
