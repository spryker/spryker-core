<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Creator;

use ArrayObject;
use Generated\Shared\Transfer\ServiceTypeCollectionRequestTransfer;
use Generated\Shared\Transfer\ServiceTypeCollectionResponseTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ServicePoint\Business\Filter\ServiceTypeFilterInterface;
use Spryker\Zed\ServicePoint\Business\Validator\ServiceTypeValidatorInterface;
use Spryker\Zed\ServicePoint\Persistence\ServicePointEntityManagerInterface;

class ServiceTypeCreator implements ServiceTypeCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ServicePoint\Persistence\ServicePointEntityManagerInterface
     */
    protected ServicePointEntityManagerInterface $servicePointEntityManager;

    /**
     * @var \Spryker\Zed\ServicePoint\Business\Validator\ServiceTypeValidatorInterface
     */
    protected ServiceTypeValidatorInterface $serviceTypeValidator;

    /**
     * @var \Spryker\Zed\ServicePoint\Business\Filter\ServiceTypeFilterInterface
     */
    protected ServiceTypeFilterInterface $serviceTypeFilter;

    /**
     * @param \Spryker\Zed\ServicePoint\Persistence\ServicePointEntityManagerInterface $servicePointEntityManager
     * @param \Spryker\Zed\ServicePoint\Business\Validator\ServiceTypeValidatorInterface $serviceTypeValidator
     * @param \Spryker\Zed\ServicePoint\Business\Filter\ServiceTypeFilterInterface $serviceTypeFilter
     */
    public function __construct(
        ServicePointEntityManagerInterface $servicePointEntityManager,
        ServiceTypeValidatorInterface $serviceTypeValidator,
        ServiceTypeFilterInterface $serviceTypeFilter
    ) {
        $this->servicePointEntityManager = $servicePointEntityManager;
        $this->serviceTypeValidator = $serviceTypeValidator;
        $this->serviceTypeFilter = $serviceTypeFilter;
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTypeCollectionRequestTransfer $serviceTypeCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeCollectionResponseTransfer
     */
    public function createServiceTypeCollection(
        ServiceTypeCollectionRequestTransfer $serviceTypeCollectionRequestTransfer
    ): ServiceTypeCollectionResponseTransfer {
        $this->assertRequiredFields($serviceTypeCollectionRequestTransfer);

        $serviceTypeCollectionResponseTransfer = (new ServiceTypeCollectionResponseTransfer())
            ->setServiceTypes($serviceTypeCollectionRequestTransfer->getServiceTypes());

        $serviceTypeCollectionResponseTransfer = $this->serviceTypeValidator->validate($serviceTypeCollectionResponseTransfer);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $errorTransfers */
        $errorTransfers = $serviceTypeCollectionResponseTransfer->getErrors();

        if ($serviceTypeCollectionRequestTransfer->getIsTransactional() && $errorTransfers->count()) {
            return $serviceTypeCollectionResponseTransfer;
        }

        [$validServiceTypeTransfers, $invalidServiceTypeTransfers] = $this->serviceTypeFilter->filterServiceTypesByValidity(
            $serviceTypeCollectionResponseTransfer,
        );

        if ($validServiceTypeTransfers->count()) {
            $validServiceTypeTransfers = $this->getTransactionHandler()->handleTransaction(function () use ($validServiceTypeTransfers) {
                return $this->executeCreateServiceTypeCollectionTransaction($validServiceTypeTransfers);
            });
        }

        $serviceTypeTransfers = $this->serviceTypeFilter->mergeServiceTypes(
            $validServiceTypeTransfers,
            $invalidServiceTypeTransfers,
        );

        return $serviceTypeCollectionResponseTransfer->setServiceTypes($serviceTypeTransfers);
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTypeTransfer> $serviceTypeTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ServiceTypeTransfer>
     */
    protected function executeCreateServiceTypeCollectionTransaction(
        ArrayObject $serviceTypeTransfers
    ): ArrayObject {
        $persistedServiceTypeTransfers = new ArrayObject();

        foreach ($serviceTypeTransfers as $entityIdentifier => $serviceTypeTransfer) {
            $serviceTypeTransfer = $this->servicePointEntityManager->createServiceType($serviceTypeTransfer);
            $persistedServiceTypeTransfers->offsetSet(
                $entityIdentifier,
                $serviceTypeTransfer,
            );
        }

        return $persistedServiceTypeTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ServiceTypeCollectionRequestTransfer $serviceTypeCollectionRequestTransfer
     *
     * @return void
     */
    protected function assertRequiredFields(ServiceTypeCollectionRequestTransfer $serviceTypeCollectionRequestTransfer): void
    {
        $serviceTypeCollectionRequestTransfer
            ->requireIsTransactional()
            ->requireServiceTypes();

        foreach ($serviceTypeCollectionRequestTransfer->getServiceTypes() as $serviceTypeTransfer) {
            $serviceTypeTransfer
                ->requireName()
                ->requireKey();
        }
    }
}
