<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\GiftCard;

use Generated\Shared\Transfer\GiftCardTransfer;
use Orm\Zed\GiftCard\Persistence\SpyGiftCard;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCard;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Zed\GiftCard\Business\Exception\GiftCardMissingCodeException;

class GiftCardCreator implements GiftCardCreatorInterface
{
    public const ATTRIBUTES = 'attributes';

    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected $encodingService;

    /**
     * @var \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReaderInterface
     */
    protected $giftCardReader;

    /**
     * @var \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardCodeGeneratorInterface
     */
    protected $giftCardCodeGenerator;

    /**
     * @param \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardReaderInterface $giftCardReader
     * @param \Spryker\Zed\GiftCard\Business\GiftCard\GiftCardCodeGeneratorInterface $giftCardCodeGenerator
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $encodingService
     */
    public function __construct(
        GiftCardReaderInterface $giftCardReader,
        GiftCardCodeGeneratorInterface $giftCardCodeGenerator,
        UtilEncodingServiceInterface $encodingService
    ) {
        $this->encodingService = $encodingService;
        $this->giftCardReader = $giftCardReader;
        $this->giftCardCodeGenerator = $giftCardCodeGenerator;
    }

    /**
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer
     */
    public function create(GiftCardTransfer $giftCardTransfer)
    {
        $this->assertGiftCardProperties($giftCardTransfer);

        $giftCardEntity = $this->createGiftCardEntityFromTransfer($giftCardTransfer);
        $giftCardEntity->save();

        $this->updateGiftCardTransferFromEntity($giftCardTransfer, $giftCardEntity);

        return $giftCardTransfer;
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

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer
     */
    public function createGiftCardForOrderItem($idSalesOrderItem)
    {
        $giftCardMetadataEntity = $this->giftCardReader->getGiftCardOrderItemMetadata($idSalesOrderItem);
        $giftCardTransfer = $this->createGiftCardTransferFromMetadata($giftCardMetadataEntity);

        $giftCardMetadataEntity->setCode($giftCardTransfer->getCode());
        $giftCardMetadataEntity->save();

        return $this->create($giftCardTransfer);
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCard $giftCardOrderItemMetadata
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer
     */
    protected function createGiftCardTransferFromMetadata(SpySalesOrderItemGiftCard $giftCardOrderItemMetadata)
    {
        $orderItemEntity = $giftCardOrderItemMetadata->getSpySalesOrderItem();
        $orderEntity = $orderItemEntity->getOrder();

        $giftCardTransfer = new GiftCardTransfer();
        $giftCardMetadata = $giftCardOrderItemMetadata->toArray();

        $giftCardTransfer->setAttributes($this->encodingService->decodeJson($giftCardMetadata[static::ATTRIBUTES]));
        unset($giftCardMetadata[static::ATTRIBUTES]);

        $giftCardTransfer->fromArray($giftCardMetadata, true);

        $giftCardTransfer
            ->setIsActive(true)
            ->setName($orderItemEntity->getName())
            ->setCurrencyIsoCode($orderEntity->getCurrencyIsoCode())
            ->setReplacementPattern($giftCardOrderItemMetadata->getPattern())
            ->setCode($this->getCode($giftCardOrderItemMetadata))
            ->setValue($this->getGiftCardValue(
                $orderItemEntity,
                $giftCardOrderItemMetadata
            ));

        return $giftCardTransfer;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem $giftCardOrderItem
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCard $giftCardOrderItemMetadata
     *
     * @return int
     */
    protected function getGiftCardValue(
        SpySalesOrderItem $giftCardOrderItem,
        SpySalesOrderItemGiftCard $giftCardOrderItemMetadata
    ) {
        if ($giftCardOrderItemMetadata->getValue() !== null) {
            return $giftCardOrderItemMetadata->getValue();
        }

        return $giftCardOrderItem->getPrice();
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCard $giftCardOrderItemMetadata
     *
     * @throws \Spryker\Zed\GiftCard\Business\Exception\GiftCardMissingCodeException
     *
     * @return string
     */
    protected function getCode(SpySalesOrderItemGiftCard $giftCardOrderItemMetadata)
    {
        if ($giftCardOrderItemMetadata->getCode()) {
            return $giftCardOrderItemMetadata->getCode();
        }

        if (!$giftCardOrderItemMetadata->getPattern()) {
            throw new GiftCardMissingCodeException('Neither code nor pattern were provided for gift card sales order item with id ' . $giftCardOrderItemMetadata->getFkSalesOrderItem());
        }

        return $this->giftCardCodeGenerator->generateGiftCardCode($giftCardOrderItemMetadata->getPattern());
    }
}
