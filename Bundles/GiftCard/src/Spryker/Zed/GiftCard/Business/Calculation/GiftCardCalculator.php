<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\Calculation;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\GiftCardTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Orm\Zed\GiftCard\Persistence\SpyGiftCard;
use Spryker\Shared\GiftCard\GiftCardConstants;
use Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReaderInterface;

class GiftCardCalculator
{

    /**
     * @var \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReader
     */
    protected $giftCardReader;

    /**
     * @param \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReaderInterface $giftCardReader
     */
    public function __construct(GiftCardReaderInterface $giftCardReader)
    {
        $this->giftCardReader = $giftCardReader;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $allGiftCards = $calculableObjectTransfer->getGiftCards();
        if ($this->containsGiftCardProducts($calculableObjectTransfer)) {
            $calculableObjectTransfer->setGiftCards(new ArrayObject());
            $this->addNotApplicableGiftCardsToCalculableObject($calculableObjectTransfer, $allGiftCards);

            return;
        }

        list($applicableGiftCards, $nonApplicableGiftCards) = $this->partitionGiftCardsByApplicability($allGiftCards, $calculableObjectTransfer);

        $this->addGiftCardPaymentsToQuote($calculableObjectTransfer, $applicableGiftCards);
        $calculableObjectTransfer->setGiftCards($applicableGiftCards);
        $this->addNotApplicableGiftCardsToCalculableObject($calculableObjectTransfer, $nonApplicableGiftCards);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return bool
     */
    protected function containsGiftCardProducts(CalculableObjectTransfer $calculableObjectTransfer)
    {
        foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {
            $giftCardMetadata = $itemTransfer->getGiftCardMetadata();

            if (!$giftCardMetadata) {
                continue;
            }

            if ($giftCardMetadata->getIsGiftCard()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\GiftCardTransfer[] $allGiftCards
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\GiftCardTransfer[] $allGiftCards
     */
    private function filterNonApplicableGiftCards(ArrayObject $allGiftCards)
    {
        $result = [];

        foreach ($allGiftCards as $giftCard) {
            $giftCardTransfer = $this->getGiftCard($giftCard);

            if (!$giftCardTransfer) {
                continue;
            }

            if (!$this->isApplicable($giftCardTransfer)) {
                $result[] = $giftCardTransfer;
            }
        }

        return new ArrayObject($result);
    }

    /**
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer|null
     */
    protected function findGiftCard(GiftCardTransfer $giftCardTransfer)
    {
        return $this->giftCardReader->findByCode($giftCardTransfer->getCode());
    }

    /**
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     *
     * @return bool
     */
    protected function isApplicable(GiftCardTransfer $giftCardTransfer)
    {
        if ($this->giftCardReader->isUsed($giftCardTransfer)) {
            return false;
        }

        return $giftCardTransfer->getIsActive();
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\GiftCardTransfer[] $allGiftCards
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\GiftCardTransfer[] $allGiftCards
     */
    protected function partitionGiftCardsByApplicability(ArrayObject $allGiftCards)
    {
        $applicableGiftCards = [];
        $nonApplicableGiftCards = [];

        foreach ($allGiftCards as $giftCardTransfer) {
            $giftCardTransfer = $this->findGiftCard($giftCardTransfer);

            if (!$giftCardTransfer) {
                continue;
            }

            if ($this->isApplicable($giftCardTransfer)) {
                $applicableGiftCards[] = $giftCardTransfer;
                continue;
            }

            $nonApplicableGiftCards[] = $giftCardTransfer;
        }

        return [new ArrayObject($applicableGiftCards), new ArrayObject($nonApplicableGiftCards)];
    }

    /**
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     * @param \Orm\Zed\GiftCard\Persistence\SpyGiftCard $giftCardEntity
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer
     */
    protected function hydrateGiftCardTransfer(GiftCardTransfer $giftCardTransfer, SpyGiftCard $giftCardEntity)
    {
        $giftCardTransfer->fromArray($giftCardEntity->toArray(), true);

        return $giftCardTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\GiftCardTransfer[] $applicableGiftCards
     *
     * @return void
     */
    protected function addGiftCardPaymentsToQuote(CalculableObjectTransfer $calculableObjectTransfer, ArrayObject $applicableGiftCards)
    {
        foreach ($applicableGiftCards as $giftCard) {
            if ($this->containsPayment($calculableObjectTransfer, $giftCard)) {
                continue;
            }

            $paymentTransfer = new PaymentTransfer();
            $paymentTransfer->setPaymentProvider(GiftCardConstants::PROVIDER_NAME);
            $paymentTransfer->setPaymentSelection(GiftCardConstants::PROVIDER_NAME);
            $paymentTransfer->setPaymentMethod(GiftCardConstants::PROVIDER_NAME);
            $paymentTransfer->setAmount($giftCard->getValue());
            $paymentTransfer->setIsLimitedAmount(true);

            $paymentTransfer->setGiftCard($giftCard);
            $calculableObjectTransfer->addPayment($paymentTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     *
     * @return bool
     */
    protected function containsPayment(CalculableObjectTransfer $calculableObjectTransfer, GiftCardTransfer $giftCardTransfer)
    {
        foreach ($calculableObjectTransfer->getPayments() as $payment) {
            if (!$payment->getGiftCard()) {
                continue;
            }

            if ($payment->getGiftCard()->getCode() === $giftCardTransfer->getCode()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     * @param \ArrayObject|\Generated\Shared\Transfer\GiftCardTransfer[] $nonApplicableGiftCards
     *
     * @return void
     */
    protected function addNotApplicableGiftCardsToCalculableObject(CalculableObjectTransfer $calculableObjectTransfer, ArrayObject $nonApplicableGiftCards)
    {
        foreach ($nonApplicableGiftCards as $giftCardTransfer) {
            $calculableObjectTransfer->addNotApplicableGiftCardCode($giftCardTransfer->getCode());
            $this->removeGiftCardPayment($calculableObjectTransfer, $giftCardTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     *
     * @return void
     */
    protected function removeGiftCardPayment(CalculableObjectTransfer $calculableObjectTransfer, GiftCardTransfer $giftCardTransfer)
    {
        $payments = [];

        foreach ($calculableObjectTransfer->getPayments() as $paymentTransfer) {
            if (!$paymentTransfer->getGiftCard()) {
                $payments[] = $paymentTransfer;
            }

            if ($paymentTransfer->getGiftCard()->getCode() !== $giftCardTransfer->getCode()) {
                $payments[] = $paymentTransfer;
            }
        }

        $calculableObjectTransfer->setPayments(new ArrayObject($payments));
    }

}
