<?php

namespace Spryker\Client\Offer\Model\Hydrator;

use ArrayObject;

interface OfferHydratorInterface
{
    /**
     * @param \ArrayObject $offers
     *
     * @return \ArrayObject
     */
    public function hydrateQuoteWithCustomer(ArrayObject $offers): ArrayObject;
}
