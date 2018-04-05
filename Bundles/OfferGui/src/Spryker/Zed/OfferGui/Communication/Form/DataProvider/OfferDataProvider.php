<?php

namespace Spryker\Zed\OfferGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Spryker\Zed\Currency\Business\CurrencyFacadeInterface;
use Spryker\Zed\Kernel\Locator;
use Spryker\Zed\Offer\Business\OfferFacadeInterface;
use Spryker\Zed\OfferGui\Communication\Form\Offer\EditOfferType;
use Spryker\Zed\OfferGui\Dependency\Facade\OfferGuiToOfferFacadeInterface;

class OfferDataProvider
{
    /**
     * @var OfferFacadeInterface
     */
    protected $offerFacade;

    /**
     * @param OfferGuiToOfferFacadeInterface $offerFacade
     */
    public function __construct(OfferGuiToOfferFacadeInterface $offerFacade)
    {
        $this->offerFacade = $offerFacade;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [
            'data_class' => OfferTransfer::class,
            EditOfferType::OPTION_CUSTOMER_LIST => $this->getCustomerList(),
            EditOfferType::OPTION_STORE_CURRENCY_LIST => $this->getStoreCurrencyChoiceList()
        ];
    }

    /**
     * @param OfferTransfer $offerTransfer
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
                        ->setItems(new \ArrayObject())
                        ->setShippingAddress(new AddressTransfer())
                        ->setBillingAddress(new AddressTransfer())
                        ->setCartRuleDiscounts(new \ArrayObject())
                        ->setVoucherDiscounts(new \ArrayObject())
                );
        }

        $offerTransfer->getQuote()
            ->setIncomingItems(new \ArrayObject([
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
        //todo: move to DP
        $customerCollection = SpyCustomerQuery::create()->find();
        $customerList = [];

        /** @var \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity */
        foreach ($customerCollection as $customerEntity) {
            $customerName = $customerEntity->getLastName()
                . ' '
                . $customerEntity->getFirstName()
                . ' [' . $customerEntity->getEmail() . ']';

            $customerList[$customerEntity->getCustomerReference()] = $customerName;
        }

        return $customerList;
    }

    /**
     * @return array
     */
    protected function getStoreCurrencyChoiceList()
    {
        /** @var CurrencyFacadeInterface $currencyFacade */
        $currencyFacade = Locator::getInstance()->currency()->facade();

        $storeWithCurrencyTransfers = $currencyFacade->getAllStoresWithCurrencies();
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
}