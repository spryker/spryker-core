<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\GiftCard\Persistence\GiftCardPersistenceFactory getFactory()
 */
class GiftCardQueryContainer extends AbstractQueryContainer implements GiftCardQueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyGiftCardQuery
     */
    public function queryGiftCards()
    {
        return $this
            ->getFactory()
            ->createGiftCardQuery();
    }

    /**
     * @api
     *
     * @param int $idGiftCard
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyGiftCardQuery
     */
    public function queryGiftCardById($idGiftCard)
    {
        return $this
            ->queryGiftCards()
            ->filterByIdGiftCard($idGiftCard);
    }

    /**
     * @api
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyPaymentGiftCardQuery
     */
    public function queryPaymentGiftCards()
    {
        return $this
            ->getFactory()
            ->createSalesOrderGiftCardQuery();
    }

    /**
     * @api
     *
     * @param string $code
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyPaymentGiftCardQuery
     */
    public function queryPaymentGiftCardsForCode($code)
    {
        return $this
            ->queryPaymentGiftCards()
            ->filterByCode($code);
    }

    /**
     * @api
     *
     * @param string $abstractSku
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyGiftCardProductAbstractConfigurationQuery
     */
    public function queryGiftCardConfigurationByProductAbstractSku($abstractSku)
    {
        return $this
            ->getFactory()
            ->createSpyGiftCardProductAbstractConfigurationQuery()
            ->useSpyGiftCardProductAbstractConfigurationLinkQuery()
            ->useSpyProductAbstractQuery()
            ->filterBySku($abstractSku)
            ->endUse()
            ->endUse();
    }

    /**
     * @api
     *
     * @param string $concreteSku
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyGiftCardProductConfigurationQuery
     */
    public function queryGiftCardConfigurationByProductSku($concreteSku)
    {
        return $this
            ->getFactory()
            ->createSpyGiftCardProductConfigurationQuery()
            ->useSpyGiftCardProductConfigurationLinkQuery()
            ->useSpyProductQuery()
            ->filterBySku($concreteSku)
            ->endUse()
            ->endUse();
    }

    /**
     * @api
     *
     * @param int $idSalesPayment
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyPaymentGiftCardQuery
     */
    public function queryPaymentGiftCardsForIdSalesPayment($idSalesPayment)
    {
        return $this->queryPaymentGiftCards()->filterByFkSalesPayment($idSalesPayment);
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyPaymentGiftCardQuery
     */
    public function queryPaymentGiftCardsForIdSalesOrder($idSalesOrder)
    {
        return $this
            ->queryPaymentGiftCards()
            ->useSpySalesPaymentQuery()
                ->filterByFkSalesOrder($idSalesOrder)
            ->endUse();
    }

    /**
     * @api
     *
     * @param string $code
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyGiftCardQuery
     */
    public function queryGiftCardByCode($code)
    {
        return $this->queryGiftCards()->filterByCode($code);
    }

    /**
     * @api
     *
     * @param string[] $codes
     *
     * @return \Orm\Zed\GiftCard\Persistence\SpyGiftCardQuery
     */
    public function queryGiftCardByCodes(array $codes)
    {
        return $this->queryGiftCards()->filterByCode($codes, Criteria::IN);
    }

    /**
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return \Orm\Zed\Sales\Persistence\SpySalesOrderItemGiftCardQuery
     */
    public function queryGiftCardOrderItemMetadata($idSalesOrderItem)
    {
        return $this
            ->getFactory()
            ->createSpySalesOrderItemGiftCardQuery()
            ->filterByFkSalesOrderItem($idSalesOrderItem);
    }
}
