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
use Spryker\Zed\GiftCard\Business\ActualValueHydrator\GiftCardActualValueHydratorInterface;
use Spryker\Zed\GiftCard\Business\GiftCard\GiftCardDecisionRuleCheckerInterface;
use Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReaderInterface;
use Spryker\Zed\GiftCard\GiftCardConfig;

class GiftCardCalculator implements GiftCardCalculatorInterface
{
    /**
     * @var \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReaderInterface
     */
    protected $giftCardReader;

    /**
     * @var \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardDecisionRuleCheckerInterface
     */
    protected $giftCardDecisionRuleChecker;

    /**
     * @var \Spryker\Zed\GiftCard\Business\ActualValueHydrator\GiftCardActualValueHydratorInterface
     */
    protected $giftCardActualValueHydrator;

    /**
     * @var \Spryker\Zed\GiftCard\GiftCardConfig
     */
    protected $giftCardConfig;

    /**
     * @param \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReaderInterface $giftCardReader
     * @param \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardDecisionRuleCheckerInterface $giftCardDecisionRuleChecker
     * @param \Spryker\Zed\GiftCard\Business\ActualValueHydrator\GiftCardActualValueHydratorInterface $giftCardActualValueHydrator
     * @param \Spryker\Zed\GiftCard\GiftCardConfig $giftCardConfig
     */
    public function __construct(
        GiftCardReaderInterface $giftCardReader,
        GiftCardDecisionRuleCheckerInterface $giftCardDecisionRuleChecker,
        GiftCardActualValueHydratorInterface $giftCardActualValueHydrator,
        GiftCardConfig $giftCardConfig
    ) {
        $this->giftCardReader = $giftCardReader;
        $this->giftCardDecisionRuleChecker = $giftCardDecisionRuleChecker;
        $this->giftCardActualValueHydrator = $giftCardActualValueHydrator;
        $this->giftCardConfig = $giftCardConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $giftCards = $calculableObjectTransfer->getGiftCards();

        if ($this->hasGiftCardProducts($calculableObjectTransfer)) {
            $calculableObjectTransfer->setGiftCards(new ArrayObject());
            $this->addNotApplicableGiftCardsToCalculableObject($calculableObjectTransfer, $giftCards);

            return;
        }

        list($applicableGiftCards, $nonApplicableGiftCards) = $this->partitionGiftCardsByApplicability($giftCards, $calculableObjectTransfer);

        $this->addGiftCardPaymentsToQuote($calculableObjectTransfer, $applicableGiftCards);
        $calculableObjectTransfer->setGiftCards($applicableGiftCards);
        $this->addNotApplicableGiftCardsToCalculableObject($calculableObjectTransfer, $nonApplicableGiftCards);
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return bool
     */
    protected function hasGiftCardProducts(CalculableObjectTransfer $calculableObjectTransfer)
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
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer|null
     */
    protected function findGiftCard(GiftCardTransfer $giftCardTransfer)
    {
        $giftCardTransfer->requireCode();

        return $this->giftCardReader->findByCode($giftCardTransfer->getCode());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\GiftCardTransfer[] $giftCardTransfers
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \ArrayObject[]
     */
    protected function partitionGiftCardsByApplicability(ArrayObject $giftCardTransfers, CalculableObjectTransfer $calculableObjectTransfer)
    {
        $applicableGiftCards = [];
        $nonApplicableGiftCards = [];

        foreach ($giftCardTransfers as $giftCardTransfer) {
            $giftCardTransfer = $this->findGiftCard($giftCardTransfer);

            if (!$giftCardTransfer) {
                continue;
            }

            if ($this->giftCardDecisionRuleChecker->isApplicable($giftCardTransfer, $calculableObjectTransfer->getOriginalQuote())) {
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
        foreach ($applicableGiftCards as $giftCardTransfer) {
            $giftCardPaymentTransfer = $this->findPayment($calculableObjectTransfer, $giftCardTransfer);
            $giftCardTransfer = $this->giftCardActualValueHydrator->hydrate($giftCardTransfer);

            if ($giftCardPaymentTransfer) {
                $giftCardPaymentTransfer->setAmount(
                    $giftCardTransfer->getActualValue()
                );

                continue;
            }

            $giftCardPaymentTransfer = (new PaymentTransfer())
                ->setPaymentProvider($this->giftCardConfig->getPaymentProviderName())
                ->setPaymentSelection($this->giftCardConfig->getPaymentProviderName())
                ->setPaymentMethod($this->giftCardConfig->getPaymentMethodName())
                ->setAvailableAmount($giftCardTransfer->getActualValue())
                ->setIsLimitedAmount(true)
                ->setGiftCard($giftCardTransfer);

            $calculableObjectTransfer->addPayment($giftCardPaymentTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer|null
     */
    protected function findPayment(CalculableObjectTransfer $calculableObjectTransfer, GiftCardTransfer $giftCardTransfer)
    {
        foreach ($calculableObjectTransfer->getPayments() as $payment) {
            if (!$payment->getGiftCard()) {
                continue;
            }

            if ($payment->getGiftCard()->getCode() === $giftCardTransfer->getCode()) {
                return $payment;
            }
        }

        return null;
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
            $giftCardTransfer = $this->giftCardReader->findByCode($giftCardTransfer->getCode());

            if (!$giftCardTransfer) {
                continue;
            }

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
