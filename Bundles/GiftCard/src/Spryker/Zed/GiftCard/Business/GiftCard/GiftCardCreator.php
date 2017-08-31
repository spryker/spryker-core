<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\GiftCard;

use Generated\Shared\Transfer\GiftCardTransfer;
use Orm\Zed\GiftCard\Persistence\SpyGiftCard;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;

class GiftCardCreator implements GiftCardCreatorInterface
{

    const ATTRIBUTES = 'attributes';

    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingService
     */
    protected $encodingService;

    /**
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $encodingService
     */
    public function __construct(UtilEncodingServiceInterface $encodingService)
    {
        $this->encodingService = $encodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     *
     * @return void
     */
    public function create(GiftCardTransfer $giftCardTransfer)
    {
        $this->assertGiftCardProperties($giftCardTransfer);

        $giftCardEntity = $this->createGiftCardEntityFromTransfer($giftCardTransfer);
        $giftCardEntity->save();

        $this->updateGiftCardTransferFromEntity($giftCardTransfer, $giftCardEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     *
     * @return void
     */
    protected function assertGiftCardProperties(GiftCardTransfer $giftCardTransfer)
    {
        $giftCardTransfer
            ->requireCode()
            ->requireName()
            ->requireValue()
            ->requireIsActive()
            ->requireAttributes();
    }

    /**
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyGiftCard
     */
    protected function createGiftCardEntityFromTransfer(GiftCardTransfer $giftCardTransfer)
    {
        $giftCardEntity = new SpyGiftCard();
        $giftCardData = $giftCardTransfer->toArray();

        $giftCardEntity->setAttributes($this->encodingService->encodeJson($giftCardData[static::ATTRIBUTES]));
        unset($giftCardData[static::ATTRIBUTES]);

        $giftCardEntity->fromArray($giftCardData);

        return $giftCardEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     * @param \Orm\Zed\GiftCard\Persistence\SpyGiftCard $giftCardEntity
     *
     * @return void
     */
    protected function updateGiftCardTransferFromEntity(GiftCardTransfer $giftCardTransfer, SpyGiftCard $giftCardEntity)
    {
        $giftCardTransfer->setIdGiftCard($giftCardEntity->getIdGiftCard());
    }

}
