<?php

namespace Spryker\Client\Offer\Model\Hydrator;

use ArrayObject;
use Spryker\Client\Offer\Dependency\Client\OfferToCustomerClientInterface;

class OfferHydrator implements OfferHydratorInterface
{
    /**
     * @var \Spryker\Client\Offer\Dependency\Client\OfferToCustomerClientInterface
     */
    protected $customerClient;

    /**
     * @param \Spryker\Client\Offer\Dependency\Client\OfferToCustomerClientInterface $customerClient
     */
    public function __construct(OfferToCustomerClientInterface $customerClient)
    {
        $this->customerClient = $customerClient;
    }

    /**
     * @param \ArrayObject $offers
     *
     * @return \ArrayObject
     */
    public function hydrateQuoteWithCustomer(ArrayObject $offers): ArrayObject
    {
        foreach ($offers as $offer) {
            $offer->getQuote()->setCustomer($this->customerClient->getCustomer());
        }

        return $offers;
    }
}
