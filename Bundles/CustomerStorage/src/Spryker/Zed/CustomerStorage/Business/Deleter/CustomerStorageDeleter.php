<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerStorage\Business\Deleter;

use DateInterval;
use DateTime;
use Generated\Shared\Transfer\InvalidatedCustomerCollectionDeleteCriteriaTransfer;
use Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Zed\CustomerStorage\CustomerStorageConfig;
use Spryker\Zed\CustomerStorage\Persistence\CustomerStorageEntityManagerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class CustomerStorageDeleter implements CustomerStorageDeleterInterface
{
    use TransactionTrait;

    /**
     * @var string
     */
    protected const DATE_TIME_FORMAT_CREATED_AT = 'Y-m-d H:i:s';

    /**
     * @var \Spryker\Zed\CustomerStorage\Persistence\CustomerStorageEntityManagerInterface
     */
    protected CustomerStorageEntityManagerInterface $customerStorageEntityManager;

    /**
     * @var \Spryker\Zed\CustomerStorage\CustomerStorageConfig
     */
    protected CustomerStorageConfig $customerStorageConfig;

    /**
     * @param \Spryker\Zed\CustomerStorage\Persistence\CustomerStorageEntityManagerInterface $customerStorageEntityManager
     * @param \Spryker\Zed\CustomerStorage\CustomerStorageConfig $customerStorageConfig
     */
    public function __construct(
        CustomerStorageEntityManagerInterface $customerStorageEntityManager,
        CustomerStorageConfig $customerStorageConfig
    ) {
        $this->customerStorageEntityManager = $customerStorageEntityManager;
        $this->customerStorageConfig = $customerStorageConfig;
    }

    /**
     * @return void
     */
    public function deleteExpiredCustomerInvalidatedStorage(): void
    {
        do {
            $invalidatedCustomerCollectionTransfer = $this->getTransactionHandler()
                ->handleTransaction(function (): InvalidatedCustomerCollectionTransfer {
                    return $this->executeDeleteExpiredCustomerInvalidatedStorageTransaction();
                });
        } while ($invalidatedCustomerCollectionTransfer->getInvalidatedCustomers()->count() >= $this->customerStorageConfig->getBatchSizeLimit());
    }

    /**
     * @return \Generated\Shared\Transfer\InvalidatedCustomerCollectionTransfer
     */
    protected function executeDeleteExpiredCustomerInvalidatedStorageTransaction(): InvalidatedCustomerCollectionTransfer
    {
        $invalidatedCustomerCollectionDeleteCriteriaTransfer = $this->createInvalidatedCustomerCollectionDeleteCriteriaTransfer(
            $this->getCreatedAt(),
            $this->createPaginationTransfer(),
        );

        return $this->customerStorageEntityManager->deleteInvalidatedCustomerCollection(
            $invalidatedCustomerCollectionDeleteCriteriaTransfer,
        );
    }

    /**
     * @return \DateTime
     */
    protected function getCreatedAt(): DateTime
    {
        $customerInvalidatedStorageLifetimeInterval = new DateInterval(
            $this->customerStorageConfig->getCustomerInvalidatedStorageRecordLifeTime(),
        );

        return (new DateTime())->sub($customerInvalidatedStorageLifetimeInterval);
    }

    /**
     * @param \DateTime $createdAt
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Generated\Shared\Transfer\InvalidatedCustomerCollectionDeleteCriteriaTransfer
     */
    protected function createInvalidatedCustomerCollectionDeleteCriteriaTransfer(
        DateTime $createdAt,
        PaginationTransfer $paginationTransfer
    ): InvalidatedCustomerCollectionDeleteCriteriaTransfer {
        return (new InvalidatedCustomerCollectionDeleteCriteriaTransfer())
            ->setCreatedAt($createdAt->format(static::DATE_TIME_FORMAT_CREATED_AT))
            ->setPagination($paginationTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    protected function createPaginationTransfer(): PaginationTransfer
    {
        return (new PaginationTransfer())
            ->setOffset(0)
            ->setLimit($this->customerStorageConfig->getBatchSizeLimit());
    }
}
