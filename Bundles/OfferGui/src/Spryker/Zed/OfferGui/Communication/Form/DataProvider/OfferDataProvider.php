<?php

namespace Spryker\Zed\OfferGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomerQuery;
use Spryker\Zed\Offer\Business\OfferFacadeInterface;
use Spryker\Zed\OfferGui\Communication\Form\Offer\OfferType;
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
            OfferType::OPTION_CUSTOMER_LIST => $this->getCustomerList()
        ];
    }

    /**
     * @param int|null $idOffer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function getData(OfferTransfer $offerTransfer)
    {
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
}