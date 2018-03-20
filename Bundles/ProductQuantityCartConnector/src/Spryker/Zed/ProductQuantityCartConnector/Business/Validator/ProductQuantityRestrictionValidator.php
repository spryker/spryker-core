<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantityCartConnector\Business\Validator;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Orm\Zed\Product\Persistence\SpyProductQuery;

class ProductQuantityRestrictionValidator implements ProductQuantityRestrictionValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function validateItems(CartChangeTransfer $cartChangeTransfer)
    {
        $responseTransfer = new CartPreCheckResponseTransfer();

        $changedProductConcreteSkus = [];
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $changedProductConcreteSkus[] = $itemTransfer->getSku();
        }

        $quoteProductQuantityMap = [];
        foreach ($cartChangeTransfer->getQuote()->getItems() as $itemTransfer) {
            $quoteProductQuantityMap[$itemTransfer->getSku()] = $itemTransfer->getQuantity();
        }

        $productQuantityRestrictionCollection = $this->getProductQuantityRestrictionCollection($changedProductConcreteSkus);

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            $productConcreteSku = $itemTransfer->getSku();
            $productConcreteQuoteQuantity = isset($quoteProductQuantityMap[$productConcreteSku]) ? $quoteProductQuantityMap[$productConcreteSku] : 0;
            $productConcreteQuantity = $itemTransfer->getQuantity() + $productConcreteQuoteQuantity;
            $min = $productQuantityRestrictionCollection[$productConcreteSku]['min'];
            $max = $productQuantityRestrictionCollection[$productConcreteSku]['max'];
            $interval = $productQuantityRestrictionCollection[$productConcreteSku]['interval'];

            if ($productConcreteQuantity < $min) {
                $this->createViolationMessage($itemTransfer, $responseTransfer);
            }
            if ($max !== null && $productConcreteQuantity > $max) {
                $this->createViolationMessage($itemTransfer, $responseTransfer);
            }
            if (!is_int($productConcreteQuantity / $interval)) {
                $this->createViolationMessage($itemTransfer, $responseTransfer);
            }
        }

        return $this->setResponseSuccessful($responseTransfer);
    }

    /**
     * @param string[] $productConcreteSkus
     *
     * @return array
     */
    protected function getProductQuantityRestrictionCollection(array $productConcreteSkus)
    {
        /** @var \Orm\Zed\Product\Persistence\SpyProduct[] $productCollection */
        $productCollection = SpyProductQuery::create()
            ->filterBySku_In($productConcreteSkus)
            ->leftJoinWithSpyProductQuantity()
            ->find()
            ->getArrayCopy();

        $productQuantityRestrictionCollection = [];
        foreach ($productCollection as $productEntity) {
            /** @var \Orm\Zed\ProductQuantity\Persistence\SpyProductQuantity $productQuantityEntity */
            $productQuantityEntity = $productEntity->getSpyProductQuantities()->getFirst();

            $restriction = ['min' => 1, 'max' => null, 'interval' => 1];
            if ($productQuantityEntity !== null) {
                $restriction = [
                    'min' => $productQuantityEntity->getQuantityMin() === null ? 1 : $productQuantityEntity->getQuantityMin(),
                    'max' => $productQuantityEntity->getQuantityMax(),
                    'interval' => $productQuantityEntity->getQuantityInterval() === null ? 1 : $productQuantityEntity->getQuantityInterval(),
                ];
            }

            $productQuantityRestrictionCollection[$productEntity->getSku()] = $restriction;
        }

        return $productQuantityRestrictionCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $responseTransfer
     *
     * @return void
     */
    protected function createViolationMessage(ItemTransfer $itemTransfer, CartPreCheckResponseTransfer $responseTransfer)
    {
        $message = (new MessageTransfer())
            ->setValue('cart.error.quantity')
            ->setParameters([]);

        $responseTransfer->addMessage($message);
    }

    /**
     * @param \Generated\Shared\Transfer\CartPreCheckResponseTransfer $responseTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    protected function setResponseSuccessful(CartPreCheckResponseTransfer $responseTransfer)
    {
        $isSuccessful = count($responseTransfer->getMessages()) === 0;
        $responseTransfer->setIsSuccess($isSuccessful);

        return $responseTransfer;
    }
}
