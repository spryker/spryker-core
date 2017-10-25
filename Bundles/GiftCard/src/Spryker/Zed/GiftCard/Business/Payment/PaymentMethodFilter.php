<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\Payment;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentInformationTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\GiftCard\GiftCardConfig;

class PaymentMethodFilter implements PaymentMethodFilterInterface
{
    /**
     * @var \Spryker\Zed\GiftCard\GiftCardConfig
     */
    protected $giftCardConfig;

    /**
     * @param \Spryker\Zed\GiftCard\GiftCardConfig $giftCardConfig
     */
    public function __construct(GiftCardConfig $giftCardConfig)
    {
        $this->giftCardConfig = $giftCardConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentInformationTransfer[]|\ArrayObject $paymentMethods
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \ArrayObject
     */
    public function filterPaymentMethods(ArrayObject $paymentMethods, QuoteTransfer $quoteTransfer)
    {
        if (!$this->containsGiftCards($quoteTransfer)) {
            return $paymentMethods;
        }

        return $this->excludeBlacklistedPaymentMethods($paymentMethods);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function containsGiftCards(QuoteTransfer $quoteTransfer)
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($this->isGiftCard($itemTransfer)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isGiftCard(ItemTransfer $itemTransfer)
    {
        $metadata = $itemTransfer->getGiftCardMetadata();

        if (!$metadata) {
            return false;
        }

        return $metadata->getIsGiftCard();
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentInformationTransfer[]|\ArrayObject $paymentMethods
     *
     * @return \Generated\Shared\Transfer\PaymentInformationTransfer[]|\ArrayObject
     */
    protected function excludeBlacklistedPaymentMethods(ArrayObject $paymentMethods)
    {
        $result = new ArrayObject();

        foreach ($paymentMethods as $paymentMethod) {
            if (!$this->isBlacklisted($paymentMethod)) {
                $result[] = $paymentMethod;
            }
        }

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentInformationTransfer $paymentInformationTransfer
     *
     * @return bool
     */
    protected function isBlacklisted(PaymentInformationTransfer $paymentInformationTransfer)
    {
        $giftCardMethodBlacklist = $this->giftCardConfig->getGiftCardMethodBlacklist();
        foreach ($giftCardMethodBlacklist as $giftCardMethod) {
            if ($paymentInformationTransfer->getMethod() === $giftCardMethod) {
                return true;
            }
        }

        return false;
    }
}
