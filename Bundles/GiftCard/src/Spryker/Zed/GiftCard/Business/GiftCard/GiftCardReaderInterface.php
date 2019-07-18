<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business\GiftCard;

interface GiftCardReaderInterface
{
    /**
     * @param int $idGiftCard
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer|null
     */
    public function findById($idGiftCard);

    /**
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isGiftCardOrderItem($idSalesOrderItem);

    /**
     * @param int $idSalesOrderItem
     *
     * @throws \Spryker\Zed\GiftCard\Business\Exception\GiftCardSalesMetadataNotFoundException
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCard
     */
    public function getGiftCardOrderItemMetadata($idSalesOrderItem);

    /**
     * @param string $code
     *
     * @return bool
     */
    public function isUsed($code);

    /**
     * @param string $code
     *
     * @return bool
     */
    public function isPresent($code);

    /**
     * @param string $code
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer|null
     */
    public function findByCode($code);

    /**
     * @param string $abstractSku
     *
     * @return \Generated\Shared\Transfer\GiftCardAbstractProductConfigurationTransfer|null
     */
    public function findGiftCardAbstractConfiguration($abstractSku);

    /**
     * @param string $concreteSku
     *
     * @return \Generated\Shared\Transfer\GiftCardProductConfigurationTransfer|null
     */
    public function findGiftCardConcreteConfiguration($concreteSku);

    /**
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyPaymentGiftCard[]
     */
    public function getGiftCardPaymentsForOrder($idSalesOrder);

    /**
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer[]
     */
    public function findGiftCardsByIdSalesOrder($idSalesOrder);

    /**
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer|null
     */
    public function findGiftCardByIdSalesOrderItem($idSalesOrderItem);
}
