<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCustomerPermission\Business\Model;

use Orm\Zed\ProductCustomerPermission\Persistence\SpyProductCustomerPermission;
use Spryker\Zed\ProductCustomerPermission\Dependency\Facade\ProductCustomerPermissionToTouchInterface;
use Spryker\Zed\ProductCustomerPermission\Persistence\ProductCustomerPermissionQueryContainerInterface;
use Spryker\Zed\ProductCustomerPermission\ProductCustomerPermissionConfig;

class CustomerProductPermissionSaver implements CustomerProductPermissionSaverInterface
{
    /** @var int */
    protected $customerId;

    /** @var \Spryker\Zed\ProductCustomerPermission\Persistence\ProductCustomerPermissionQueryContainerInterface */
    protected $queryContainer;

    /** @var \Spryker\Zed\ProductCustomerPermission\Dependency\Facade\ProductCustomerPermissionToTouchInterface */
    protected $touchFacade;

    /**
     * @param int $customerId
     * @param \Spryker\Zed\ProductCustomerPermission\Persistence\ProductCustomerPermissionQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductCustomerPermission\Dependency\Facade\ProductCustomerPermissionToTouchInterface $touchFacade
     */
    public function __construct(
        int $customerId,
        ProductCustomerPermissionQueryContainerInterface $queryContainer,
        ProductCustomerPermissionToTouchInterface $touchFacade
    ) {
        $this->customerId = $customerId;
        $this->queryContainer = $queryContainer;
        $this->touchFacade = $touchFacade;
    }

    /**
     * @param int $productId
     *
     * @return void
     */
    public function saveProductPermission(int $productId)
    {
        $productCustomerPermissionEntity = new SpyProductCustomerPermission();
        $productCustomerPermissionEntity->setFkCustomer($this->customerId);
        $productCustomerPermissionEntity->setFkProductAbstract($productId);
        $productCustomerPermissionEntity->save();

        $this->touchEntity($productCustomerPermissionEntity->getPrimaryKey());
    }

    /**
     * @param array $productIds
     *
     * @return void
     */
    public function saveProductPermissions(array $productIds)
    {
        $query = $this->queryContainer->queryProductCustomerPermission();
        $query->filterByFkCustomer($this->customerId);
        if ($productIds) {
            $query->filterByFkProductAbstract_In($productIds);
        }
        $existingRecords = $query->find();

        $existingRecordIds = [];
        foreach ($existingRecords as $record) {
            $existingRecordIds[$record->getFkProductAbstract()] = $record->getIdProductCustomerPermission();
        }

        if (empty($productIds)) {
            $this->cleanEntities($existingRecordIds);
            return;
        }

        if ($existingRecords->count() === count($productIds)) {
            return;
        }

        $this->deleteProducts($existingRecordIds, $productIds);
        $this->addNewProducts($existingRecordIds, $productIds);
    }

    /**
     * @param int $entityId
     *
     * @return void
     */
    protected function touchEntity(int $entityId)
    {
        $this->touchFacade->touchActive(ProductCustomerPermissionConfig::RESOURCE_TYPE_PRODUCT_CUSTOMER_PERMISSION, $entityId);
    }

    /**
     * @param array $entityIds
     *
     * @return void
     */
    protected function cleanEntities(array $entityIds)
    {
        if (!$entityIds) {
            return;
        }

        foreach ($entityIds as $entityId) {
            $this->touchFacade->touchDeleted(ProductCustomerPermissionConfig::RESOURCE_TYPE_PRODUCT_CUSTOMER_PERMISSION, $entityId);
        }

        $this->queryContainer->queryProductCustomerPermission()
            ->filterByIdProductCustomerPermission_In($entityIds)
            ->delete();
    }

    /**
     * @param array $existingRecordIds
     * @param array $productIds
     *
     * @return void
     */
    protected function deleteProducts(array $existingRecordIds, array $productIds)
    {
        $productsToDelete = array_diff(array_keys($existingRecordIds), $productIds);
        $entitiesToDelete = [];
        foreach ($productsToDelete as $productId) {
            $entitiesToDelete[] = $existingRecordIds[$productId];
        }
        $this->cleanEntities($entitiesToDelete);
    }

    /**
     * @param array $existingRecordIds
     * @param array $productIds
     *
     * @return void
     */
    protected function addNewProducts(array $existingRecordIds, array $productIds)
    {
        $productsToAdd = array_diff($productIds, array_keys($existingRecordIds));
        foreach ($productsToAdd as $productId) {
            $this->saveProductPermission($productId);
        }
    }
}
