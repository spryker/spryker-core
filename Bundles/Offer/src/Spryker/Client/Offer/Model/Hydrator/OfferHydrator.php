<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

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
     * @param \ArrayObject<int, \Generated\Shared\Transfer\OfferTransfer> $offerTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\OfferTransfer>
     */
    public function hydrateQuoteWithCustomer(ArrayObject $offerTransfers): ArrayObject
    {
        foreach ($offerTransfers as $offerTransfer) {
            $offerTransfer->getQuote()->setCustomer($this->customerClient->getCustomer());
        }

        return $offerTransfers;
    }
}
