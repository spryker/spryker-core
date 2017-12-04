<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\GiftCard;

use ArrayObject;
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
    protected function replaceGiftCardsTransaction(ArrayObject $giftCardPayments)
    {
        foreach ($giftCardPayments as $giftCardPayment) {
            $giftCard = $this->giftCardReader->findByCode($giftCardPayment->getCode());
            if (!$giftCard) {
                continue;
            }

            if (!$giftCard->getReplacementPattern()) {
                continue;
            }

            $newValue = $giftCard->getValue() - $giftCardPayment->getSpySalesPayment()->getAmount();

            if ($newValue > 0) {
                $giftCardTransfer = (new GiftCardTransfer())
                    ->setCode($this->giftCardCodeGenerator->generateGiftCardCode($giftCard->getReplacementPattern()))
                    ->setValue($newValue)
                    ->setReplacementPattern($giftCard->getReplacementPattern())
                    ->setName('Gift Card Replacement')
                    ->setAttributes($giftCard->getAttributes())
                    ->setIsActive(true);

                $this->giftCardCreator->create($giftCardTransfer);
            }
        }
    }
}
