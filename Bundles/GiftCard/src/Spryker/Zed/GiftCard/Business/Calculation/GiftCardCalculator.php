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
use Spryker\Shared\GiftCard\GiftCardConfig;
use Spryker\Shared\GiftCard\GiftCardConstants;
use Spryker\Zed\GiftCard\Business\GiftCard\GiftCardDecisionRuleCheckerInterface;
use Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReaderInterface;
use Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardValueProviderPluginInterface;

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
     * @var \Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardValueProviderPluginInterface
     */
    protected $giftCardValueProvider;

    /**
     * @param \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReaderInterface $giftCardReader
     * @param \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardDecisionRuleCheckerInterface $giftCardDecisionRuleChecker
     * @param \Spryker\Zed\GiftCard\Dependency\Plugin\GiftCardValueProviderPluginInterface $giftCardValueProvider
     */
    public function __construct(
        GiftCardReaderInterface $giftCardReader,
        GiftCardDecisionRuleCheckerInterface $giftCardDecisionRuleChecker,
        GiftCardValueProviderPluginInterface $giftCardValueProvider
    ) {
        $this->giftCardReader = $giftCardReader;
        $this->giftCardDecisionRuleChecker = $giftCardDecisionRuleChecker;
        $this->giftCardValueProvider = $giftCardValueProvider;
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
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer|null
     */
    protected function findGiftCard(GiftCardTransfer $giftCardTransfer)
    {
        return $this->giftCardReader->findByCode($giftCardTransfer->getCode());
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\GiftCardTransfer[] $allGiftCards
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \ArrayObject[]
     */
    protected function partitionGiftCardsByApplicability(ArrayObject $allGiftCards, CalculableObjectTransfer $calculableObjectTransfer)
    {
        $applicableGiftCards = [];
        $nonApplicableGiftCards = [];

        foreach ($allGiftCards as $giftCardTransfer) {
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
        foreach ($applicableGiftCards as $giftCard) {
            $giftCardPayment = $this->findPayment($calculableObjectTransfer, $giftCard);

            if ($giftCardPayment) {
                $giftCardPayment->setAmount($this->giftCardValueProvider->getValue($giftCard));
                continue;
            }
            $giftCardPayment = new PaymentTransfer();
            $giftCardPayment->setPaymentProvider(GiftCardConfig::PROVIDER_NAME);
            $giftCardPayment->setPaymentSelection(GiftCardConfig::PROVIDER_NAME);
            $giftCardPayment->setPaymentMethod(GiftCardConfig::PROVIDER_NAME);
            $giftCardPayment->setAmount($this->giftCardValueProvider->getValue($giftCard));
            $giftCardPayment->setIsLimitedAmount(true);
            $giftCardPayment->setGiftCard($giftCard);

            $calculableObjectTransfer->addPayment($giftCardPayment);
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
