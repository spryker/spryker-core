<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\GiftCard;

use Generated\Shared\Transfer\GiftCardAbstractProductConfigurationTransfer;
use Generated\Shared\Transfer\GiftCardProductConfigurationTransfer;
use Generated\Shared\Transfer\GiftCardTransfer;
use Orm\Zed\GiftCard\Persistence\SpyGiftCard;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use Spryker\Zed\GiftCard\Business\ActualValueHydrator\GiftCardActualValueHydratorInterface;
use Spryker\Zed\GiftCard\Business\Exception\GiftCardNotFoundException;
use Spryker\Zed\GiftCard\Business\Exception\GiftCardSalesMetadataNotFoundException;
use Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainerInterface;

class GiftCardReader implements GiftCardReaderInterface
{
    public const ATTRIBUTES = 'attributes';

    /**
     * @var \Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\GiftCard\Business\ActualValueHydrator\GiftCardActualValueHydratorInterface
     */
    protected $giftCardActualValueHydrator;

    /**
     * @var \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface
     */
    protected $encodingService;

    /**
     * @param \Spryker\Zed\GiftCard\Persistence\GiftCardQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\GiftCard\Business\ActualValueHydrator\GiftCardActualValueHydratorInterface $giftCardActualValueHydrator
     * @param \Spryker\Service\UtilEncoding\UtilEncodingServiceInterface $encodingService
     */
    public function __construct(
        GiftCardQueryContainerInterface $queryContainer,
        GiftCardActualValueHydratorInterface $giftCardActualValueHydrator,
        UtilEncodingServiceInterface $encodingService
    ) {
        $this->queryContainer = $queryContainer;
        $this->giftCardActualValueHydrator = $giftCardActualValueHydrator;
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

        return $this->hydrateGiftCardTransfer($giftCardEntity, new GiftCardTransfer());
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

        $giftCardTransfer = $this->hydrateGiftCardTransfer($giftCardEntity, new GiftCardTransfer());

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
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer
     */
    protected function hydrateGiftCardTransfer(SpyGiftCard $giftCardEntity, GiftCardTransfer $giftCardTransfer)
    {
        $giftCardData = $giftCardEntity->toArray();
        $attributes = $this->encodingService->decodeJson($giftCardData[static::ATTRIBUTES], true);

        if (!$attributes) {
            $attributes = [];
        }

        $giftCardTransfer->setAttributes($attributes);
        unset($giftCardData[static::ATTRIBUTES]);

        $giftCardTransfer->fromArray($giftCardData, true);
        $giftCardTransfer = $this->giftCardActualValueHydrator->hydrate($giftCardTransfer);

        return $giftCardTransfer;
    }

    /**
     * @param \Orm\Zed\GiftCard\Persistence\SpyGiftCard[] $giftCardEntities
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer[]
     */
    protected function getGiftCardTransfersFromEntities(array $giftCardEntities)
    {
        $giftCardTransfers = [];

        foreach ($giftCardEntities as $giftCardEntity) {
            $giftCardTransfers[] = $this->hydrateGiftCardTransfer($giftCardEntity, new GiftCardTransfer());
        }

        return $giftCardTransfers;
    }

    /**
     * @param string $code
     *
     * @return bool
     */
    public function isUsed($code)
    {
        return $this->queryContainer->queryPaymentGiftCardsForCode($code)->count() > 0;
    }

    /**
     * @param string $code
     *
     * @return bool
     */
    public function isPresent($code)
    {
        return $this->queryContainer->queryGiftCardByCode($code)->count() > 0;
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isGiftCardOrderItem($idSalesOrderItem)
    {
        return $this->queryContainer->queryGiftCardOrderItemMetadata($idSalesOrderItem)->count() > 0;
    }

    /**
     * @param string $abstractSku
     *
     * @return \Generated\Shared\Transfer\GiftCardAbstractProductConfigurationTransfer|null
     */
    public function findGiftCardAbstractConfiguration($abstractSku)
    {
        $configurationEntity = $this->queryContainer->queryGiftCardConfigurationByProductAbstractSku($abstractSku)->findOne();

        if (!$configurationEntity) {
            return null;
        }

        $configurationTransfer = new GiftCardAbstractProductConfigurationTransfer();
        $configurationTransfer->fromArray($configurationEntity->toArray(), true);

        return $configurationTransfer;
    }

    /**
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\GiftCardProductConfigurationTransfer|null
     */
    public function findGiftCardConcreteConfiguration($concreteSku)
    {
        $configurationEntity = $this->queryContainer->queryGiftCardConfigurationByProductSku($concreteSku)->findOne();

        if (!$configurationEntity) {
            return null;
        }

        $configurationTransfer = new GiftCardProductConfigurationTransfer();
        $configurationTransfer->fromArray($configurationEntity->toArray(), true);

        return $configurationTransfer;
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @throws \Spryker\Zed\GiftCard\Business\Exception\GiftCardSalesMetadataNotFoundException
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCard
     */
    public function getGiftCardOrderItemMetadata($idSalesOrderItem)
    {
        $giftCardSalesMetadataEntity = $this->queryContainer->queryGiftCardOrderItemMetadata($idSalesOrderItem)->findOne();

        if (!$giftCardSalesMetadataEntity) {
            throw new GiftCardSalesMetadataNotFoundException('Giftcard Metadata for item ' . $idSalesOrderItem . ' were requested but are missing');
        }

        return $giftCardSalesMetadataEntity;
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyPaymentGiftCard[]
     */
    public function getGiftCardPaymentsForOrder($idSalesOrder)
    {
        return $this->queryContainer
            ->queryPaymentGiftCardsForIdSalesOrder($idSalesOrder)
            ->find()
            ->getArrayCopy();
    }

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer[]
     */
    public function findGiftCardsByIdSalesOrder($idSalesOrder)
    {
        $giftCardPaymentEntities = $this->getGiftCardPaymentsForOrder($idSalesOrder);
        $giftCardCodes = $this->extractGiftCardCodesFromGiftCardPaymentEntities($giftCardPaymentEntities);

        $giftCardEntities = $this->getGiftCardEntitiesByCodes($giftCardCodes);
        $giftCardTransfers = $this->getGiftCardTransfersFromEntities($giftCardEntities);

        return $giftCardTransfers;
    }

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer|null
     */
    public function findGiftCardByIdSalesOrderItem($idSalesOrderItem)
    {
        $salesOrderItemGiftCardEntity = $this->queryContainer
            ->queryGiftCardOrderItemMetadata($idSalesOrderItem)
            ->findOne();

        return $this->findByCode($salesOrderItemGiftCardEntity->getCode());
    }

    /**
     * @param \Orm\Zed\GiftCard\Persistence\SpyGiftCard[] $giftCardCodes
     *
     * @return array
     */
    protected function getGiftCardEntitiesByCodes(array $giftCardCodes)
    {
        return $this->queryContainer
            ->queryGiftCardByCodes($giftCardCodes)
            ->find()
            ->getArrayCopy();
    }

    /**
     * @param array $giftCardPayments
     *
     * @return array
     */
    protected function extractGiftCardCodesFromGiftCardPaymentEntities(array $giftCardPayments)
    {
        $codes = [];

        foreach ($giftCardPayments as $paymentGiftCard) {
            $codes[] = $paymentGiftCard->getCode();
        }

        return $codes;
    }
}
