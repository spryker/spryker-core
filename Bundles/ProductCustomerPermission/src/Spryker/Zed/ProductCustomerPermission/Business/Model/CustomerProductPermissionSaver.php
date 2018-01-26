<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCustomerPermission\Business\Model;

use Orm\Zed\ProductCustomerPermission\Persistence\SpyProductCustomerPermission;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\ProductCustomerPermission\Dependency\Facade\ProductCustomerPermissionToTouchFacadeInterface;
use Spryker\Zed\ProductCustomerPermission\Persistence\ProductCustomerPermissionQueryContainerInterface;
use Spryker\Zed\ProductCustomerPermission\ProductCustomerPermissionConfig;

class CustomerProductPermissionSaver implements CustomerProductPermissionSaverInterface
{
    /**
     * @var \Spryker\Zed\ProductCustomerPermission\Persistence\ProductCustomerPermissionQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductCustomerPermission\Dependency\Facade\ProductCustomerPermissionToTouchFacadeInterface
     */
    protected $touchFacade;

    /**
     * @param \Spryker\Zed\ProductCustomerPermission\Persistence\ProductCustomerPermissionQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductCustomerPermission\Dependency\Facade\ProductCustomerPermissionToTouchFacadeInterface $touchFacade
     */
    public function __construct(
        ProductCustomerPermissionQueryContainerInterface $queryContainer,
        ProductCustomerPermissionToTouchFacadeInterface $touchFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->touchFacade = $touchFacade;
    }

    /**
     * @inheritdoc
     *
     * @param int $customerId
     * @param int $productId
     *
     * @return void
     */
    public function saveProductPermission(int $customerId, int $productId)
    {
        $productCustomerPermissionEntity = new SpyProductCustomerPermission();
        $productCustomerPermissionEntity->setFkCustomer($customerId);
        $productCustomerPermissionEntity->setFkProductAbstract($productId);
        $productCustomerPermissionEntity->save();

        $this->touchEntity($productCustomerPermissionEntity->getPrimaryKey());
    }

    /**
     * @inheritdoc
     *
     * @param int $customerId
     * @param array $productIds
     *
     * @return void
     */
    public function saveProductPermissions(int $customerId, array $productIds)
    {
        $query = count($productIds) > 0
            ? $this->queryContainer->queryProductCustomerPermissionByCustomerAndProducts($customerId, $productIds)
            : $this->queryContainer->queryProductCustomerPermissionByCustomer($customerId);

        $existingRecords = $query->find();

        $existingRecordIds = $this->getExistingRecordsIds($existingRecords);

        if (count($productIds) === 0) {
            $this->cleanEntities($existingRecordIds);
            return;
        }

        if ($existingRecords->count() === count($productIds)) {
            return;
        }

        $this->deleteProducts($existingRecordIds, $productIds);
        $this->addNewProducts($customerId, $existingRecordIds, $productIds);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $existingRecords
     *
     * @return array
     */
    protected function getExistingRecordsIds(ObjectCollection $existingRecords)
    {
        $existingRecordIds = [];
        foreach ($existingRecords as $record) {
            $existingRecordIds[$record->getFkProductAbstract()] = $record->getIdProductCustomerPermission();
        }

        return $existingRecordIds;
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

        $this->queryContainer->queryProductCustomerPermissionByIds($entityIds)
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
     * @param int $customerId
     * @param array $existingRecordIds
     * @param array $productIds
     *
     * @return void
     */
    protected function addNewProducts(int $customerId, array $existingRecordIds, array $productIds)
    {
        $productsToAdd = array_diff($productIds, array_keys($existingRecordIds));
        foreach ($productsToAdd as $productId) {
            $this->saveProductPermission($customerId, $productId);
        }
    }
}
