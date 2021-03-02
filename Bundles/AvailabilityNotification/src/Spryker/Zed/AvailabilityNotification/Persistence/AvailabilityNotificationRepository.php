<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityNotification\Persistence;

use Generated\Shared\Transfer\AvailabilityNotificationCriteriaTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionCollectionTransfer;
use Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilityNotificationSubscriptionQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Spryker\Zed\Kernel\Persistence\AbstractRepository;

/**
 * @method \Spryker\Zed\AvailabilityNotification\Persistence\AvailabilityNotificationPersistenceFactory getFactory()
 */
class AvailabilityNotificationRepository extends AbstractRepository implements AvailabilityNotificationRepositoryInterface
{
    /**
     * @param string $email
     * @param string $sku
     * @param int $fkStore
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer|null
     */
    public function findOneByEmailAndSku(
        string $email,
        string $sku,
        int $fkStore
    ): ?AvailabilityNotificationSubscriptionTransfer {
        $query = $this->querySubscription()
            ->filterByEmail($email)
            ->filterBySku($sku)
            ->filterByFkStore($fkStore);
        $query->setIgnoreCase(true);

        $availabilityNotificationSubscriptionEntity = $query->findOne();

        if ($availabilityNotificationSubscriptionEntity === null) {
            return null;
        }

        return $this->getFactory()->createAvailabilityNotificationSubscriptionMapper()->mapAvailabilityNotificationSubscriptionTransfer($availabilityNotificationSubscriptionEntity);
    }

    /**
     * @param string $subscriptionKey
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer|null
     */
    public function findOneBySubscriptionKey(string $subscriptionKey): ?AvailabilityNotificationSubscriptionTransfer
    {
        $availabilityNotificationSubscriptionEntity = $this->querySubscription()
            ->filterBySubscriptionKey($subscriptionKey)
            ->findOne();

        if ($availabilityNotificationSubscriptionEntity === null) {
            return null;
        }

        return $this->getFactory()->createAvailabilityNotificationSubscriptionMapper()->mapAvailabilityNotificationSubscriptionTransfer($availabilityNotificationSubscriptionEntity);
    }

    /**
     * @param string $sku
     * @param int $fkStore
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer[]
     */
    public function findBySkuAndStore(string $sku, int $fkStore): array
    {
        $availabilityNotificationSubscriptionEntities = $this->querySubscription()
            ->filterBySku($sku)
            ->filterByFkStore($fkStore)
            ->find();

        return $this->getFactory()
            ->createAvailabilityNotificationSubscriptionMapper()
            ->mapAvailabilityNotificationSubscriptionTransferCollection($availabilityNotificationSubscriptionEntities);
    }

    /**
     * @param string $customerReference
     * @param string $sku
     * @param int $fkStore
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionTransfer|null
     */
    public function findOneByCustomerReferenceAndSku(
        string $customerReference,
        string $sku,
        int $fkStore
    ): ?AvailabilityNotificationSubscriptionTransfer {
        $availabilityNotificationSubscriptionEntity = $this->querySubscription()
            ->filterByCustomerReference($customerReference)
            ->filterBySku($sku)
            ->filterByFkStore($fkStore)
            ->findOne();

        if ($availabilityNotificationSubscriptionEntity === null) {
            return null;
        }

        return $this->getFactory()->createAvailabilityNotificationSubscriptionMapper()->mapAvailabilityNotificationSubscriptionTransfer($availabilityNotificationSubscriptionEntity);
    }

    /**
     * @param \Generated\Shared\Transfer\AvailabilityNotificationCriteriaTransfer $availabilityNotificationCriteriaTransfer
     * @param int $fkStore
     *
     * @return \Generated\Shared\Transfer\AvailabilityNotificationSubscriptionCollectionTransfer
     */
    public function findByCustomerReference(AvailabilityNotificationCriteriaTransfer $availabilityNotificationCriteriaTransfer, int $fkStore): AvailabilityNotificationSubscriptionCollectionTransfer
    {
        $querySubscription = $this->querySubscription()
                            ->filterByCustomerReference($availabilityNotificationCriteriaTransfer->getCustomerReferences(), Criteria::IN)
                            ->filterByFkStore($fkStore)
        ;
        if ($availabilityNotificationCriteriaTransfer->getPagination()) {
            $querySubscription = $this->preparePagination($querySubscription, $availabilityNotificationCriteriaTransfer->getPagination());
        }

        $availabilityNotificationSubscriptionEntities = $querySubscription->find();

        $availabilityNotificationSubscriptionCollectionTransfer = new AvailabilityNotificationSubscriptionCollectionTransfer();

        foreach ($availabilityNotificationSubscriptionEntities as $availabilityNotificationSubscriptionEntity) {
            $availabilityNotificationSubscriptionCollectionTransfer->addAvailabilityNotificationSubscription(
                $this->getFactory()
                    ->createAvailabilityNotificationSubscriptionMapper()
                    ->mapAvailabilityNotificationSubscriptionTransfer($availabilityNotificationSubscriptionEntity)
            );
        }

        if ($availabilityNotificationCriteriaTransfer->getPagination()) {
            $availabilityNotificationSubscriptionCollectionTransfer->setPagination($availabilityNotificationCriteriaTransfer->getPagination());
        }

        return $availabilityNotificationSubscriptionCollectionTransfer;
    }

    /**
     * @return \Orm\Zed\AvailabilityNotification\Persistence\SpyAvailabilityNotificationSubscriptionQuery
     */
    protected function querySubscription(): SpyAvailabilityNotificationSubscriptionQuery
    {
        return $this->getFactory()->createAvailabilityNotificationSubscriptionQuery();
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    protected function preparePagination(ModelCriteria $query, PaginationTransfer $paginationTransfer): ModelCriteria
    {
        $page = $paginationTransfer
            ->requirePage()
            ->getPage();

        $maxPerPage = $paginationTransfer
            ->requireMaxPerPage()
            ->getMaxPerPage();

        $paginationModel = $query->paginate($page, $maxPerPage);

        $paginationTransfer->setNbResults($paginationModel->getNbResults())
            ->setFirstIndex($paginationModel->getFirstIndex())
            ->setLastIndex($paginationModel->getLastIndex())
            ->setFirstPage($paginationModel->getFirstPage())
            ->setLastPage($paginationModel->getLastPage())
            ->setNextPage($paginationModel->getNextPage())
            ->setPreviousPage($paginationModel->getPreviousPage());

        $query = $paginationModel->getQuery();

        return $query;
    }
}
