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
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\PaymentGiftCardCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\SalesOrderItemGiftCardCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\SalesOrderItemGiftCardCollectionResponseTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\GiftCard\Business\GiftCardBusinessFactory getFactory()
 * @method \Spryker\Zed\GiftCard\Persistence\GiftCardRepositoryInterface getRepository()
 * @method \Spryker\Zed\GiftCard\Persistence\GiftCardEntityManagerInterface getEntityManager()
 */
class GiftCardFacade extends AbstractFacade implements GiftCardFacadeInterface
{
    /**
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
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
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function expandGiftCardMetadata(CartChangeTransfer $cartChangeTransfer)
    {
        return $this->getFactory()
            ->createGiftCardMetadataExpander()
            ->expandGiftCardMetadata($cartChangeTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CollectedDiscountTransfer $collectedDiscountTransfer
     *
     * @return \Generated\Shared\Transfer\CollectedDiscountTransfer
     */
    public function filterGiftCardDiscountableItems(CollectedDiscountTransfer $collectedDiscountTransfer)
    {
        return $this->getFactory()
            ->createGiftCardDiscountableItemFilter()
            ->filterGiftCardDiscountableItems($collectedDiscountTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function filterPaymentMethods(PaymentMethodsTransfer $paymentMethodsTransfer, QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()
            ->createPaymentMethodFilter()
            ->filterPaymentMethods($paymentMethodsTransfer, $quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return void
     */
    public function recalculate(CalculableObjectTransfer $calculableObjectTransfer)
    {
        $this->getFactory()
            ->createGiftCardCalculator()
            ->recalculate($calculableObjectTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return bool
     */
    public function precheckSalesOrderGiftCards(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        return $this
            ->getFactory()
            ->createSalesOrderPreChecker()
            ->precheckSalesOrderGiftCards($quoteTransfer, $checkoutResponse);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\GiftCard\Business\GiftCardFacade::saveOrderGiftCards()} instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveGiftCardPayments(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getFactory()
            ->createSalesOrderPaymentSaver()
            ->saveGiftCardPayments($quoteTransfer, $checkoutResponse);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Partially replaced by {@link \Spryker\Zed\GiftCard\Business\GiftCardFacade::createGiftCardPaymentsFromQuote()}.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function saveOrderGiftCards(QuoteTransfer $quoteTransfer, SaveOrderTransfer $saveOrderTransfer): void
    {
        $this->getFactory()
            ->createOrderGiftCardsRelationshipWriter()
            ->saveOrderGiftCards($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return bool
     */
    public function isGiftCardOrderItem($idSalesOrderItem)
    {
        return $this->getFactory()
            ->createGiftCardReader()
            ->isGiftCardOrderItem($idSalesOrderItem);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer
     */
    public function createGiftCardForOrderItem($idSalesOrderItem)
    {
        return $this->getFactory()
            ->createGiftCardCreator()
            ->createGiftCardForOrderItem($idSalesOrderItem);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\GiftCard\Business\GiftCardFacade::saveOrderGiftCards()} instead.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\CheckoutResponseTransfer $checkoutResponse
     *
     * @return void
     */
    public function saveSalesOrderGiftCardItems(QuoteTransfer $quoteTransfer, CheckoutResponseTransfer $checkoutResponse)
    {
        $this->getFactory()
            ->createSalesOrderItemSaver()
            ->saveSalesOrderGiftCardItems($quoteTransfer, $checkoutResponse);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $code
     *
     * @return bool
     */
    public function isUsed($code)
    {
        return $this->getFactory()
            ->createGiftCardReader()
            ->isUsed($code);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return void
     */
    public function replaceGiftCards($idSalesOrder)
    {
        $this->getFactory()
            ->createGiftCardReplacer()
            ->replaceGiftCards($idSalesOrder);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @deprecated Use {@link \Spryker\Zed\GiftCard\Business\GiftCardFacadeInterface::filterShipmentGroupMethods()} instead.
     *
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ShipmentMethodTransfer> $shipmentMethods
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ShipmentMethodTransfer>
     */
    public function filterShipmentMethods(ArrayObject $shipmentMethods, QuoteTransfer $quoteTransfer)
    {
        return $this->getFactory()
            ->createShipmentMethodFilter()
            ->filterShipmentMethods($shipmentMethods, $quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ShipmentMethodTransfer>
     */
    public function filterShipmentGroupMethods(ShipmentGroupTransfer $shipmentGroupTransfer): ArrayObject
    {
        return $this->getFactory()->createShipmentGroupMethodFilter()->filterShipmentMethods($shipmentGroupTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idSalesOrder
     *
     * @return array<\Generated\Shared\Transfer\GiftCardTransfer>
     */
    public function findGiftCardsByIdSalesOrder($idSalesOrder)
    {
        return $this->getFactory()
            ->createGiftCardReader()
            ->findGiftCardsByIdSalesOrder($idSalesOrder);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idSalesOrderItem
     *
     * @return \Generated\Shared\Transfer\GiftCardTransfer|null
     */
    public function findGiftCardByIdSalesOrderItem($idSalesOrderItem)
    {
        return $this->getFactory()
            ->createGiftCardReader()
            ->findGiftCardByIdSalesOrderItem($idSalesOrderItem);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param iterable<\Generated\Shared\Transfer\ShipmentGroupTransfer> $shipmentGroupCollection
     *
     * @return iterable<\Generated\Shared\Transfer\ShipmentGroupTransfer>
     */
    public function sanitizeShipmentGroupCollection(iterable $shipmentGroupCollection): iterable
    {
        return $this->getFactory()
            ->createShipmentGroupSanitizer()
            ->sanitizeShipmentGroupCollection($shipmentGroupCollection);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $cartCode
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addCartCode(QuoteTransfer $quoteTransfer, string $cartCode): QuoteTransfer
    {
        return $this->getFactory()
            ->createGiftCardCartCodeAdder()
            ->addCartCode($quoteTransfer, $cartCode);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $cartCode
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function removeCartCode(QuoteTransfer $quoteTransfer, string $cartCode): QuoteTransfer
    {
        return $this->getFactory()
            ->createGiftCardCartCodeRemover()
            ->removeCartCode($quoteTransfer, $cartCode);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function clearCartCodes(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()
            ->createGiftCardCartCodeClearer()
            ->clearCartCodes($quoteTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string $cartCode
     *
     * @return \Generated\Shared\Transfer\MessageTransfer|null
     */
    public function findOperationResponseMessage(QuoteTransfer $quoteTransfer, string $cartCode): ?MessageTransfer
    {
        return $this->getFactory()
            ->createGiftCardCartCodeOperationMessageFinder()
            ->findOperationResponseMessage($quoteTransfer, $cartCode);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return string
     */
    public function buildPaymentMapKey(PaymentTransfer $paymentTransfer): string
    {
        return $this->getFactory()->createPaymentMapKeyBuilder()->buildMapKey($paymentTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     *
     * @return void
     */
    public function createGiftCardPaymentsFromQuote(
        QuoteTransfer $quoteTransfer,
        SaveOrderTransfer $saveOrderTransfer
    ): void {
        $this->getFactory()
            ->createSalesOrderPaymentSaver()
            ->saveGiftCardOrderPayments($quoteTransfer, $saveOrderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentGiftCardCollectionDeleteCriteriaTransfer $paymentGiftCardCollectionDeleteCriteriaTransfer
     *
     * @return void
     */
    public function deletePaymentGiftCardCollection(
        PaymentGiftCardCollectionDeleteCriteriaTransfer $paymentGiftCardCollectionDeleteCriteriaTransfer
    ): void {
        $this->getFactory()
            ->createPaymentGiftCardDeleter()
            ->deletePaymentGiftCardCollection($paymentGiftCardCollectionDeleteCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\SalesOrderItemGiftCardCollectionDeleteCriteriaTransfer $salesOrderItemGiftCardCollectionDeleteCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\SalesOrderItemGiftCardCollectionResponseTransfer
     */
    public function deleteSalesOrderItemGiftCardCollection(
        SalesOrderItemGiftCardCollectionDeleteCriteriaTransfer $salesOrderItemGiftCardCollectionDeleteCriteriaTransfer
    ): SalesOrderItemGiftCardCollectionResponseTransfer {
        return $this->getFactory()
            ->createSalesOrderItemGiftCardDeleter()
            ->deleteSalesOrderItemGiftCardCollection($salesOrderItemGiftCardCollectionDeleteCriteriaTransfer);
    }
}
