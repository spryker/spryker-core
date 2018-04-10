<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OfferGui\Communication\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\OfferGui\Communication\Form\Offer\EditOfferType;
use Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToCurrencyFacadeInterface;
use Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToCustomerFacadeInterface;

class OfferDataProvider
{
    /**
     * @var \Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToCurrencyFacadeInterface
     */
    protected $currencyFacade;

    /**
     * @var \Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @param \Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToCurrencyFacadeInterface $currencyFacade
     * @param \Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToCustomerFacadeInterface $customerFacade
     */
    public function __construct(
        OfferGuiToCurrencyFacadeInterface $currencyFacade,
        OfferGuiToCustomerFacadeInterface $customerFacade
    ) {
        $this->currencyFacade = $currencyFacade;
        $this->customerFacade = $customerFacade;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            'data_class' => OfferTransfer::class,
            EditOfferType::OPTION_CUSTOMER_LIST => $this->getCustomerList(),
            EditOfferType::OPTION_STORE_CURRENCY_LIST => $this->getStoreCurrencyChoiceList(),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function getData(OfferTransfer $offerTransfer)
    {
        if (!$offerTransfer->getQuote()) {
            $offerTransfer = (new OfferTransfer())
                ->setQuote(
                    (new QuoteTransfer())
                        ->setStore(new StoreTransfer())
                        ->setCurrency(new CurrencyTransfer())
                        ->setItems(new ArrayObject())
                        ->setShippingAddress(new AddressTransfer())
                        ->setBillingAddress(new AddressTransfer())
                        ->setCartRuleDiscounts(new ArrayObject())
                        ->setVoucherDiscounts(new ArrayObject())
                );
        }

        $offerTransfer->getQuote()
            ->setIncomingItems(new ArrayObject([
                new ItemTransfer(),
                new ItemTransfer(),
                new ItemTransfer(),
            ]));

        return $offerTransfer;
    }

    /**
     * @return array
     */
    protected function getCustomerList()
    {
        $customerCollection = $this->customerFacade->getCustomerCollection($this->createCustomerCollectionTransfer());
        $customerList = [];

        foreach ($customerCollection->getCustomers() as $customerTransfer) {
            $customerName = $customerTransfer->getLastName()
                . ' '
                . $customerTransfer->getFirstName()
                . ' [' . $customerTransfer->getEmail() . ']';

            $customerList[$customerTransfer->getCustomerReference()] = $customerName;
        }

        return $customerList;
    }

    /**
     * @return array
     */
    protected function getStoreCurrencyChoiceList()
    {
        $storeWithCurrencyTransfers = $this->currencyFacade->getAllStoresWithCurrencies();
        $storeList = [];

        foreach ($storeWithCurrencyTransfers as $storeWithCurrencyTransfer) {
            $storeTransfer = $storeWithCurrencyTransfer->getStore();

            foreach ($storeWithCurrencyTransfer->getCurrencies() as $currencyTransfer) {
                $label = $storeTransfer->getName() . ' - ' . $currencyTransfer->getName()
                    . ' [' . $currencyTransfer->getCode() . ']';
                $key = $storeTransfer->getName() . ';' . $currencyTransfer->getCode();

                $storeList[$key] = $label;
            }
        }

        return array_flip($storeList);
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerCollectionTransfer
     */
    protected function createCustomerCollectionTransfer(): CustomerCollectionTransfer
    {
        $customerCollectionTransfer = new CustomerCollectionTransfer();

        return $customerCollectionTransfer;
    }
}
