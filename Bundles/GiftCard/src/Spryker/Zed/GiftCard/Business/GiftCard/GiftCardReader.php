<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\GiftCard;

use Generated\Shared\Transfer\GiftCardTransfer;
use Orm\Zed\GiftCard\Persistence\SpyGiftCard;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Zed\GiftCard\Business\Exception\GiftCardNotFoundException;
use Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainerInterface;

class GiftCardReader implements GiftCardReaderInterface
{

    const ATTRIBUTES = 'attributes';

    /**
     * @var \Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainerInterface
     */
    private $queryContainer;

    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingService
     */
    private $encodingService;

    /**
     * @param \Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainerInterface $queryContainer
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $encodingService
     */
    public function __construct(
        GiftCardQueryContainerInterface $queryContainer,
        UtilEncodingServiceInterface $encodingService
    ) {
        $this->queryContainer = $queryContainer;
        $this->encodingService = $encodingService;
    }

    /**
     * @param int $idGiftCard
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer|null
     */
    public function findById($idGiftCard)
    {
        $giftCardEntity = $this->findEntityById($idGiftCard);

        if (!$giftCardEntity) {
            return null;
        }

        return $this->getGiftCardTransferFromEntity($giftCardEntity);
    }

    /**
     * @param int $idGiftCard
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyGiftCard|null
     */
    protected function findEntityById($idGiftCard)
    {
        return $this
            ->queryContainer
            ->queryGiftCardById($idGiftCard)
            ->findOne();
    }

    /**
     * @param string $code
     *
     * @throws \Spryker\Zed\GiftCard\Business\Exception\GiftCardNotFoundException
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer
     */
    public function getByCode($code)
    {
        $giftCardTransfer = $this->findByCode($code);

        if (!$giftCardTransfer) {
            throw new GiftCardNotFoundException(sprintf(
                'Gift card for code "%s" could not be found',
                $code
            ));
        }

        return $giftCardTransfer;
    }

    /**
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer|null
     */
    public function findByCode($code)
    {
        $giftCardEntity = $this->findEntityByCode($code);

        if (!$giftCardEntity) {
            return null;
        }

        $giftCardTransfer = $this->getGiftCardTransferFromEntity($giftCardEntity);

        return $giftCardTransfer;
    }

    /**
     * @param string $code
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyGiftCard|null
     */
    protected function findEntityByCode($code)
    {
        return $this
            ->queryContainer
            ->queryGiftCardByCode($code)
            ->findOne();
    }

    /**
     * @param \Orm\Zed\GiftCard\Persistence\SpyGiftCard $giftCardEntity
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer
     */
    protected function getGiftCardTransferFromEntity(SpyGiftCard $giftCardEntity)
    {
        $giftCardTransfer = new GiftCardTransfer();

        $giftCardData = $giftCardEntity->toArray();
        $attributes = $this->encodingService->decodeJson($giftCardData[self::ATTRIBUTES], true);

        if (!$attributes) {
            $attributes = [];
        }

        $giftCardTransfer->setAttributes($attributes);
        unset($giftCardData[self::ATTRIBUTES]);

        $giftCardTransfer->fromArray($giftCardData, true);

        return $giftCardTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     *
     * @return bool
     */
    public function isUsed(GiftCardTransfer $giftCardTransfer)
    {
        return $this->queryContainer->queryPaymentGiftCardsForCode($giftCardTransfer->getCode())->count() > 0;
    }

}
