<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\GiftCard;

use Generated\Shared\Transfer\GiftCardTransfer;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class GiftCardReplacer implements GiftCardReplacerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReaderInterface
     */
    protected $giftCardReader;

    /**
     * @var \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardCreatorInterface
     */
    protected $giftCardCreator;

    /**
     * @var \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardCodeGeneratorInterface
     */
    protected $giftCardCodeGenerator;

    /**
     * @param \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReaderInterface $giftCardReader
     * @param \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardCreatorInterface $giftCardCreator
     * @param \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardCodeGeneratorInterface $giftCardCodeGenerator
     */
    public function __construct(
        GiftCardReaderInterface $giftCardReader,
        GiftCardCreatorInterface $giftCardCreator,
        GiftCardCodeGeneratorInterface $giftCardCodeGenerator
    ) {
        $this->giftCardReader = $giftCardReader;
        $this->giftCardCreator = $giftCardCreator;
        $this->giftCardCodeGenerator = $giftCardCodeGenerator;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function replaceGiftCards($idSalesOrder)
    {
        $giftCardPayments = $this->giftCardReader->getGiftCardPaymentsForOrder($idSalesOrder);

        $this->handleDatabaseTransaction(function () use ($giftCardPayments) {
            $this->replaceGiftCardsTransaction($giftCardPayments);
        });
    }

    /**
     * @param \ArrayObject|\Orm\Zed\GiftCard\Persistence\SpyPaymentGiftCard[] $giftCardPayments
     *
     * @return void
     */
    protected function replaceGiftCardsTransaction(array $giftCardPayments)
    {
        foreach ($giftCardPayments as $giftCardPayment) {
            $giftCardTransfer = $this->giftCardReader->findByCode($giftCardPayment->getCode());

            if (!$giftCardTransfer) {
                continue;
            }

            if (!$giftCardTransfer->getReplacementPattern()) {
                continue;
            }

            $newValue = $giftCardTransfer->getValue() - $giftCardPayment->getSpySalesPayment()->getAmount();

            if ($newValue <= 0) {
                continue;
            }

            $this->createReplacementGiftCard($giftCardTransfer, $newValue);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransferBase
     * @param int $value
     *
     * @return void
     */
    protected function createReplacementGiftCard(GiftCardTransfer $giftCardTransferBase, $value)
    {
        $giftCardCode = $this->giftCardCodeGenerator->generateGiftCardCode(
            $giftCardTransferBase->getReplacementPattern()
        );

        $giftCardName = $this->generateGiftCardName($giftCardTransferBase);

        $giftCardTransfer = new GiftCardTransfer();
        $giftCardTransfer = $giftCardTransfer->fromArray($giftCardTransferBase->toArray(), true);
        $giftCardTransfer
            ->setCode($giftCardCode)
            ->setName($giftCardName)
            ->setValue($value)
            ->setReplacementPattern($giftCardTransfer->getReplacementPattern())
            ->setAttributes($giftCardTransfer->getAttributes())
            ->setIsActive(true);

        $this->giftCardCreator->create($giftCardTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     *
     * @return string
     */
    protected function generateGiftCardName(GiftCardTransfer $giftCardTransfer)
    {
        return $giftCardTransfer->getName() . ' Replacement';
    }
}
