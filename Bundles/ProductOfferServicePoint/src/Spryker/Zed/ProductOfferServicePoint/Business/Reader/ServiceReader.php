<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Business\Reader;

use Generated\Shared\Transfer\IterableProductOfferServicesCriteriaTransfer;
use Generated\Shared\Transfer\ServiceCollectionTransfer;
use Generated\Shared\Transfer\ServiceConditionsTransfer;
use Generated\Shared\Transfer\ServiceCriteriaTransfer;
use Spryker\Zed\ProductOfferServicePoint\Business\Mapper\ProductOfferServiceMapperInterface;
use Spryker\Zed\ProductOfferServicePoint\Dependency\Facade\ProductOfferServicePointToServicePointFacadeInterface;

class ServiceReader implements ServiceReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Dependency\Facade\ProductOfferServicePointToServicePointFacadeInterface
     */
    protected ProductOfferServicePointToServicePointFacadeInterface $servicePointFacade;

    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Business\Mapper\ProductOfferServiceMapperInterface
     */
    protected ProductOfferServiceMapperInterface $productOfferServiceMapper;

    /**
     * @param \Spryker\Zed\ProductOfferServicePoint\Dependency\Facade\ProductOfferServicePointToServicePointFacadeInterface $servicePointFacade
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Mapper\ProductOfferServiceMapperInterface $productOfferServiceMapper
     */
    public function __construct(
        ProductOfferServicePointToServicePointFacadeInterface $servicePointFacade,
        ProductOfferServiceMapperInterface $productOfferServiceMapper
    ) {
        $this->servicePointFacade = $servicePointFacade;
        $this->productOfferServiceMapper = $productOfferServiceMapper;
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

    /**
     * @param list<int> $serviceIds
     *
     * @return \Generated\Shared\Transfer\ServiceCollectionTransfer
     */
    public function getServiceCollectionByServiceIds(array $serviceIds): ServiceCollectionTransfer
    {
        $serviceCriteriaTransfer = (new ServiceCriteriaTransfer())->setServiceConditions(
            (new ServiceConditionsTransfer())->setServiceIds($serviceIds),
        );

        return $this->servicePointFacade->getServiceCollection($serviceCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceConditionsTransfer $serviceConditionsTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceCollectionTransfer
     */
    public function getServiceCollectionByServiceConditions(ServiceConditionsTransfer $serviceConditionsTransfer): ServiceCollectionTransfer
    {
        $serviceCriteriaTransfer = (new ServiceCriteriaTransfer())->setServiceConditions($serviceConditionsTransfer);

        return $this->servicePointFacade->getServiceCollection($serviceCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\IterableProductOfferServicesCriteriaTransfer $iterableProductOfferServicesCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceCollectionTransfer
     */
    public function getServiceCollectionByIterableProductOfferServicesCriteria(
        IterableProductOfferServicesCriteriaTransfer $iterableProductOfferServicesCriteriaTransfer
    ): ServiceCollectionTransfer {
        $serviceCriteriaTransfer = $this->productOfferServiceMapper->mapIterableProductOfferServicesCriteriaTransferToServiceCriteriaTransfer(
            $iterableProductOfferServicesCriteriaTransfer,
            new ServiceCriteriaTransfer(),
        );

        return $this->servicePointFacade->getServiceCollection($serviceCriteriaTransfer);
    }
}
