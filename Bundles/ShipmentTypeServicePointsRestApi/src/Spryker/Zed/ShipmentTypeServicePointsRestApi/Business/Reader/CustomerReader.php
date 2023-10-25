<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentTypeServicePointsRestApi\Business\Reader;

use Generated\Shared\Transfer\CustomerCriteriaTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\ShipmentTypeServicePointsRestApi\Dependency\Facade\ShipmentTypeServicePointsRestApiToCustomerFacadeInterface;

class CustomerReader implements CustomerReaderInterface
{
    /**
     * @var \Spryker\Zed\ShipmentTypeServicePointsRestApi\Dependency\Facade\ShipmentTypeServicePointsRestApiToCustomerFacadeInterface
     */
    protected ShipmentTypeServicePointsRestApiToCustomerFacadeInterface $customerFacade;

    /**
     * @param \Spryker\Zed\ShipmentTypeServicePointsRestApi\Dependency\Facade\ShipmentTypeServicePointsRestApiToCustomerFacadeInterface $customerFacade
     */
    public function __construct(ShipmentTypeServicePointsRestApiToCustomerFacadeInterface $customerFacade)
    {
        $this->customerFacade = $customerFacade;
    }

    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function findCustomerTransferByCustomerReference(string $customerReference): ?CustomerTransfer
    {
        $customerCriteriaTransfer = $this->createCustomerCriteriaTransfer($customerReference);

        return $this->customerFacade->getCustomerByCriteria($customerCriteriaTransfer)->getCustomerTransfer();
    }

    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\CustomerCriteriaTransfer
     */
    protected function createCustomerCriteriaTransfer(string $customerReference): CustomerCriteriaTransfer
    {
        return (new CustomerCriteriaTransfer())->setCustomerReference($customerReference);
    }
}
