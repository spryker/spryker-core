<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Creator;

use ArrayObject;
use Generated\Shared\Transfer\ServicePointServiceCollectionRequestTransfer;
use Generated\Shared\Transfer\ServicePointServiceCollectionResponseTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ServicePoint\Business\Expander\ServicePointServiceServicePointExpanderInterface;
use Spryker\Zed\ServicePoint\Business\Expander\ServicePointServiceServiceTypeExpanderInterface;
use Spryker\Zed\ServicePoint\Business\Filter\ServicePointServiceFilterInterface;
use Spryker\Zed\ServicePoint\Business\Validator\ServicePointServiceValidatorInterface;
use Spryker\Zed\ServicePoint\Persistence\ServicePointEntityManagerInterface;

class ServicePointServiceCreator implements ServicePointServiceCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ServicePoint\Persistence\ServicePointEntityManagerInterface
     */
    protected ServicePointEntityManagerInterface $servicePointEntityManager;

    /**
     * @var \Spryker\Zed\ServicePoint\Business\Validator\ServicePointServiceValidatorInterface
     */
    protected ServicePointServiceValidatorInterface $servicePointServiceValidator;

    /**
     * @var \Spryker\Zed\ServicePoint\Business\Filter\ServicePointServiceFilterInterface
     */
    protected ServicePointServiceFilterInterface $servicePointServiceFilter;

    /**
     * @var \Spryker\Zed\ServicePoint\Business\Expander\ServicePointServiceServicePointExpanderInterface
     */
    protected ServicePointServiceServicePointExpanderInterface $servicePointServiceServicePointExpander;

    /**
     * @var \Spryker\Zed\ServicePoint\Business\Expander\ServicePointServiceServiceTypeExpanderInterface
     */
    protected ServicePointServiceServiceTypeExpanderInterface $servicePointServiceServiceTypeExpander;

    /**
     * @param \Spryker\Zed\ServicePoint\Persistence\ServicePointEntityManagerInterface $servicePointEntityManager
     * @param \Spryker\Zed\ServicePoint\Business\Validator\ServicePointServiceValidatorInterface $servicePointServiceValidator
     * @param \Spryker\Zed\ServicePoint\Business\Filter\ServicePointServiceFilterInterface $servicePointServiceFilter
     * @param \Spryker\Zed\ServicePoint\Business\Expander\ServicePointServiceServicePointExpanderInterface $servicePointServiceServicePointExpander
     * @param \Spryker\Zed\ServicePoint\Business\Expander\ServicePointServiceServiceTypeExpanderInterface $servicePointServiceServiceTypeExpander
     */
    public function __construct(
        ServicePointEntityManagerInterface $servicePointEntityManager,
        ServicePointServiceValidatorInterface $servicePointServiceValidator,
        ServicePointServiceFilterInterface $servicePointServiceFilter,
        ServicePointServiceServicePointExpanderInterface $servicePointServiceServicePointExpander,
        ServicePointServiceServiceTypeExpanderInterface $servicePointServiceServiceTypeExpander
    ) {
        $this->servicePointEntityManager = $servicePointEntityManager;
        $this->servicePointServiceValidator = $servicePointServiceValidator;
        $this->servicePointServiceFilter = $servicePointServiceFilter;
        $this->servicePointServiceServicePointExpander = $servicePointServiceServicePointExpander;
        $this->servicePointServiceServiceTypeExpander = $servicePointServiceServiceTypeExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointServiceCollectionRequestTransfer $servicePointServiceCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointServiceCollectionResponseTransfer
     */
    public function createServicePointServiceCollection(
        ServicePointServiceCollectionRequestTransfer $servicePointServiceCollectionRequestTransfer
    ): ServicePointServiceCollectionResponseTransfer {
        $this->assertRequiredFields($servicePointServiceCollectionRequestTransfer);

        $servicePointServiceCollectionResponseTransfer = (new ServicePointServiceCollectionResponseTransfer())
            ->setServicePointServices($servicePointServiceCollectionRequestTransfer->getServicePointServices());

        $servicePointServiceCollectionResponseTransfer = $this->servicePointServiceValidator->validate($servicePointServiceCollectionResponseTransfer);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers */
        $errorTransfers = $servicePointServiceCollectionResponseTransfer->getErrors();

        if ($servicePointServiceCollectionRequestTransfer->getIsTransactional() && $errorTransfers->count()) {
            return $servicePointServiceCollectionResponseTransfer;
        }

        [$validServicePointServiceTransfers, $invalidServicePointServiceTransfers] = $this->servicePointServiceFilter
            ->filterServicePointServicesByValidity($servicePointServiceCollectionResponseTransfer);

        if ($validServicePointServiceTransfers->count()) {
            $validServicePointServiceTransfers = $this->expandServicePointServiceTransfersWithRelations($validServicePointServiceTransfers);
            $validServicePointServiceTransfers = $this->getTransactionHandler()->handleTransaction(function () use ($validServicePointServiceTransfers) {
                return $this->executeCreateServicePointServiceCollectionTransaction($validServicePointServiceTransfers);
            });
        }

        return $servicePointServiceCollectionResponseTransfer->setServicePointServices(
            $this->servicePointServiceFilter->mergeServicePointServices($validServicePointServiceTransfers, $invalidServicePointServiceTransfers),
        );
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointServiceTransfer> $servicePointServiceTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointServiceTransfer>
     */
    protected function executeCreateServicePointServiceCollectionTransaction(
        ArrayObject $servicePointServiceTransfers
    ): ArrayObject {
        $persistedServicePointServiceTransfers = new ArrayObject();

        foreach ($servicePointServiceTransfers as $entityIdentifier => $servicePointServiceTransfer) {
            $servicePointServiceTransfer = $this->servicePointEntityManager->createServicePointService($servicePointServiceTransfer);

            $persistedServicePointServiceTransfers->offsetSet($entityIdentifier, $servicePointServiceTransfer);
        }

        return $persistedServicePointServiceTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ServicePointServiceCollectionRequestTransfer $servicePointServiceCollectionRequestTransfer
     *
     * @return void
     */
    protected function assertRequiredFields(ServicePointServiceCollectionRequestTransfer $servicePointServiceCollectionRequestTransfer): void
    {
        $servicePointServiceCollectionRequestTransfer
            ->requireIsTransactional()
            ->requireServicePointServices();

        foreach ($servicePointServiceCollectionRequestTransfer->getServicePointServices() as $servicePointServiceTransfer) {
            $servicePointServiceTransfer
                ->requireIsActive()
                ->requireKey()
                ->requireServicePoint()
                ->requireServiceType();

            $servicePointServiceTransfer->getServicePointOrFail()->requireUuid();
            $servicePointServiceTransfer->getServiceTypeOrFail()->requireUuid();
        }
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointServiceTransfer> $servicePointServiceTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ServicePointServiceTransfer>
     */
    protected function expandServicePointServiceTransfersWithRelations(
        ArrayObject $servicePointServiceTransfers
    ): ArrayObject {
        $servicePointServiceTransfers = $this->servicePointServiceServicePointExpander
            ->expandServicePointServiceTransfersWithServicePointRelations($servicePointServiceTransfers);

        return $this->servicePointServiceServiceTypeExpander
            ->expandServicePointServiceTransfersWithServiceTypeRelations($servicePointServiceTransfers);
    }
}
