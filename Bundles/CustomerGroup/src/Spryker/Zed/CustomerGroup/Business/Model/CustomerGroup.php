<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerGroup\Business\Model;

use ArrayObject;
use Generated\Shared\Transfer\CustomerGroupToCustomerAssignmentTransfer;
use Generated\Shared\Transfer\CustomerGroupToCustomerTransfer;
use Generated\Shared\Transfer\CustomerGroupTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroup;
use Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupToCustomer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\CustomerGroup\Business\Exception\CustomerGroupNotFoundException;
use Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface;

class CustomerGroup implements CustomerGroupInterface
{
    /**
     * @var \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\CustomerGroup\Persistence\CustomerGroupQueryContainerInterface $queryContainer
     */
    public function __construct(CustomerGroupQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerGroupTransfer
     */
    public function get(CustomerGroupTransfer $customerGroupTransfer)
    {
        $customerGroupEntity = $this->getCustomerGroup($customerGroupTransfer);
        $customerGroupTransfer->fromArray($customerGroupEntity->toArray(), true);

        $customerGroupToCustomerCollection = $customerGroupEntity->getSpyCustomerGroupToCustomers();
        $customerGroupTransfer->setCustomers(
            $this->entityCollectionToTransferCollection($customerGroupToCustomerCollection)
        );

        return $customerGroupTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection|\Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupToCustomer[] $customerGroupToCustomerCollection
     *
     * @return \Generated\Shared\Transfer\CustomerGroupToCustomerTransfer[]|\ArrayObject
     */
    protected function entityCollectionToTransferCollection(ObjectCollection $customerGroupToCustomerCollection)
    {
        $customerGroups = new ArrayObject();

        foreach ($customerGroupToCustomerCollection as $customerGroupToCustomerEntity) {
            $customerGroupToCustomerTransfer = new CustomerGroupToCustomerTransfer();
            $customerGroupToCustomerTransfer->fromArray($customerGroupToCustomerEntity->toArray(), true);

            $customerGroups[] = $customerGroupToCustomerTransfer;
        }

        return $customerGroups;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerGroupTransfer
     */
    public function add(CustomerGroupTransfer $customerGroupTransfer)
    {
        $customerGroupEntity = new SpyCustomerGroup();
        $customerGroupEntity->fromArray($customerGroupTransfer->toArray());

        $this->queryContainer->getConnection()->beginTransaction();

        $customerGroupEntity->save();
        $customerGroupTransfer = $this->mapEntityToTransfer($customerGroupEntity, $customerGroupTransfer);

        $this->saveCustomers($customerGroupTransfer, $customerGroupEntity);

        $this->queryContainer->getConnection()->commit();

        return $customerGroupTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     *
     * @return void
     */
    public function update(CustomerGroupTransfer $customerGroupTransfer)
    {
        $customerGroupEntity = $this->getCustomerGroup($customerGroupTransfer);
        $customerGroupEntity->fromArray($customerGroupTransfer->toArray());

        $this->queryContainer->getConnection()->beginTransaction();
        $customerGroupEntity->save();

        $customerGroupTransfer = $this->mapEntityToTransfer($customerGroupEntity, $customerGroupTransfer);

        $this->removeCustomersFromGroup($customerGroupTransfer);
        $this->saveCustomers($customerGroupTransfer, $customerGroupEntity);

        $this->queryContainer->getConnection()->commit();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     * @param \Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroup $customerGroupEntity
     *
     * @return void
     */
    protected function saveCustomers(CustomerGroupTransfer $customerGroupTransfer, SpyCustomerGroup $customerGroupEntity)
    {
        $idsCustomerToAssign = $customerGroupTransfer->getCustomerAssignment() ?
            $customerGroupTransfer->getCustomerAssignment()->getIdsCustomerToAssign() :
            [];

        foreach ($idsCustomerToAssign as $idCustomerToAssign) {
            $customerGroupToCustomerEntity = new SpyCustomerGroupToCustomer();
            $customerGroupToCustomerEntity->setFkCustomerGroup($customerGroupEntity->getIdCustomerGroup());
            $customerGroupToCustomerEntity->setFkCustomer($idCustomerToAssign);

            $customerGroupToCustomerEntity->save();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     *
     * @return bool
     */
    public function delete(CustomerGroupTransfer $customerGroupTransfer)
    {
        $customerEntity = $this->getCustomerGroup($customerGroupTransfer);
        $customerEntity->delete();

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     *
     * @throws \Spryker\Zed\CustomerGroup\Business\Exception\CustomerGroupNotFoundException
     *
     * @return \Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroup
     */
    protected function getCustomerGroup(CustomerGroupTransfer $customerGroupTransfer)
    {
        $customerGroupTransfer->requireIdCustomerGroup();

        $customerEntity = $this->queryContainer->queryCustomerGroupById($customerGroupTransfer->getIdCustomerGroup())
                ->findOne();

        if (!$customerEntity) {
            throw new CustomerGroupNotFoundException(sprintf(
                'Customer group not found by ID `%s`',
                $customerGroupTransfer->getIdCustomerGroup()
            ));
        }

        return $customerEntity;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     *
     * @return void
     */
    public function removeCustomersFromGroup(CustomerGroupTransfer $customerGroupTransfer)
    {
        $customerGroupTransfer
            ->requireIdCustomerGroup();

        $idsCustomerToDeAssign = $customerGroupTransfer->getCustomerAssignment() ?
            $customerGroupTransfer->getCustomerAssignment()->getIdsCustomerToDeAssign() :
            [];

        foreach ($idsCustomerToDeAssign as $idCustomer) {
            $customerEntity = $this->queryContainer
                ->queryCustomerGroupToCustomerByFkCustomerGroup($customerGroupTransfer->getIdCustomerGroup())
                ->filterByFkCustomer($idCustomer)->findOne();

            if (!$customerEntity) {
                continue;
            }

            $customerEntity->delete();
        }
    }

    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerGroupTransfer|null
     */
    public function findCustomerGroupByIdCustomer($idCustomer)
    {
        $customerGroupEntity = $this->queryContainer
            ->queryCustomerGroupByFkCustomer($idCustomer)
            ->findOne();

        if (!$customerGroupEntity) {
            return null;
        }

        $customerGroupTransfer = new CustomerGroupTransfer();
        $customerGroupTransfer = $this->mapEntityToTransfer($customerGroupEntity, $customerGroupTransfer);

        return $customerGroupTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function removeCustomerFromAllGroups(CustomerTransfer $customerTransfer)
    {
        $customerTransfer->requireIdCustomer();

        $customerGroupTransfers = $this->findCustomerGroupsByIdCustomer($customerTransfer->getIdCustomer());

        foreach ($customerGroupTransfers as $customerGroupTransfer) {
            $customerGroupTransfer
                ->getCustomerAssignment()
                ->addIdCustomerToDeAssign(
                    $customerTransfer->getIdCustomer()
                );

            $this->removeCustomersFromGroup($customerGroupTransfer);
        }
    }

    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CustomerGroupTransfer[]
     */
    protected function findCustomerGroupsByIdCustomer($idCustomer)
    {
        $customerGroupEntities = $this->queryContainer
            ->queryCustomerGroupByFkCustomer($idCustomer)
            ->find();

        $groups = [];

        foreach ($customerGroupEntities as $customerGroupEntity) {
            $customerAssignmentTransfer = new CustomerGroupToCustomerAssignmentTransfer();
            $customerAssignmentTransfer->setIdCustomerGroup($customerGroupEntity->getIdCustomerGroup());

            $customerGroupTransfer = new CustomerGroupTransfer();
            $customerGroupTransfer->setCustomerAssignment($customerAssignmentTransfer);

            $customerGroupTransfer->fromArray($customerGroupEntity->toArray(), true);

            $groups[] = $customerGroupTransfer;
        }

        return $groups;
    }

    /**
     * @param \Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroup $customerGroupEntity
     * @param \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerGroupTransfer
     */
    protected function mapEntityToTransfer(SpyCustomerGroup $customerGroupEntity, CustomerGroupTransfer $customerGroupTransfer)
    {
        $customerGroupTransfer->fromArray($customerGroupEntity->toArray(), true);

        return $customerGroupTransfer;
    }
}
