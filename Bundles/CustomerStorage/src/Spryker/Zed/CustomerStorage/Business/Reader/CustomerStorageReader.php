<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerStorage\Business\Reader;

use Generated\Shared\Transfer\CustomerCollectionTransfer;
use Generated\Shared\Transfer\CustomerCriteriaFilterTransfer;
use Generated\Shared\Transfer\InvalidatedCustomerConditionsTransfer;
use Generated\Shared\Transfer\InvalidatedCustomerCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Zed\CustomerStorage\Business\Mapper\CustomerStorageMapperInterface;
use Spryker\Zed\CustomerStorage\Dependency\Facade\CustomerStorageToCustomerFacadeInterface;
use Spryker\Zed\CustomerStorage\Persistence\CustomerStorageRepositoryInterface;

class CustomerStorageReader implements CustomerStorageReaderInterface
{
    /**
     * @var \Spryker\Zed\CustomerStorage\Business\Mapper\CustomerStorageMapperInterface
     */
    protected CustomerStorageMapperInterface $customerStorageMapper;

    /**
     * @var \Spryker\Zed\CustomerStorage\Dependency\Facade\CustomerStorageToCustomerFacadeInterface
     */
    protected CustomerStorageToCustomerFacadeInterface $customerFacade;

    /**
     * @var \Spryker\Zed\CustomerStorage\Persistence\CustomerStorageRepositoryInterface
     */
    protected CustomerStorageRepositoryInterface $customerStorageRepository;

    /**
     * @param \Spryker\Zed\CustomerStorage\Business\Mapper\CustomerStorageMapperInterface $customerStorageMapper
     * @param \Spryker\Zed\CustomerStorage\Dependency\Facade\CustomerStorageToCustomerFacadeInterface $customerFacade
     * @param \Spryker\Zed\CustomerStorage\Persistence\CustomerStorageRepositoryInterface $customerStorageRepository
     */
    public function __construct(
        CustomerStorageMapperInterface $customerStorageMapper,
        CustomerStorageToCustomerFacadeInterface $customerFacade,
        CustomerStorageRepositoryInterface $customerStorageRepository
    ) {
        $this->customerStorageMapper = $customerStorageMapper;
        $this->customerFacade = $customerFacade;
        $this->customerStorageRepository = $customerStorageRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     * @param array<int, int> $customerIds
     *
     * @return array<int, \Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getSynchronizationDataTransferCollection(
        PaginationTransfer $paginationTransfer,
        array $customerIds
    ): array {
        $customerCriteriaFilterTransfer = $this->customerStorageMapper->mapCustomerIdsToCustomerCriteriaFilterTransfer(
            $customerIds,
            (new CustomerCriteriaFilterTransfer())->setHasAnonymizedAt(true),
        );

        $invalidatedCustomerCriteriaTransfer = $this->createInvalidatedCustomerCriteriaTransfer(
            $this->customerFacade->getCustomerCollectionByCriteria($customerCriteriaFilterTransfer),
            $paginationTransfer,
        );

        return $this->customerStorageRepository->getInvalidatedCustomerSynchronizationDataTransferCollection(
            $invalidatedCustomerCriteriaTransfer,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerCollectionTransfer $customerCollectionTransfer
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Generated\Shared\Transfer\InvalidatedCustomerCriteriaTransfer
     */
    protected function createInvalidatedCustomerCriteriaTransfer(
        CustomerCollectionTransfer $customerCollectionTransfer,
        PaginationTransfer $paginationTransfer
    ): InvalidatedCustomerCriteriaTransfer {
        $invalidatedCustomerConditionsTransfer = new InvalidatedCustomerConditionsTransfer();

        foreach ($customerCollectionTransfer->getCustomers() as $customerTransfer) {
            $invalidatedCustomerConditionsTransfer->addCustomerReference(
                $customerTransfer->getCustomerReferenceOrFail(),
            );
        }

        return (new InvalidatedCustomerCriteriaTransfer())
            ->setInvalidatedCustomerConditions($invalidatedCustomerConditionsTransfer)
            ->setPagination($paginationTransfer);
    }
}
