<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Persistence;

interface GiftCardQueryContainerInterface
{
    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyGiftCardQuery
     */
    public function queryGiftCards();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idGiftCard
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyGiftCardQuery
     */
    public function queryGiftCardById($idGiftCard);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $abstractSku
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyGiftCardProductAbstractConfigurationQuery
     */
    public function queryGiftCardConfigurationByProductAbstractSku($abstractSku);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $concreteSku
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyGiftCardProductConfigurationQuery
     */
    public function queryGiftCardConfigurationByProductSku($concreteSku);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $code
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyGiftCardQuery
     */
    public function queryGiftCardByCode($code);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string[] $codes
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyGiftCardQuery
     */
    public function queryGiftCardByCodes(array $codes);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $code
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyPaymentGiftCardQuery
     */
    public function queryPaymentGiftCardsForCode($code);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idSalesPayment
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyPaymentGiftCardQuery
     */
    public function queryPaymentGiftCardsForIdSalesPayment($idSalesPayment);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyPaymentGiftCardQuery
     */
    public function queryPaymentGiftCardsForIdSalesOrder($idSalesOrder);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCardQuery
     */
    public function queryGiftCardOrderItemMetadata($idSalesOrderItem);
}
