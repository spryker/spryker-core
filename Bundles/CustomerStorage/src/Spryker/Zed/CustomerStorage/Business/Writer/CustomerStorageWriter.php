<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerStorage\Business\Writer;

use DateTime;
use Generated\Shared\Transfer\CustomerCriteriaFilterTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\InvalidatedCustomerTransfer;
use Spryker\Zed\CustomerStorage\Business\Mapper\CustomerStorageMapperInterface;
use Spryker\Zed\CustomerStorage\Dependency\Facade\CustomerStorageToCustomerFacadeInterface;
use Spryker\Zed\CustomerStorage\Persistence\CustomerStorageEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CustomerStorageWriter implements CustomerStorageWriterInterface
{
    use TransactionTrait;

    /**
     * @uses \Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap::COL_PASSWORD
     *
     * @var string
     */
    protected const COL_PASSWORD = 'spy_customer.password';

    /**
     * @var string
     */
    protected const DATE_TIME_FORMAT_PASSWORD_UPDATED_AT = 'Y-m-d H:i:s.u';

    /**
     * @var \Spryker\Zed\CustomerStorage\Business\Mapper\CustomerStorageMapperInterface
     */
    protected CustomerStorageMapperInterface $customerStorageMapper;

    /**
     * @var \Spryker\Zed\CustomerStorage\Dependency\Facade\CustomerStorageToCustomerFacadeInterface
     */
    protected CustomerStorageToCustomerFacadeInterface $customerFacade;

    /**
     * @var \Spryker\Zed\CustomerStorage\Persistence\CustomerStorageEntityManagerInterface
     */
    protected CustomerStorageEntityManagerInterface $customerStorageEntityManager;

    /**
     * @param \Spryker\Zed\CustomerStorage\Business\Mapper\CustomerStorageMapperInterface $customerStorageMapper
     * @param \Spryker\Zed\CustomerStorage\Dependency\Facade\CustomerStorageToCustomerFacadeInterface $customerFacade
     * @param \Spryker\Zed\CustomerStorage\Persistence\CustomerStorageEntityManagerInterface $customerStorageEntityManager
     */
    public function __construct(
        CustomerStorageMapperInterface $customerStorageMapper,
        CustomerStorageToCustomerFacadeInterface $customerFacade,
        CustomerStorageEntityManagerInterface $customerStorageEntityManager
    ) {
        $this->customerStorageMapper = $customerStorageMapper;
        $this->customerFacade = $customerFacade;
        $this->customerStorageEntityManager = $customerStorageEntityManager;
    }

    /**
     * @param array<int, \Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    public function writeCustomerInvalidatedStorageCollectionByCustomerEvents(array $eventEntityTransfers): void
    {
        $customerCollectionTransfer = $this->customerFacade->getCustomerCollectionByCriteria(
            $this->createCustomerCriteriaFilterTransfer($eventEntityTransfers),
        );

        $customerCollectionIndexedByIdCustomer = $this->customerStorageMapper->mapCustomerCollectionTransferToCustomerCollectionArrayIndexedByIdCustomer(
            $customerCollectionTransfer,
            [],
        );

        $this->getTransactionHandler()->handleTransaction(function () use ($customerCollectionIndexedByIdCustomer, $eventEntityTransfers): void {
            $this->writeCollection($eventEntityTransfers, $customerCollectionIndexedByIdCustomer);
        });
    }

    /**
     * @param array<int, \Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param array<int, \Generated\Shared\Transfer\CustomerTransfer> $customerCollectionIndexedByIdCustomer
     *
     * @return void
     */
    protected function writeCollection(
        array $eventEntityTransfers,
        array $customerCollectionIndexedByIdCustomer
    ): void {
        foreach ($eventEntityTransfers as $eventEntityTransfer) {
            $customerTransfer = $customerCollectionIndexedByIdCustomer[$eventEntityTransfer->getIdOrFail()] ?? null;
            if ($customerTransfer === null) {
                continue;
            }

            $this->customerStorageEntityManager->saveCustomerInvalidatedStorage(
                $this->createInvalidatedCustomerTransfer($eventEntityTransfer, $customerTransfer),
            );
        }
    }

    /**
     * @param array<int, \Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return \Generated\Shared\Transfer\CustomerCriteriaFilterTransfer
     */
    protected function createCustomerCriteriaFilterTransfer(array $eventEntityTransfers): CustomerCriteriaFilterTransfer
    {
        $customerIds = [];
        foreach ($eventEntityTransfers as $eventEntityTransfer) {
            $customerId = $eventEntityTransfer->getId();

            if ($customerId !== null) {
                $customerIds[] = $customerId;
            }
        }

        return $this->customerStorageMapper->mapCustomerIdsToCustomerCriteriaFilterTransfer(
            $customerIds,
            (new CustomerCriteriaFilterTransfer())->setHasAnonymizedAt(true),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer $eventEntityTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\InvalidatedCustomerTransfer
     */
    protected function createInvalidatedCustomerTransfer(
        EventEntityTransfer $eventEntityTransfer,
        CustomerTransfer $customerTransfer
    ): InvalidatedCustomerTransfer {
        return (new InvalidatedCustomerTransfer())
            ->setCustomerReference(
                $customerTransfer->getCustomerReference(),
            )
            ->setAnonymizedAt(
                $customerTransfer->getAnonymizedAt(),
            )
            ->setPasswordUpdatedAt(
                $this->isPasswordUpdated($eventEntityTransfer) ? (new DateTime())->format(static::DATE_TIME_FORMAT_PASSWORD_UPDATED_AT) : null,
            );
    }

    /**
     * @param \Generated\Shared\Transfer\EventEntityTransfer $eventEntityTransfer
     *
     * @return bool
     */
    protected function isPasswordUpdated(EventEntityTransfer $eventEntityTransfer): bool
    {
        return in_array(static::COL_PASSWORD, $eventEntityTransfer->getModifiedColumns(), true);
    }
}
