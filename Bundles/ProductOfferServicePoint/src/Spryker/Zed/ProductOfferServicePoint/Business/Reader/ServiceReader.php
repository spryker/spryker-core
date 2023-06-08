<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Business\Reader;

use Generated\Shared\Transfer\ServiceCollectionTransfer;
use Generated\Shared\Transfer\ServiceConditionsTransfer;
use Generated\Shared\Transfer\ServiceCriteriaTransfer;
use Spryker\Zed\ProductOfferServicePoint\Dependency\Facade\ProductOfferServicePointToServicePointFacadeInterface;

class ServiceReader implements ServiceReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Dependency\Facade\ProductOfferServicePointToServicePointFacadeInterface
     */
    protected ProductOfferServicePointToServicePointFacadeInterface $servicePointFacade;

    /**
     * @param \Spryker\Zed\ProductOfferServicePoint\Dependency\Facade\ProductOfferServicePointToServicePointFacadeInterface $servicePointFacade
     */
    public function __construct(
        ProductOfferServicePointToServicePointFacadeInterface $servicePointFacade
    ) {
        $this->servicePointFacade = $servicePointFacade;
    }

    /**
     * @param list<string> $serviceUuids
     *
     * @return \Generated\Shared\Transfer\ServiceCollectionTransfer
     */
    public function getServiceCollectionByServiceUuids(array $serviceUuids): ServiceCollectionTransfer
    {
        $serviceConditionsTransfer = (new ServiceConditionsTransfer())
            ->setUuids($serviceUuids);
        $serviceCriteriaTransfer = (new ServiceCriteriaTransfer())
            ->setServiceConditions($serviceConditionsTransfer);

        return $this->servicePointFacade->getServiceCollection($serviceCriteriaTransfer);
    }
}
