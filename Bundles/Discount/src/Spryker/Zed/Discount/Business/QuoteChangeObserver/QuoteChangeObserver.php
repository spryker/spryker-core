<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QuoteChangeObserver;

use ArrayObject;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface;

class QuoteChangeObserver implements QuoteChangeObserverInterface
{
    public const GLOSSARY_KEY_DISCOUNT_QUOTE_CHANGE_DISCOUNT_NOT_AVAILABLE = 'discount.quote_change.discount.not_available';
    public const GLOSSARY_KEY_DISCOUNT_QUOTE_CHANGE_DISCOUNT_AMOUNT_CHANGED = 'discount.quote_change.discount.amount_changed';

    /**
     * @var \Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface $messengerFacade
     */
    public function __construct(DiscountToMessengerInterface $messengerFacade)
    {
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $resultQuoteTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $sourceQuoteTransfer
     *
     * @return void
     */
    public function checkDiscountChanges(QuoteTransfer $resultQuoteTransfer, QuoteTransfer $sourceQuoteTransfer): void
    {
        $indexResultDiscountTransferCollection = $this->indexDiscountTransferCollection($resultQuoteTransfer->getVoucherDiscounts());
        $indexSourceDiscountTransferCollection = $this->indexDiscountTransferCollection($sourceQuoteTransfer->getVoucherDiscounts());
        $indexResultCartRuleTransferCollection = $this->indexDiscountTransferCollection($resultQuoteTransfer->getCartRuleDiscounts());
        $indexSourceCartRuleTransferCollection = $this->indexDiscountTransferCollection($sourceQuoteTransfer->getCartRuleDiscounts());

        $itemSkuCollection = array_merge(
            $this->checkRemovedDiscountsItemsSku($indexResultDiscountTransferCollection, $indexSourceDiscountTransferCollection, $sourceQuoteTransfer),
            $this->checkRemovedDiscountsItemsSku($indexResultCartRuleTransferCollection, $indexSourceCartRuleTransferCollection, $sourceQuoteTransfer)
        );
        if (!empty($itemSkuCollection)) {
            $this->createInfoMessage(static::GLOSSARY_KEY_DISCOUNT_QUOTE_CHANGE_DISCOUNT_NOT_AVAILABLE, $itemSkuCollection);
        }

        $itemSkuCollection = array_merge(
            $this->checkCurrentDiscountsDiffItemsSku($indexResultDiscountTransferCollection, $indexSourceDiscountTransferCollection, $resultQuoteTransfer),
            $this->checkCurrentDiscountsDiffItemsSku($indexResultCartRuleTransferCollection, $indexSourceCartRuleTransferCollection, $resultQuoteTransfer)
        );
        if (!empty($itemSkuCollection)) {
            $this->createInfoMessage(static::GLOSSARY_KEY_DISCOUNT_QUOTE_CHANGE_DISCOUNT_AMOUNT_CHANGED, $itemSkuCollection);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer[] $indexResultDiscountTransferCollection
     * @param \Generated\Shared\Transfer\DiscountTransfer[] $indexSourceDiscountTransferCollection
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int[]
     */
    protected function checkRemovedDiscountsItemsSku(array $indexResultDiscountTransferCollection, array $indexSourceDiscountTransferCollection, QuoteTransfer $quoteTransfer): array
    {
        $itemSkuCollection = [];
        foreach ($indexSourceDiscountTransferCollection as $discountTransfer) {
            if (!isset($indexResultDiscountTransferCollection[$discountTransfer->getIdDiscount()])) {
                $itemSkuCollection = array_merge(
                    $itemSkuCollection,
                    $this->findItemsWithAppliedDiscounts($quoteTransfer, $discountTransfer->getIdDiscount())
                );
            }
        }

        return $itemSkuCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer[] $indexResultDiscountTransferCollection
     * @param \Generated\Shared\Transfer\DiscountTransfer[] $indexSourceDiscountTransferCollection
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return int[]
     */
    protected function checkCurrentDiscountsDiffItemsSku(array $indexResultDiscountTransferCollection, array $indexSourceDiscountTransferCollection, QuoteTransfer $quoteTransfer): array
    {
        $itemSkuCollection = [];
        foreach ($indexSourceDiscountTransferCollection as $discountTransfer) {
            if (isset($indexResultDiscountTransferCollection[$discountTransfer->getIdDiscount()]) &&
                $discountTransfer->getAmount() !== $indexResultDiscountTransferCollection[$discountTransfer->getIdDiscount()]->getAmount()
            ) {
                $itemSkuCollection = array_merge(
                    $itemSkuCollection,
                    $this->findItemsWithAppliedDiscounts($quoteTransfer, $discountTransfer->getIdDiscount())
                );
            }
        }

        return $itemSkuCollection;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\DiscountTransfer[] $discountTransferCollection
     *
     * @return \Generated\Shared\Transfer\DiscountTransfer[]
     */
    protected function indexDiscountTransferCollection(ArrayObject $discountTransferCollection): array
    {
        $indexDiscountTransferCollection = [];
        foreach ($discountTransferCollection as $discountTransfer) {
            $indexDiscountTransferCollection[$discountTransfer->getIdDiscount()] = $discountTransfer;
        }

        return $indexDiscountTransferCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idDiscount
     *
     * @return int[]
     */
    protected function findItemsWithAppliedDiscounts(QuoteTransfer $quoteTransfer, int $idDiscount): array
    {
        $itemSkuCollection = [];
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            foreach ($itemTransfer->getCalculatedDiscounts() as $calculatedDiscountTransfer) {
                if ($calculatedDiscountTransfer->getIdDiscount() === $idDiscount) {
                    $itemSkuCollection[] = $itemTransfer->getSku();
                }
            }
        }

        return $itemSkuCollection;
    }

    /**
     * @param string $key
     * @param array $skuCollection
     *
     * @return void
     */
    protected function createInfoMessage(string $key, array $skuCollection): void
    {
        $skuCollection = array_unique($skuCollection);
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue($key);
        $messageTransfer->setParameters(['skus' => implode(', ', $skuCollection)]);
        $this->messengerFacade->addInfoMessage($messageTransfer);
    }
}
