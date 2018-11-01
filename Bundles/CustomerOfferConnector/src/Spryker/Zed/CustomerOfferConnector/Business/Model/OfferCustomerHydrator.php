<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerOfferConnector\Business\Model;

use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Zed\CustomerOfferConnector\Dependency\Facade\CustomerOfferConnectorToCustomerFacadeInterface;

class OfferCustomerHydrator implements OfferCustomerHydratorInterface
{
    /**
     * @var \Spryker\Zed\CustomerOfferConnector\Dependency\Facade\CustomerOfferConnectorToCustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @param \Spryker\Zed\CustomerOfferConnector\Dependency\Facade\CustomerOfferConnectorToCustomerFacadeInterface $customerFacade
     */
    public function __construct(CustomerOfferConnectorToCustomerFacadeInterface $customerFacade)
    {
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function hydrate(OfferTransfer $offerTransfer): OfferTransfer
    {
        $customerTransfer = $this->customerFacade->findByReference($offerTransfer->getCustomerReference());
        $offerTransfer->setCustomer($customerTransfer);

        return $offerTransfer;
    }
}
