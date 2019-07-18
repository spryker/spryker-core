<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Business\QuoteChangeObserver;

use ArrayObject;
use Generated\Shared\Transfer\DiscountTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Discount\DiscountConfig;
use Spryker\Zed\Discount\Dependency\Facade\DiscountToMessengerInterface;
use Spryker\Zed\Discount\DiscountDependencyProvider;

class QuoteChangeObserver implements QuoteChangeObserverInterface
{
    public const GLOSSARY_KEY_DISCOUNT_QUOTE_CHANGE_DISCOUNT_NOT_AVAILABLE = 'discount.quote_change.discount.not_available';
    public const GLOSSARY_KEY_DISCOUNT_QUOTE_CHANGE_DISCOUNT_AMOUNT_CHANGED = 'discount.quote_change.discount.amount_changed';
    protected const GLOSSARY_KEY_DISCOUNT_QUOTE_CHANGE_DOES_NOT_APPLY_FOR_CURRENCY = 'discount.quote_change.does_not_apply_for_currency';

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
            $this->createInfoMessageWithSkuCollection(static::GLOSSARY_KEY_DISCOUNT_QUOTE_CHANGE_DISCOUNT_NOT_AVAILABLE, $itemSkuCollection);
        }

        $itemSkuCollection = array_merge(
            $this->checkCurrentDiscountsDiffItemsSku($indexResultDiscountTransferCollection, $indexSourceDiscountTransferCollection, $resultQuoteTransfer),
            $this->checkCurrentDiscountsDiffItemsSku($indexResultCartRuleTransferCollection, $indexSourceCartRuleTransferCollection, $resultQuoteTransfer)
        );
        if (!empty($itemSkuCollection)) {
            $this->createInfoMessageWithSkuCollection(static::GLOSSARY_KEY_DISCOUNT_QUOTE_CHANGE_DISCOUNT_AMOUNT_CHANGED, $itemSkuCollection);
        }

        $this->filterNotUsableVouchersForCurrentCurrency($resultQuoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer[] $indexResultDiscountTransferCollection
     * @param \Generated\Shared\Transfer\DiscountTransfer[] $indexSourceDiscountTransferCollection
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string[]
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
     * @return string[]
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
            if (!$discountTransfer->getIdDiscount()) {
                continue;
            }
            $indexDiscountTransferCollection[$discountTransfer->getIdDiscount()] = $discountTransfer;
        }

        return $indexDiscountTransferCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param int $idDiscount
     *
     * @return string[]
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
    protected function createInfoMessageWithSkuCollection(string $key, array $skuCollection): void
    {
        $skuCollection = array_unique($skuCollection);
        $this->createInfoMessage($key, ['skus' => implode(', ', $skuCollection)]);
    }

    /**
     * @param string $key
     * @param array $parameters
     *
     * @return void
     */
    protected function createInfoMessage(string $key, array $parameters = []): void
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue($key);
        $messageTransfer->setParameters($parameters);

        $this->messengerFacade->addInfoMessage($messageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function filterNotUsableVouchersForCurrentCurrency(QuoteTransfer $quoteTransfer): void
    {
        $voucherDiscounts = $quoteTransfer->getVoucherDiscounts();

        if ($voucherDiscounts->count() === 0) {
            return;
        }

        foreach ($voucherDiscounts as $key => $discountTransfer) {
            if (!$this->isVoucherUsableForCurrentCurrency($discountTransfer, $quoteTransfer)) {
                $voucherDiscounts->offsetUnset($key);

                $this->createInfoMessage(static::GLOSSARY_KEY_DISCOUNT_QUOTE_CHANGE_DOES_NOT_APPLY_FOR_CURRENCY, [
                    '%code%' => $discountTransfer->getVoucherCode(),
                ]);
            }
        }
    }

    /**
     * @param \Generated\Shared\Transfer\DiscountTransfer $discountTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isVoucherUsableForCurrentCurrency(DiscountTransfer $discountTransfer, QuoteTransfer $quoteTransfer): bool
    {
        if ($discountTransfer->getCalculatorPlugin() === DiscountDependencyProvider::PLUGIN_CALCULATOR_PERCENTAGE) {
            return true;
        }

        $grossModeEnabled = $quoteTransfer->getPriceMode() === DiscountConfig::PRICE_MODE_GROSS;
        $netModeEnabled = $quoteTransfer->getPriceMode() === DiscountConfig::PRICE_MODE_NET;

        foreach ($discountTransfer->getMoneyValueCollection() as $moneyValueTransfer) {
            if ($moneyValueTransfer->getCurrency()->getCode() !== $quoteTransfer->getCurrency()->getCode()) {
                continue;
            }

            if ($netModeEnabled && $moneyValueTransfer->getNetAmount()) {
                return true;
            }

            if ($grossModeEnabled && $moneyValueTransfer->getGrossAmount()) {
                return true;
            }
        }

        return false;
    }
}
