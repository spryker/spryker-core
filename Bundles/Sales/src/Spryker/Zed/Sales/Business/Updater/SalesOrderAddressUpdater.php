<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Sales\Business\Updater;

use ArrayObject;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface;
use Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface;

class SalesOrderAddressUpdater implements SalesOrderAddressUpdaterInterface
{
    use TransactionTrait;

    /**
     * @param \Spryker\Zed\Sales\Persistence\SalesEntityManagerInterface $salesEntityManager
     * @param \Spryker\Zed\Sales\Dependency\Facade\SalesToCountryInterface $countryFacade
     */
    public function __construct(
        protected SalesEntityManagerInterface $salesEntityManager,
        protected SalesToCountryInterface $countryFacade
    ) {
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    public function updateSalesOrderAddressesByQuote(QuoteTransfer $quoteTransfer, OrderTransfer $orderTransfer): OrderTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($quoteTransfer, $orderTransfer) {
            return $this->executeUpdateSalesOrderAddressByQuoteTransaction($quoteTransfer, $orderTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function executeUpdateSalesOrderAddressByQuoteTransaction(QuoteTransfer $quoteTransfer, OrderTransfer $orderTransfer): OrderTransfer
    {
        $billingAddressTransfer = $this->updateSalesOrderAddress(
            $orderTransfer->getBillingAddressOrFail(),
            $quoteTransfer->getBillingAddressOrFail(),
        );
        $orderTransfer->setBillingAddress($billingAddressTransfer);

        return $this->updateSalesOrderShippingAddress($quoteTransfer, $orderTransfer);
    }

    /**
     * @deprecated Exists for backward compatibility reasons only. Will be removed in the next major.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function updateSalesOrderShippingAddress(QuoteTransfer $quoteTransfer, OrderTransfer $orderTransfer): OrderTransfer
    {
        $hasOrderShipping = $orderTransfer->getShippingAddress() !== null;
        $hasQuoteShipping = $this->hasValidQuoteShippingAddress($quoteTransfer);

        if (!$hasOrderShipping && !$hasQuoteShipping) {
            return $orderTransfer;
        }

        if (!$hasOrderShipping) {
            return $this->createShippingAddress($quoteTransfer, $orderTransfer);
        }

        if (!$hasQuoteShipping) {
            return $this->removeShippingAddress($orderTransfer);
        }

        return $this->updateExistingShippingAddress($quoteTransfer, $orderTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function createShippingAddress(QuoteTransfer $quoteTransfer, OrderTransfer $orderTransfer): OrderTransfer
    {
        $shippingAddressTransfer = $this->expandAddressWithCountry($quoteTransfer->getShippingAddress());
        $shippingAddressTransfer->setUuid(null);
        $shippingAddressTransfer = $this->salesEntityManager->createSalesOrderAddress($shippingAddressTransfer);

        $this->updateItemShippingAddresses($shippingAddressTransfer, $quoteTransfer->getItems());

        return $orderTransfer->setShippingAddress($shippingAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function removeShippingAddress(OrderTransfer $orderTransfer): OrderTransfer
    {
        $this->salesEntityManager->unsetSalesOrderShippingAddress(
            $orderTransfer->getShippingAddressOrFail()->getIdSalesOrderAddressOrFail(),
        );

        return $orderTransfer->setShippingAddress(null);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\OrderTransfer
     */
    protected function updateExistingShippingAddress(QuoteTransfer $quoteTransfer, OrderTransfer $orderTransfer): OrderTransfer
    {
        $shippingAddressTransfer = $this->updateSalesOrderAddress(
            $orderTransfer->getShippingAddressOrFail(),
            $quoteTransfer->getShippingAddressOrFail(),
        );
        $this->updateItemShippingAddresses($shippingAddressTransfer, $quoteTransfer->getItems());

        return $orderTransfer->setShippingAddress($shippingAddressTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer>
     */
    protected function updateItemShippingAddresses(
        AddressTransfer $addressTransfer,
        ArrayObject $itemTransfers
    ): ArrayObject {
        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getShipment() === null || $itemTransfer->getShipmentOrFail()->getShippingAddress() === null) {
                continue;
            }

            $itemTransfer->getShipmentOrFail()->setShippingAddress($addressTransfer);
        }

        return $itemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $persistedAddressTransfer
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function updateSalesOrderAddress(AddressTransfer $persistedAddressTransfer, AddressTransfer $addressTransfer): AddressTransfer
    {
        $addressTransfer->setIdSalesOrderAddress($persistedAddressTransfer->getIdSalesOrderAddressOrFail());
        $persistedAddressTransfer->fromArray($addressTransfer->modifiedToArray(), true);
        $persistedAddressTransfer = $this->expandAddressWithCountry($persistedAddressTransfer);

        return $this->salesEntityManager->updateSalesOrderAddress($this->cleanUpAddressUuid($persistedAddressTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function expandAddressWithCountry(AddressTransfer $addressTransfer): AddressTransfer
    {
        $countryTransfer = $this->countryFacade->getCountryByIso2Code($addressTransfer->getIso2CodeOrFail());

        return $addressTransfer
            ->setFkCountry($countryTransfer->getIdCountryOrFail())
            ->setCountry($countryTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function hasValidQuoteShippingAddress(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getShippingAddress() !== null && $quoteTransfer->getShippingAddressOrFail()->getAddress1() !== null;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function cleanUpAddressUuid(AddressTransfer $addressTransfer): AddressTransfer
    {
        return $addressTransfer->setUuid(null);
    }
}
