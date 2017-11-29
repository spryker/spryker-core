<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCard\Business;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CheckoutResponseTransfer;
use Generated\Shared\Transfer\CollectedDiscountTransfer;
use Generated\Shared\Transfer\GiftCardTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\GiftCard\Business\GiftCardBusinessFactory getFactory()
 */
class GiftCardFacade extends AbstractFacade implements GiftCardFacadeInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\GiftCardTransfer $giftCardTransfer
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer
     */
    public function create(GiftCardTransfer $giftCardTransfer)
    {
        return $this
            ->getFactory()
            ->createGiftCardCreator()
            ->create($giftCardTransfer);
    }

    /**
     * @api
     *
     * @param int $idGiftCard
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer|null
     */
    public function findById($idGiftCard)
    {
        return $this
            ->getFactory()
            ->createGiftCardReader()
            ->findById($idGiftCard);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandGiftCardMetadata(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFactory()->createGiftCardMetadataExpander()->expandGiftCardMetadata($cartChangeTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CollectedDiscountTransfer $collectedDiscountTransfer
     *
     * @return \Generated\Shared\Transfer\CollectedDiscountTransfer
     */
    public function filterGiftCardDiscountableItems(CollectedDiscountTransfer $collectedDiscountTransfer)
    {
        return $this->getFactory()->createGiftCardDiscountableItemFilter()->filterGiftCardDiscountableItems($collectedDiscountTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentInformationTransfer[]|\ArrayObject $paymentMethods
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentInformationTransfer[]|\ArrayObject
     */
    public function filterPaymentMethods(ArrayObject $paymentMethods, QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->createPaymentMethodFilter()->filterPaymentMethods($paymentMethods, $quoteTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->getFactory()->createGiftCardCalculator()->recalculate($calculableObjectTransfer);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function precheckSalesOrderGiftCards(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this
            ->getFactory()
            ->createSalesOrderPreChecker()
            ->precheckSalesOrderGiftCards($quoteTransfer, $checkoutResponse);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveGiftCardPayments(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this
            ->getFactory()
            ->createSalesOrderSaver()
            ->saveGiftCardPayments($quoteTransfer, $checkoutResponse);
    }

    /**
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isGiftCardOrderItem($idSalesOrderItem)
    {
        return $this->getFactory()->createGiftCardReader()->isGiftCardOrderItem($idSalesOrderItem);
    }

    /**
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer
     */
    public function createGiftCardForOrderItem($idSalesOrderItem)
    {
        return $this->getFactory()->createGiftCardCreator()->createGiftCardForOrderItem($idSalesOrderItem);
    }

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveSalesOrderGiftCardItems(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getFactory()->createSalesOrderItemSaver()->saveSalesOrderGiftCardItems($quoteTransfer, $checkoutResponse);
    }

    /**
     * @api
     *
     * @param string $code
     *
     * @return bool
     */
    public function isUsed($code)
    {
        return $this->getFactory()->createGiftCardReader()->isUsed($code);
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function replaceGiftCards($idSalesOrder)
    {
        $this->getFactory()->createGiftCardReplacer()->replaceGiftCards($idSalesOrder);
    }

    /**
     * @api
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethods
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ShipmentMethodTransfer[] $shipmentMethods
     */
    public function filterShipmentMethods(ArrayObject $shipmentMethods, QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()->createShipmentMethodFilter()->filterShipmentMethods($shipmentMethods, $quoteTransfer);
    }

    /**
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer[]
     */
    public function findGiftCardsByIdSalesOrder($idSalesOrder)
    {
        return $this->getFactory()
            ->createGiftCardReader()
            ->findGiftCardsByIdSalesOrder($idSalesOrder);
    }
}
